<?php

namespace App\Entity;

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


}
