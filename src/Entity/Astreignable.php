<?php
// src/Entity/Astreignable.php
namespace App\Entity;

use App\Repository\AstreignableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\User;

/**
 * @ORM\Entity(repositoryClass=AstreignableRepository::class)
 */
class Astreignable
{
    /** @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer") @Groups({"astreignable:read"}) */
    private ?int $id = null;

    /** @ORM\Column(type="string", length=100) @Groups({"astreignable:read"}) */
    private string $nom;

    /** @ORM\Column(type="string", length=100) @Groups({"astreignable:read"}) */
    private string $prenom;

    /** @ORM\Column(type="string", length=150) @Groups({"astreignable:read"}) */
    private string $email;

    /** @ORM\Column(type="string", length=20) @Groups({"astreignable:read"}) */
    private string $telephone;

    /** @ORM\Column(type="string", length=50) @Groups({"astreignable:read"}) */
    private string $seniorite;

    /** @ORM\Column(type="string", length=100) @Groups({"astreignable:read"}) */
    private string $direction;

    /** @ORM\Column(type="boolean") @Groups({"astreignable:read"}) */
    private bool $disponible;

    /** @ORM\ManyToMany(targetEntity=App\Entity\PlanningAstreinte::class, mappedBy="binome") */
    private Collection $plannings;

    /** @ORM\OneToMany(targetEntity=App\Entity\ServiceFait::class, mappedBy="astreignable") */
    private Collection $services;

    /** @ORM\OneToMany(targetEntity=App\Entity\MainCourante::class, mappedBy="astreignable") */
    private Collection $mainCourantes;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="astreignableProfile", cascade={"persist", "remove"})
     */
    private ?User $user = null;

    /**
     * Champ virtuel pour saisir le mot de passe en clair dans EasyAdmin.
     * Il n’est pas persisté par Doctrine.
     *
     * @Groups({"astreignable:read"})
     */
    private ?string $plainPassword = null;

    public function __construct()
    {
        $this->plannings     = new ArrayCollection();
        $this->services      = new ArrayCollection();
        $this->mainCourantes = new ArrayCollection();
    }

    // -- getters / setters pour les champs persistés --

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

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
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

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getSeniorite(): string
    {
        return $this->seniorite;
    }

    public function setSeniorite(string $seniorite): self
    {
        $this->seniorite = $seniorite;
        return $this;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): self
    {
        $this->direction = $direction;
        return $this;
    }

    public function getDisponible(): bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): self
    {
        $this->disponible = $disponible;
        return $this;
    }

    public function isDisponible(): bool
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
        if ($this->services->removeElement($service) && $service->getAstreignable() === $this) {
            $service->setAstreignable(null);
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
            if ($mainCourante->getAstreignable() === $this) {
                $mainCourante->setAstreignable(null);
            }
        }
        return $this;
    }

    // -- relation utilisateur --

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        if ($user && $user->getAstreignableProfile() !== $this) {
            $user->setAstreignableProfile($this);
        }
        return $this;
    }

    // -- getters / setters pour le mot de passe en clair --

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

public function __toString(): string
{
    return $this->prenom . ' ' . $this->nom;
}


}
