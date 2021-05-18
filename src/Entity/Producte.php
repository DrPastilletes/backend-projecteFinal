<?php

namespace App\Entity;

use App\Repository\ProducteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProducteRepository::class)
 */
class Producte
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
     * @ORM\Column(type="float")
     */
    private $preu;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disponible;

    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class, inversedBy="productes")
     */
    private $categoria;

    /**
     * @ORM\ManyToMany(targetEntity=Comanda::class, mappedBy="productes")
     */
    private $comandas;

    /**
     * @ORM\ManyToOne(targetEntity=Bar::class, inversedBy="productes")
     */
    private $bar;

    public function __construct()
    {
        $this->comandas = new ArrayCollection();
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

    public function getPreu(): ?float
    {
        return $this->preu;
    }

    public function setPreu(float $preu): self
    {
        $this->preu = $preu;

        return $this;
    }

    public function getDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }

    public function getCategoria(): ?categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * @return Collection|Comanda[]
     */
    public function getComandas(): Collection
    {
        return $this->comandas;
    }

    public function addComanda(Comanda $comanda): self
    {
        if (!$this->comandas->contains($comanda)) {
            $this->comandas[] = $comanda;
            $comanda->addProducte($this);
        }

        return $this;
    }

    public function removeComanda(Comanda $comanda): self
    {
        if ($this->comandas->removeElement($comanda)) {
            $comanda->removeProducte($this);
        }

        return $this;
    }

    public function getBar(): ?Bar
    {
        return $this->bar;
    }

    public function setBar(?Bar $bar): self
    {
        $this->bar = $bar;

        return $this;
    }
}
