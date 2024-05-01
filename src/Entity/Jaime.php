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
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $date = 'CURRENT_TIMESTAMP';


}
