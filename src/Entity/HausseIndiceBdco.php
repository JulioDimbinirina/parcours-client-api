<?php

namespace App\Entity;

use App\Repository\HausseIndiceBdcoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HausseIndiceBdcoRepository::class)
 */
class HausseIndiceBdco
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
