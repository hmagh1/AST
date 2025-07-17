<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\AdministrateurUCACRepository;

/**
 * @ORM\Entity(repositoryClass=AdministrateurUCACRepository::class)
 */
class AdministrateurUCAC
{
    /** @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer") @Groups({"admin:read"}) */
    private $id;

    /** @ORM\Column(type="string", length=100) @Groups({"admin:read"}) */
    private $nom;

    /** @ORM\Column(type="string", length=150) @Groups({"admin:read"}) */
    private $email;

    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
}
