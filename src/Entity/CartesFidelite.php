<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CartesFidelite
 *
 * @ORM\Table(name="cartes_fidelite", uniqueConstraints={@ORM\UniqueConstraint(name="id_user", columns={"id_user"})})
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
     * @ORM\Column(name="id_user", type="integer", nullable=true)
     */
    private $idUser;

    /**
     * @var int|null
     *
     * @ORM\Column(name="points", type="integer", nullable=true)
     */
    private $points;


}
