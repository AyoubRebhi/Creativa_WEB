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
     * @var \DateTimeImmutable
     *
     * @ORM\Column(name="date", type="datetime_immutable", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $date;

    public function __construct()
    {
        $this->date = new \DateTimeImmutable();
    }

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

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }
}
