<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\ServiceFaitRepository;

/**
 * @ORM\Entity(repositoryClass=ServiceFaitRepository::class)
 */
class ServiceFait
{
    /** @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer") @Groups({"servicefait:read"}) */
    private $id;

    /** @ORM\Column(type="date") @Groups({"servicefait:read"}) */
    private $date;

    /** @ORM\Column(type="integer") @Groups({"servicefait:read"}) */
    private $heuresEffectuees;

    /** @ORM\Column(type="boolean") @Groups({"servicefait:read"}) */
    private $valide;

    /** @ORM\ManyToOne(targetEntity="Astreignable", inversedBy="services") @ORM\JoinColumn(nullable=false) */
    private $astreignable;

    public function getId(): ?int { return $this->id; }
    public function getDate(): ?\DateTimeInterface { return $this->date; }
    public function setDate(\DateTimeInterface $date): self { $this->date = $date; return $this; }
    public function getHeuresEffectuees(): ?int { return $this->heuresEffectuees; }
    public function setHeuresEffectuees(int $h): self { $this->heuresEffectuees = $h; return $this; }
    public function getValide(): ?bool { return $this->valide; }
    public function setValide(bool $valide): self { $this->valide = $valide; return $this; }

    public function isValide(): ?bool
    {
        return $this->valide;
    }

    public function getAstreignable(): ?Astreignable
    {
        return $this->astreignable;
    }

    public function setAstreignable(?Astreignable $astreignable): self
    {
        $this->astreignable = $astreignable;

        return $this;
    }
}
