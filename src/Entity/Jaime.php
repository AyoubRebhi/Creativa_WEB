<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jaime
 *
 * @ORM\Table(name="jaime", indexes={@ORM\Index(name="fk_projet", columns={"id_projet"})})
 * @ORM\Entity
 */
class Jaime
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idUser;

    /**
     * @var int
     *
     * @ORM\Column(name="id_projet", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idProjet;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    private $date;

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdProjet(): ?int
    {
        return $this->idProjet;
    }

    public function setIdProjet(int $idProjet): self
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
}
