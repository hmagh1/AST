<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\AstreignableRepository;

/**
 * @ORM\Entity(repositoryClass=AstreignableRepository::class)
 */
class Astreignable
{
    /** @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer") @Groups({"astreignable:read"}) */
    private $id;

    /** @ORM\Column(type="string", length=100) @Groups({"astreignable:read"}) */
    private $nom;

    /** @ORM\Column(type="string", length=100) @Groups({"astreignable:read"}) */
    private $prenom;

    /** @ORM\Column(type="string", length=150) @Groups({"astreignable:read"}) */

    private $email;

    /** @ORM\Column(type="string", length=20) @Groups({"astreignable:read"}) */
    private $telephone;

    /** @ORM\Column(type="string", length=50) @Groups({"astreignable:read"}) */
    private $seniorite;

    /** @ORM\Column(type="string", length=100) @Groups({"astreignable:read"}) */
    private $direction;

    /** @ORM\Column(type="boolean") @Groups({"astreignable:read"}) */
    private $disponible;

    /** @ORM\ManyToMany(targetEntity="PlanningAstreinte", mappedBy="binome") */
    private $plannings;

    /** @ORM\OneToMany(targetEntity="ServiceFait", mappedBy="astreignable") */
    private $services;

    /** @ORM\OneToMany(targetEntity="MainCourante", mappedBy="astreignable") */
    private $mainCourantes;

    public function __construct()
    {
        $this->plannings = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->mainCourantes = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getPrenom(): ?string { return $this->prenom; }
    public function setPrenom(string $prenom): self { $this->prenom = $prenom; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function getTelephone(): ?string { return $this->telephone; }
    public function setTelephone(string $telephone): self { $this->telephone = $telephone; return $this; }
    public function getSeniorite(): ?string { return $this->seniorite; }
    public function setSeniorite(string $seniorite): self { $this->seniorite = $seniorite; return $this; }
    public function getDirection(): ?string { return $this->direction; }
    public function setDirection(string $direction): self { $this->direction = $direction; return $this; }
    public function getDisponible(): ?bool { return $this->disponible; }
    public function setDisponible(bool $disponible): self { $this->disponible = $disponible; return $this; }

    public function isDisponible(): ?bool
    {
        return $this->disponible;
    }

    /**
     * @return Collection<int, PlanningAstreinte>
     */
    public function getPlannings(): Collection
    {
        return $this->plannings;
    }

    public function addPlanning(PlanningAstreinte $planning): self
    {
        if (!$this->plannings->contains($planning)) {
            $this->plannings[] = $planning;
            $planning->addBinome($this);
        }

        return $this;
    }

    public function removePlanning(PlanningAstreinte $planning): self
    {
        if ($this->plannings->removeElement($planning)) {
            $planning->removeBinome($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ServiceFait>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(ServiceFait $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setAstreignable($this);
        }

        return $this;
    }

    public function removeService(ServiceFait $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getAstreignable() === $this) {
                $service->setAstreignable(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MainCourante>
     */
    public function getMainCourantes(): Collection
    {
        return $this->mainCourantes;
    }

    public function addMainCourante(MainCourante $mainCourante): self
    {
        if (!$this->mainCourantes->contains($mainCourante)) {
            $this->mainCourantes[] = $mainCourante;
            $mainCourante->setAstreignable($this);
        }

        return $this;
    }

    public function removeMainCourante(MainCourante $mainCourante): self
    {
        if ($this->mainCourantes->removeElement($mainCourante)) {
            // set the owning side to null (unless already changed)
            if ($mainCourante->getAstreignable() === $this) {
                $mainCourante->setAstreignable(null);
            }
        }

        return $this;
    }
}
