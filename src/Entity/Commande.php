<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $prix;

    /**
     * @var float|null
     *
     * @ORM\Column(name="frais_liv", type="float", precision=10, scale=0, nullable=true)
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

    public function setIdUser(?int $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdProjet(): ?int
    {
        return $this->idProjet;
    }

    public function setIdProjet(?int $idProjet): static
    {
        $this->idProjet = $idProjet;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getMtTotal(): ?string
    {
        return $this->mtTotal;
    }

    public function setMtTotal(?string $mtTotal): static
    {
        $this->mtTotal = $mtTotal;

        return $this;
    }

    public function getDateLivraisonEstimee(): ?\DateTimeInterface
    {
        return $this->dateLivraisonEstimee;
    }

    public function setDateLivraisonEstimee(?\DateTimeInterface $dateLivraisonEstimee): static
    {
        $this->dateLivraisonEstimee = $dateLivraisonEstimee;

        return $this;
    }

    public function getCodePromo(): ?int
    {
        return $this->codePromo;
    }

    public function setCodePromo(?int $codePromo): static
    {
        $this->codePromo = $codePromo;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getFraisLiv(): ?float
    {
        return $this->fraisLiv;
    }

    public function setFraisLiv(?float $fraisLiv): static
    {
        $this->fraisLiv = $fraisLiv;

        return $this;
    }


}
