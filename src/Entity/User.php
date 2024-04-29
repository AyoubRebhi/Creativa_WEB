<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUser;

    /**
     * @var string|null
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Le nom de famille ne peut pas être vide.")
     */
    private $lastName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Le prénom ne peut pas être vide.")
     */
    private $firstName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Le nom d'utilisateur ne peut pas être vide.")
     */
    private $username;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Le mot de passe ne peut pas être vide.")
     * @Assert\Length(min=8, minMessage="Le mot de passe doit contenir au moins {{ limit }} caractères.")
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="role", type="string", length=255, nullable=true)
     */
    private $role;

    /**
     * @var string|null
     *
     * @ORM\Column(name="biography", type="text", length=65535, nullable=true)
     */
    private $biography;

    /**
     * @var string|null
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string|null
     *
     * @ORM\Column(name="profile_image_path", type="string", length=255, nullable=true)
     */
    private $profileImagePath;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="L'email ne peut pas être vide.")
     * @Assert\Email(message="L'email '{{ value }}' n'est pas valide.")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="ImgPath", type="string", length=255, nullable=false)
     */
    private $imgpath;

    /**
     * @var int
     *
     * @ORM\Column(name="numTel", type="integer", nullable=false)
     * @Assert\NotBlank(message="Le numéro de téléphone ne peut pas être vide.")
     * @Assert\Length(
     *     min=8,
     *     max=8,
     *     exactMessage="Le numéro de téléphone doit contenir exactement {{ limit }} chiffres."
     * )
     */
    private $numtel;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="blocked", type="boolean", nullable=true)
     */
    private $blocked;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="block_end_date", type="datetime", nullable=true)
     */
    private $blockEndDate;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Projet", mappedBy="user")
     */
    private $projets;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->projets = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->idUser;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): static
    {
        $this->biography = $biography;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getProfileImagePath(): ?string
    {
        return $this->profileImagePath;
    }

    public function setProfileImagePath(?string $profileImagePath): static
    {
        $this->profileImagePath = $profileImagePath;

        return $this;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getImgpath(): ?string
    {
        return $this->imgpath;
    }

    public function setImgpath(string $imgpath): static
    {
        $this->imgpath = $imgpath;

        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(int $numtel): static
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function isBlocked(): ?bool
    {
        return $this->blocked;
    }

    public function setBlocked(?bool $blocked): static
    {
        $this->blocked = $blocked;

        return $this;
    }

    public function getBlockEndDate(): ?\DateTimeInterface
    {
        return $this->blockEndDate;
    }

    public function setBlockEndDate(?\DateTimeInterface $blockEndDate): static
    {
        $this->blockEndDate = $blockEndDate;

        return $this;
    }


    /**
     * @return Collection<int, Projet>
     */
    public function getProjets(): Collection
    {
        return $this->projets;
    }

    public function addProjet(Projet $projet): static
    {
        if (!$this->projets->contains($projet)) {
            $this->projets->add($projet);
            $projet->setUser($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): static
    {
        if ($this->projets->removeElement($projet)) {
            // set the owning side to null (unless already changed)
            if ($projet->getUser() === $this) {
                $projet->setUser(null);
            }
        }

        return $this;
    }


    public function getRoles()
    {
        // Retourner un tableau de rôles, par exemple ['ROLE_USER']
        return $this->role ? [$this->role] : [];
    }


    public function getSalt()
    {
        // Vous n'avez pas besoin de sel car bcrypt gère cela pour vous
        return null;
    }

    public function eraseCredentials()
    {
        // Supprimer les données sensibles de l'utilisateur
        // Cette méthode est nécessaire pour effacer les mots de passe en texte brut
    }
}
