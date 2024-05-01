<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity
 */
class Post
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
     * @ORM\Column(name="Editeur", type="string", length=255, nullable=false)
     */
    private $Editeur;

    /**
     * @var string
     *
     * @ORM\Column(name="Media", type="string", length=255, nullable=false)
     */
    private $Media;

    /**
     * @var string
     *
     * @ORM\Column(name="Titre", type="string", length=255, nullable=false)
     */
    private $Titre;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="string", length=255, nullable=false)
     */
    private $Description;

    /**
     * @var int
     *
     * @ORM\Column(name="Topic_id", type="integer", nullable=false)
     */
    private $topicId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEditeur(): ?string
    {
        return $this->Editeur;
    }

    public function setEditeur(string $editeur): static
    {
        $this->Editeur = $editeur;

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->Media;
    }

    public function setMedia(string $media): static
    {
        $this->Media = $media;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $titre): static
    {
        $this->Titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $description): static
    {
        $this->Description = $description;

        return $this;
    }

    public function getTopicId(): ?int
    {
        return $this->topicId;
    }

    public function setTopicId(int $topicId): static
    {
        $this->topicId = $topicId;

        return $this;
    }
}
