<?php
// src/Entity/User.php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /** 
     * @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer") 
     */
    private int $id;

    /** 
     * @ORM\Column(type="string", length=180, unique=true) 
     */
    private string $email;

    /** 
     * @ORM\Column(type="json") 
     */
    private array $roles = [];

    /** 
     * @ORM\Column(type="string") 
     */
    private string $password;

    /**
     * @ORM\OneToOne(targetEntity=AdministrateurUCAC::class, inversedBy="user", cascade={"persist"})
     * @ORM\JoinColumn(name="administrateur_ucac_id", referencedColumnName="id", nullable=true)
     */
    private ?AdministrateurUCAC $adminProfile = null;

    /**
     * @ORM\OneToOne(targetEntity=Astreignable::class, inversedBy="user", cascade={"persist"})
     * @ORM\JoinColumn(name="astreignable_id", referencedColumnName="id", nullable=true)
     */
    private ?Astreignable $astreignableProfile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /** @deprecated since Symfony 5.3 */
    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee at least ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        // not needed with modern algos
        return null;
    }

    public function eraseCredentials(): void
    {
        // si vous aviez un plainPassword à effacer
    }

    public function getAdminProfile(): ?AdministrateurUCAC
    {
        return $this->adminProfile;
    }

    public function setAdminProfile(?AdministrateurUCAC $admin): self
    {
        $this->adminProfile = $admin;
        return $this;
    }

    public function getAstreignableProfile(): ?Astreignable
    {
        return $this->astreignableProfile;
    }

    public function setAstreignableProfile(?Astreignable $astreignable): self
    {
        $this->astreignableProfile = $astreignable;
        return $this;
    }
}
