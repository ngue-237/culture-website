<?php

namespace App\Entity;

use App\Repository\CmntRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CmntRepository::class)
 */
class Cmnt
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cnt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCnt(): ?string
    {
        return $this->cnt;
    }

    public function setCnt(string $cnt): self
    {
        $this->cnt = $cnt;

        return $this;
    }
}
