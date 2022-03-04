<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="la designation n'existe pas")
     */
    private $designation;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="prix est vide")
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="la quantite n'est pas valide")
     */
    private $quantite;


    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cathegorie;
    /**
     * @ORM\Column(type="string")
     */
    private $image;





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }


    public function getCathegorie(): ?Categorie
    {
        return $this->cathegorie;
    }

    public function setCathegorie(?Categorie $cathegorie): self
    {
        $this->cathegorie = $cathegorie;

        return $this;
    }
    public function getImage() {
        return $this->image;
    }


    public function setImage($image) {
        $this->image = $image;
    }



}
