<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @final
 */
final class User implements UserInterface
{
    public function __construct(
        private string $username,
        private array  $roles,
        private string $email,
        private string $displayName,
        private array | null  $attributes,
    ) {}

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getDisplayName(?string $mode = null): string
    {
        switch ($mode) {
            case 'initials':
                $explode = explode(' ', $this->getDisplayName());
                $initials = '';
                foreach ($explode as $item) {
                    $initials .= substr($item, 0, 1);
                }
                return strtoupper($initials);

            default:
                return $this->displayName;
        }
    }

    /**
     * @return array|null
     */
    public function getAttributes(): array | null
    {
        return $this->attributes;
    }

    /**
     * @param string $attribute
     *
     * @return string|null
     */
    public function getAttribute(string $attribute): string | null
    {
        if (!array_key_exists($attribute, $this->attributes)) {
            return null;
        }

        return $this->attributes[$attribute];
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }
}
