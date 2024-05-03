<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le titre ne peut pas être vide.")
     * @Assert\Length(max=255, maxMessage="Le titre ne peut pas dépasser {{ limit }} caractères.")
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La description ne peut pas être vide.")
     * @Assert\Length(max=255, maxMessage="La description ne peut pas dépasser {{ limit }} caractères.")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le média ne peut pas être vide.")
     * @Assert\Url(message="Le média doit être une URL valide.")
     */
    private $media;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le nombre de places ne peut pas être vide.")
     * @Assert\Type(type="integer", message="Le nombre de places doit être un nombre entier.")
     */
    private $nbPlaces;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Le prix ne peut pas être vide.")
     * @Assert\Type(type="float", message="Le prix doit être un nombre décimal.")
     */
    private $prix;

    /**
     * @ORM\OneToMany(targetEntity=Inscription::class, mappedBy="formation")
     */
    private $inscriptions;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $evaluation;

    // Getter and setter for evaluation attribute
    public function getEvaluation(): ?array
    {
        return $this->evaluation;
    }

    public function setEvaluation(?array $evaluation): self
    {
        $this->evaluation = $evaluation;

        return $this;
    }


    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(string $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getNbPlaces(): ?int
    {
        return $this->nbPlaces;
    }

    public function setNbPlaces(int $nbPlaces): self
    {
        $this->nbPlaces = $nbPlaces;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, Inscription>
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setFormation($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getFormation() === $this) {
                $inscription->setFormation(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->titre;
    }
}
