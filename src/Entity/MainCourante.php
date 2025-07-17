<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\MainCouranteRepository;

/**
 * @ORM\Entity(repositoryClass=MainCouranteRepository::class)
 */
class MainCourante
{
    /** @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer") @Groups({"maincourante:read"}) */
    private $id;

    /** @ORM\Column(type="date") @Groups({"maincourante:read"}) */
    private $date;

    /** @ORM\Column(type="text") @Groups({"maincourante:read"}) */
    private $details;

    /** @ORM\ManyToOne(targetEntity="Astreignable", inversedBy="mainCourantes") @ORM\JoinColumn(nullable=false) */
    private $astreignable;

    public function getId(): ?int { return $this->id; }
    public function getDate(): ?\DateTimeInterface { return $this->date; }
    public function setDate(\DateTimeInterface $date): self { $this->date = $date; return $this; }
    public function getDetails(): ?string { return $this->details; }
    public function setDetails(string $d): self { $this->details = $d; return $this; }

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
