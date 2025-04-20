<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
class Student implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $dob = null;

    #[ORM\Column(type: 'string', length: 10)]
    private ?string $gender = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'string', length: 20)]
    private ?string $role = 'STUDENT';

    // --- Getters and Setters (minimum needed) ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getDob(): ?\DateTimeInterface
    {
        return $this->dob;
    }

    public function setDob(\DateTimeInterface $dob): self
    {
        $this->dob = $dob;
        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    // --- UserInterface methods ---

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function eraseCredentials(): void
    {
        // If we stored any temporary sensitive data on the user, clear it here
    }

    public function getSalt(): ?string
    {
        return null;
    }
}
