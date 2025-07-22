<?php
namespace App\Entity;

use App\Repository\AdministrateurUCACRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AdministrateurUCACRepository::class)
 */
class AdministrateurUCAC
{
    /** 
     * @ORM\Id 
     * @ORM\GeneratedValue 
     * @ORM\Column(type="integer") 
     * @Groups({"admin:read"}) 
     */
    private ?int $id = null;

    /** 
     * @ORM\Column(type="string", length=100) 
     * @Groups({"admin:read"}) 
     */
    private string $nom;

    /** 
     * @ORM\Column(type="string", length=150) 
     * @Groups({"admin:read"}) 
     */
    private string $email;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        // On aligne l’email métier avec l’email de connexion
        $this->email = $user->getUserIdentifier();
        return $this;
    }
}
