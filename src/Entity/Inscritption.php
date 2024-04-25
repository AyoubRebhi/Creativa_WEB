<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inscritption
 *
 * @ORM\Table(name="inscritption", indexes={@ORM\Index(name="forgenKey", columns={"formation_id"})})
 * @ORM\Entity
 */
class Inscritption
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_inscrit", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idInscrit;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_user", type="integer", nullable=true)
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNow", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $datenow = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     *
     * @ORM\Column(name="formation_id", type="integer", nullable=false)
     */
    private $formationId;


}
