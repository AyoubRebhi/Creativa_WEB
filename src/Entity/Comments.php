<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comments
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity
 */
class Comments
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
     * @var int
     *
     * @ORM\Column(name="Post_id", type="integer", nullable=false)
     */
    private $postId;

    /**
     * @var int
     *
     * @ORM\Column(name="Editeur", type="integer", nullable=false)
     */
    private $editeur;

    /**
     * @var string
     *
     * @ORM\Column(name="Content", type="string", length=255, nullable=false)
     */
    private $content;

    /**
     * @var int
     *
     * @ORM\Column(name="Seen", type="integer", nullable=false)
     */
    private $seen;


}
