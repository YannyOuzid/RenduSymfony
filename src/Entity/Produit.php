<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 */
class Produit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotNull
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotNull
     */
    private $qte;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotNull
     */
    private $prix;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Panier", mappedBy="produit")
     */
    private $produit;

    public function __construct()
    {
        $this->produit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getQte(): ?string
    {
        return $this->qte;
    }

    public function setQte(?string $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(?string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection|Panier[]
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addProduit(Panier $produit): self
    {
        if (!$this->produit->contains($produit)) {
            $this->produit[] = $produit;
            $produit->setProduit($this);
        }

        return $this;
    }

    public function removeProduit(Panier $produit): self
    {
        if ($this->produit->contains($produit)) {
            $this->produit->removeElement($produit);
            // set the owning side to null (unless already changed)
            if ($produit->getProduit() === $this) {
                $produit->setProduit(null);
            }
        }

        return $this;
    }

     /**
     * @ORM\PostRemove
     */
    public function supprphoto(){
        if(file_exists(__DIR__ . '/../../public/uploads/'. $this->photo)){
            unlink(__DIR__ . '/../../public/uploads/'. $this->photo);
        }
        return true;
    }

    public function __toString(){
        return $this->getNom();
    }
}
