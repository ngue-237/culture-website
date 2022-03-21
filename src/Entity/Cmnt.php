<?php

namespace App\Entity;

use App\Repository\CmntRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank(message="entrer un commentaire")
     * @ORM\Column(type="string", length=255)
     */
    private $cnt;

    /**
     * @Assert\Email(message = "The email  is not a valid email.")
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @Assert\NotBlank(message="entrer votre pseudo")
     * @ORM\Column(type="string", length=255)
     */
    private $nickname;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @Assert\NotBlank(message="!!!")
     * @ORM\Column(type="boolean")
     */
    private $rgpd;

    /**
     * @ORM\ManyToOne(targetEntity=Blog::class, inversedBy="cmnts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $blog;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getRgpd(): ?bool
    {
        return $this->rgpd;
    }

    public function setRgpd(bool $rgpd): self
    {
        $this->rgpd = $rgpd;

        return $this;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): self
    {
        $this->blog = $blog;

        return $this;
    }
}
