<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Topic
 *
 * @ORM\Table(name="topic")
 * @ORM\Entity
 */
class Topic
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Catego_ID", type="string", length=255, nullable=false)
     */


    /**
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=255, nullable=false)
     */
    private $Nom;

    /**
     * @var string
     *
     * @ORM\Column(name="Subject", type="string", length=255, nullable=false)
     */
    private $Subject;

    /**
     * @var string
     *
     * @ORM\Column(name="Image", type="string", length=255, nullable=false)
     */
    private $Image;

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $nom): static
    {
        $this->Nom = $nom;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->Subject;
    }

    public function setSubject(string $subject): static
    {
        $this->Subject = $subject;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(string $image): static
    {
        $this->Image = $image;

        return $this;
    }
}
