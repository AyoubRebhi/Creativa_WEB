<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Livraison
 *
 * @ORM\Table(name="livraison", indexes={@ORM\Index(name="id_user", columns={"id_user"}), @ORM\Index(name="id_cmd", columns={"id_cmd"})})
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


}
