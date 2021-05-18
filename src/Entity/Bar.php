<?php

namespace App\Entity;

use App\Repository\BarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BarRepository::class)
 */
class Bar
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
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Categoria::class, mappedBy="bar")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Producte::class, mappedBy="bar")
     */
    private $productes;

    /**
     * @ORM\OneToMany(targetEntity=Comanda::class, mappedBy="bar")
     */
    private $comandes;

    /**
     * @ORM\OneToMany(targetEntity=Taula::class, mappedBy="bar")
     */
    private $taules;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contrasenya;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->productes = new ArrayCollection();
        $this->comandes = new ArrayCollection();
        $this->taules = new ArrayCollection();
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

    /**
     * @return Collection|Categoria[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categoria $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setBar($this);
        }

        return $this;
    }

    public function removeCategory(Categoria $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getBar() === $this) {
                $category->setBar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Producte[]
     */
    public function getProductes(): Collection
    {
        return $this->productes;
    }

    public function addProducte(Producte $producte): self
    {
        if (!$this->productes->contains($producte)) {
            $this->productes[] = $producte;
            $producte->setBar($this);
        }

        return $this;
    }

    public function removeProducte(Producte $producte): self
    {
        if ($this->productes->removeElement($producte)) {
            // set the owning side to null (unless already changed)
            if ($producte->getBar() === $this) {
                $producte->setBar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comanda[]
     */
    public function getComandes(): Collection
    {
        return $this->comandes;
    }

    public function addComande(Comanda $comande): self
    {
        if (!$this->comandes->contains($comande)) {
            $this->comandes[] = $comande;
            $comande->setBar($this);
        }

        return $this;
    }

    public function removeComande(Comanda $comande): self
    {
        if ($this->comandes->removeElement($comande)) {
            // set the owning side to null (unless already changed)
            if ($comande->getBar() === $this) {
                $comande->setBar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Taula[]
     */
    public function getTaules(): Collection
    {
        return $this->taules;
    }

    public function addTaule(Taula $taule): self
    {
        if (!$this->taules->contains($taule)) {
            $this->taules[] = $taule;
            $taule->setBar($this);
        }

        return $this;
    }

    public function removeTaule(Taula $taule): self
    {
        if ($this->taules->removeElement($taule)) {
            // set the owning side to null (unless already changed)
            if ($taule->getBar() === $this) {
                $taule->setBar(null);
            }
        }

        return $this;
    }

    public function getContrasenya(): ?string
    {
        return $this->contrasenya;
    }

    public function setContrasenya(string $contrasenya): self
    {
        $this->contrasenya = $contrasenya;

        return $this;
    }
}
