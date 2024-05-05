<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="id_user", columns={"id_user"}), @ORM\Index(name="id_projet", columns={"id_projet"})})
 * @ORM\Entity
 */
class Commande
{


    /**
     * @var int
     *
     * @ORM\Column(name="id_cmd", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCmd;

    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=true)
     * @Assert\NotBlank(message="id user ne peut pas être vide.")
     */
    private $idUser;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_projet", type="integer", nullable=true)
     */
    private $idProjet;


    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mt_total", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Le montant total ne peut pas être vide.")
     */
    private $mtTotal;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_livraison_estimee", type="date", nullable=true)
     */
    private $dateLivraisonEstimee;

    /**
     * @var int|null
     *
     * @ORM\Column(name="code_promo", type="integer", nullable=true)
     * @Assert\Length(
     *      min=4,
     *      max=4,
     *      exactMessage="Le code promo doit contenir exactement {{ limit }} chiffres."
     * )
     */
    private $codePromo;

    const STATUS_EN_COURS = 'En cours';
    const STATUS_ANNULE = 'Annulée';
    /**
     * @var string|null
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=true)
     * @Assert\NotBlank(message="Le prix ne peut pas être vide.")
     */
    private $prix;

    /**
     * @var float|null
     *
     * @ORM\Column(name="frais_liv", type="float", precision=10, scale=0, nullable=true)
     */
    private $fraisLiv;



    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Livraison", mappedBy="commande", cascade={"persist", "remove"})
     */
    private $livraison;


    // Getter et setter pour la relation OneToOne avec Livraison
    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function setLivraison(?Livraison $livraison): self
    {
        $this->livraison = $livraison;

        // Définir la relation inverse si nécessaire
        if ($livraison !== null && $livraison->getCommande() !== $this) {
            $livraison->setCommande($this);
        }

        return $this;
    }

    /**
     * @var User|null
     * 
     * @ORM\ManyToOne(targetEntity="User", inversedBy="commandes")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     */
    private $user;

    // Getter et setter pour la relation ManyToOne avec User
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getIdCmd(): ?int
    {
        return $this->idCmd;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(?int $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdProjet(): ?int
    {
        return $this->idProjet;
    }

    public function setIdProjet(?int $idProjet): self
    {
        $this->idProjet = $idProjet;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMtTotal(): ?string
    {
        return $this->mtTotal;
    }

    public function setMtTotal(?string $mtTotal): self
    {
        $this->mtTotal = $mtTotal;

        return $this;
    }

    public function getDateLivraisonEstimee(): ?\DateTimeInterface
    {
        return $this->dateLivraisonEstimee;
    }

    public function setDateLivraisonEstimee(?\DateTimeInterface $dateLivraisonEstimee): self
    {
        $this->dateLivraisonEstimee = $dateLivraisonEstimee;

        return $this;
    }

    public function getCodePromo(): ?int
    {
        return $this->codePromo;
    }

    public function setCodePromo(?int $codePromo): self
    {
        $this->codePromo = $codePromo;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getFraisLiv(): ?float
    {
        return $this->fraisLiv;
    }

    public function setFraisLiv(?float $fraisLiv): self
    {
        $this->fraisLiv = $fraisLiv;

        return $this;
    }

    public function getObject()
    {
        return [
            "date" => $this->getDate()->format('Y-m-d'), // Formatage de la date
            "mtTotal" => $this->getMtTotal(),
            "dateLivraisonEstimee" => $this->getDateLivraisonEstimee()->format('Y-m-d'), // Formatage de la date de livraison estimée
            "codePromo" => $this->getCodePromo(),
            "status" => $this->getStatus(),
            "prix" => $this->getPrix(),
            "fraisLiv" => $this->getFraisLiv(),
        ];
    }
}
