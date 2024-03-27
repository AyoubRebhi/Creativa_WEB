<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Livraison
 *
 * @ORM\Table(name="livraison", indexes={@ORM\Index(name="id_cmd", columns={"id_cmd"}), @ORM\Index(name="id_user", columns={"id_user"})})
 * @ORM\Entity
 */
class Livraison
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_liv", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLiv;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_cmd", type="integer", nullable=true)
     */
    private $idCmd;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_user", type="integer", nullable=true)
     */
    private $idUser;

    /**
     * @var string|null
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var string|null
     *
     * @ORM\Column(name="frais_liv", type="string", length=255, nullable=true)
     */
    private $fraisLiv;

    /**
     * @var string|null
     *
     * @ORM\Column(name="moyen_livraison", type="string", length=255, nullable=true)
     */
    private $moyenLivraison;

    public function getIdLiv(): ?int
    {
        return $this->idLiv;
    }

    public function getIdCmd(): ?int
    {
        return $this->idCmd;
    }

    public function setIdCmd(?int $idCmd): static
    {
        $this->idCmd = $idCmd;

        return $this;
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getFraisLiv(): ?string
    {
        return $this->fraisLiv;
    }

    public function setFraisLiv(?string $fraisLiv): static
    {
        $this->fraisLiv = $fraisLiv;

        return $this;
    }

    public function getMoyenLivraison(): ?string
    {
        return $this->moyenLivraison;
    }

    public function setMoyenLivraison(?string $moyenLivraison): static
    {
        $this->moyenLivraison = $moyenLivraison;

        return $this;
    }


}
