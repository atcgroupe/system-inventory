<?php

namespace App\Security;

use Symfony\Component\Ldap\LdapInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;

class UserRoleProvider
{
    private array $roles;

    public function __construct(
        private LdapInterface $ldap,
        private string $baseDn,
        array $roles
    ) {
        $resolver = new OptionsResolver();
        $this->configureRolesOptions($resolver);

        $this->roles = $resolver->resolve($roles);
    }

    /**
     * @param string $identifier
     *
     * @return array
     *
     *
     */
    public function getRoles(string $identifier): array
    {
        $roles = [];

        foreach ($this->roles as $role => $securityGroup) {
            if ($this->isMemberOf($securityGroup, $identifier)) {
                $roles[] = $role;
            }
        }

        if (0 === \count($roles)) {
            throw new AuthenticationException(sprintf(
                'The user "%s" has no permissions for this app. Did you miss adding him to a security group.
                The actual configured roles are:[%s]',
                $identifier,
                $this->getRolesConfigAsString()
            ));
        }

        return $roles;
    }

    /**
     * @param string $groupName
     * @param string $identifier
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    private function isMemberOf(string $groupName, string $identifier): bool
    {
        $query = $this->ldap->query(
            $this->baseDn,
            sprintf('(&(objectClass=group)(sAMAccountName=%s))', $groupName)
        );

        $entries = $query->execute();

        if (0 === $entries->count()) {
            throw new InvalidArgumentException(sprintf('The security group "%s" was not found', $groupName));
        }

        if ($entries->count() > 1) {
            throw new InvalidArgumentException(sprintf('More than one security group with the name "%s" was found', $groupName));
        }

        $entry = $entries[0];

        $query = $this->ldap->query(
            $this->baseDn,
            sprintf(
                '(&(objectClass=user)(memberof:1.2.840.113556.1.4.1941:=%s)(sAMAccountName=%s))',
                $entry->getDn(),
                $identifier
            )
        );

        $entries = $query->execute();

        return 0 !== $entries->count();
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    private function configureRolesOptions(OptionsResolver $resolver): void
    {
        $resolver->define('ROLE_ADMIN')
            ->required()
            ->allowedTypes('string');

        $resolver->define('ROLE_USER')
            ->required()
            ->allowedTypes('string');
    }

    /**
     * @return string
     */
    private function getRolesConfigAsString(): string
    {
        $rolesConfig = '';
        $count = \count($this->roles);
        $i = 1;

        foreach ($this->roles as $role => $securityGroup) {
            $comma = ($i < $count) ? ', ' : null;
            $rolesConfig .= sprintf('%s => %s%s', $role, $securityGroup, $comma);
            $i++;
        }

        return $rolesConfig;
    }
}
