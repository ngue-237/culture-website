<?php

namespace App\Entity;

use App\Repository\ProductCartRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductCartRepository::class)
 */
class ProductCart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Produits::class, cascade={"all"})
     * @ORM\JoinColumn(name="idProduit",referencedColumnName="id",nullable=false)
     * @ORM\Column(type="integer")
     */
    private $idProduit;

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class)
     * @ORM\Column(type="integer")
     */
    private $idCommande;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Length (min=2,
     *     max=10,
     *     minMessage="la quantité ne peut pas être inférieur à 2",
     *     maxMessage="la quanntité ne peut pas être supérieur à 10")
     */
    private $quantite;

    function __construct($idOrder,$idProduct,$quantity){
        /*$this->idProduit = $idProduct;
        $this->idCommande=$idOrder;
        $this->quantite = $quantity;*/
        $this->setIdCommande($idOrder);
        $this->setIdProduit($idProduct);
        $this->setQuantite($quantity);
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProduit()
    {
        return $this->idProduit;
    }

    public function setIdProduit(int $idProduit): self
    {
        $this->idProduit = $idProduit;

        return $this;
    }

    public function getIdCommande()
    {
        return $this->idCommande;
    }

    public function setIdCommande(int $idCommande): self
    {
        $this->idCommande = $idCommande;

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
}
