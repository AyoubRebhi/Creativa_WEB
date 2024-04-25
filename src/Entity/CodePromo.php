<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CodePromo
 *
 * @ORM\Table(name="code_promo")
 * @ORM\Entity
 */
class CodePromo
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="code_promo", type="integer", nullable=false)
     */
    private $codePromo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pourcentage", type="string", length=255, nullable=true)
     */
    private $pourcentage;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_expiration", type="date", nullable=true)
     */
    private $dateExpiration;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;


}
