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
     * @var int|null
     *
     * @ORM\Column(name="id_user", type="integer", nullable=true)
     * @Assert\NotBlank(message="id_user ne peut pas être vide.")
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
     * @Assert\NotBlank(message="date ne peut pas être vide.")
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
     * @Assert\NotBlank(message="date ne peut pas être vide.")
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
     * @Assert\NotBlank(message="Les frais de livraison ne peuvent pas être vides.")
     */
    private $fraisLiv;

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
}
