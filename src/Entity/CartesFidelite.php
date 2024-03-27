<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CartesFidelite
 *
 * @ORM\Table(name="cartes_fidelite", uniqueConstraints={@ORM\UniqueConstraint(name="id_user", columns={"id_user1"})})
 * @ORM\Entity
 */
class CartesFidelite
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_carte", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCarte;

    /**
     * @var int|null
     *
     * @ORM\Column(name="points", type="integer", nullable=true)
     */
    private $points;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user1", referencedColumnName="id_user")
     * })
     */
    private $idUser1;

    public function getIdCarte(): ?int
    {
        return $this->idCarte;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function getIdUser1(): ?User
    {
        return $this->idUser1;
    }

    public function setIdUser1(?User $idUser1): static
    {
        $this->idUser1 = $idUser1;

        return $this;
    }


}
