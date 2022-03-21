<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BlogRepository::class)
 */
class Blog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="entrer le titre de votre blog")

     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @Assert\NotBlank(message="entrer le contenu de votre blog")
     * @ORM\Column(type="string", length=400)
     */
    private $cnt;

    /**
     * @Assert\NotBlank(message="importer image pour votre blog")
     * @ORM\Column(type="string", length=255)

     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity=Cmnt::class, mappedBy="blog", orphanRemoval=true)
     */
    private $cmnts;

    public function __construct()
    {
        $this->cmnts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
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

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto( $photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection|Cmnt[]
     */
    public function getCmnts(): Collection
    {
        return $this->cmnts;
    }

    public function addCmnt(Cmnt $cmnt): self
    {
        if (!$this->cmnts->contains($cmnt)) {
            $this->cmnts[] = $cmnt;
            $cmnt->setBlog($this);
        }

        return $this;
    }

    public function removeCmnt(Cmnt $cmnt): self
    {
        if ($this->cmnts->removeElement($cmnt)) {
            // set the owning side to null (unless already changed)
            if ($cmnt->getBlog() === $this) {
                $cmnt->setBlog(null);
            }
        }

        return $this;
    }
}
