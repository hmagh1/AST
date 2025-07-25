<?php
namespace App\Entity;

use App\Entity\Astreignable; // ✅ Import manquant ici !
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\PlanningAstreinteRepository;

/**
 * @ORM\Entity(repositoryClass=PlanningAstreinteRepository::class)
 */
class PlanningAstreinte
{
    /** @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer") @Groups({"planning:read"}) */
    private $id;

    /** @ORM\Column(type="date") @Groups({"planning:read"}) */
    private $dateDebut;

    /** @ORM\Column(type="date") @Groups({"planning:read"}) */
    private $dateFin;

    /** @ORM\Column(type="string", length=100) @Groups({"planning:read"}) */
    private $theme;

    /** @ORM\Column(type="string", length=50) @Groups({"planning:read"}) */
    private $statut;

  /**
 * @ORM\ManyToMany(targetEntity=Astreignable::class, inversedBy="plannings")
 * @ORM\JoinTable(name="planning_astreinte_binome")
 */
private Collection $binome;

    public function __construct()
    {
        $this->binome = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $date): self
    {
        $this->dateDebut = $date;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $date): self
    {
        $this->dateFin = $date;
        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): self
    {
        $this->theme = $theme;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getBinome(): Collection
    {
        return $this->binome;
    }

    public function addBinome(Astreignable $a): self
    {
        if (!$this->binome->contains($a)) {
            $this->binome[] = $a;
        }
        return $this;
    }

    public function removeBinome(Astreignable $a): self
    {
        $this->binome->removeElement($a);
        return $this;
    }
    public function getBinomeList(): string
{
    return implode(', ', array_map(
        fn($a) => $a->getPrenom() . ' ' . $a->getNom(),
        $this->binome->toArray()
    ));
}

public function getBinomeNames(): string
{
    return implode(', ', $this->binome->map(fn($a)=> $a->getPrenom().' '.$a->getNom())->toArray());
}


}
