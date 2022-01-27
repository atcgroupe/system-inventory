<?php

namespace App\Security;

use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Exception\ConnectionException;
use Symfony\Component\Ldap\LdapInterface;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private UserRoleProvider $roleProvider;

    public function __construct(
        private LdapInterface $ldap,
        private string        $baseDn,
        private string        $searchDn,
        private string        $searchPassword,
        private array         $roles,
        private string        $mailAttr,
        private string        $displayNameAttr,
        private array         $extraAttributes = [],
    ) {
        $this->roleProvider = new UserRoleProvider($this->ldap, $this->baseDn, $this->roles);
    }

    /**
     * @throws UserNotFoundException if the user is not found
     */
    public function loadUserByIdentifier($identifier): UserInterface
    {
        try {
            $this->ldap->bind($this->searchDn, $this->searchPassword);
            $identifier = $this->ldap->escape($identifier, '', LdapInterface::ESCAPE_FILTER);
            $query = sprintf('(&(objectClass=user)(sAMAccountName=%s))', $identifier);
            $search = $this->ldap->query($this->baseDn, $query);
        } catch (ConnectionException $e) {
            $e = new UserNotFoundException(sprintf('User "%s" not found.', $identifier), 0, $e);
            $e->setUserIdentifier($identifier);

            throw $e;
        }

        $entries = $search->execute();

        if (0 === $entries->count()) {
            $e = new UserNotFoundException(sprintf('User "%s" not found.', $identifier));
            $e->setUserIdentifier($identifier);

            throw $e;
        }

        if ($entries->count() > 1) {
            $e = new UserNotFoundException('More than one user found.');
            $e->setUserIdentifier($identifier);

            throw $e;
        }

        $entry = $entries[0];

        return $this->loadUser($identifier, $entry);
    }

    /**
     * @deprecated since Symfony 5.3, loadUserByIdentifier() is used instead
     */
    public function loadUserByUsername($username): UserInterface
    {
        return $this->loadUserByIdentifier($username);
    }

    /**
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        $userClass = get_class($user);
        if (!$this->supportsClass($userClass)) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', $userClass));
        }

        /** @var User $appUser */
        $appUser = $user;

        return new User(
            $appUser->getUserIdentifier(),
            $appUser->getRoles(),
            $appUser->getEmail(),
            $appUser->getDisplayName(),
            $appUser->getAttributes(),
        );
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass($class): bool
    {
        return User::class === $class;
    }

    private function loadUser(string $identifier,Entry $entry): UserInterface
    {
        return new User(
            $identifier,
            $this->roleProvider->getRoles($identifier),
            $this->getUserAttribute($this->mailAttr, $entry),
            $this->getUserAttribute($this->displayNameAttr, $entry),
            $this->getUserExtraAttributes($entry)
        );
    }

    /**
     * @param Entry $entry
     *
     * @return array|null
     */
    private function getUserExtraAttributes(Entry $entry): array | null
    {
        $attributes = [];

        if (empty($this->extraAttributes)) {
            return [];
        }

        foreach ($this->extraAttributes as $attribute) {
            $attributes[$attribute] = $this->getUserAttribute($attribute, $entry);
        }

        return (!empty($attributes)) ? $attributes : null;
    }

    private function getUserAttribute(string $attribute, Entry $entry): string | array
    {
        if (!$entry->hasAttribute($attribute)) {
            throw new InvalidArgumentException(sprintf('The argument "%s" is missing in user Ldap Entry', $attribute));
        }

        $attribute = $entry->getAttribute($attribute);

        if (1 === \count($attribute)) {
            return $attribute[0];
        }

        return $attribute;
    }
}
