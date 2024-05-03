<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * @ORM\Entity(repositoryClass=InscriptionRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Inscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom ne peut pas être vide.")
     * @Assert\Length(max=255, maxMessage="Le nom ne peut pas dépasser {{ limit }} caractères.")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le prénom ne peut pas être vide.")
     * @Assert\Length(max=255, maxMessage="Le prénom ne peut pas dépasser {{ limit }} caractères.")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'adresse email ne peut pas être vide.")
     * @Assert\Email(message="L'adresse email '{{ value }}' n'est pas valide.")
     * @Assert\Length(max=255, maxMessage="L'adresse email ne peut pas dépasser {{ limit }} caractères.")
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateNow;

    /**
     * @ORM\ManyToOne(targetEntity=Formation::class, inversedBy="inscriptions")
     */
    private $formation;

    public function __construct()
    {
        $this->dateNow = new \DateTime();
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->dateNow = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDateNow(): ?\DateTimeInterface
    {
        return $this->dateNow;
    }

    public function setDateNow(\DateTimeInterface $dateNow): self
    {
        $this->dateNow = $dateNow;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }
}
