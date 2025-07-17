<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\DRHRepository;

/**
 * @ORM\Entity(repositoryClass=DRHRepository::class)
 */
class DRH
{
    /** @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer") @Groups({"drh:read"}) */
    private $id;

    /** @ORM\Column(type="string", length=100) @Groups({"drh:read"}) */
    private $nom;

    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
}
