<?php

namespace App\Entity;

use App\Repository\TaulaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaulaRepository::class)
 */
class Taula
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
    private $identificador;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ocupada;

    /**
     * @ORM\OneToMany(targetEntity=Comanda::class, mappedBy="taula")
     */
    private $comandas;

    /**
     * @ORM\ManyToOne(targetEntity=Bar::class, inversedBy="taules")
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

    public function getIdentificador(): ?string
    {
        return $this->identificador;
    }

    public function setIdentificador(string $identificador): self
    {
        $this->identificador = $identificador;

        return $this;
    }

    public function getOcupada(): ?bool
    {
        return $this->ocupada;
    }

    public function setOcupada(bool $ocupada): self
    {
        $this->ocupada = $ocupada;

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
            $comanda->setTaula($this);
        }

        return $this;
    }

    public function removeComanda(Comanda $comanda): self
    {
        if ($this->comandas->removeElement($comanda)) {
            // set the owning side to null (unless already changed)
            if ($comanda->getTaula() === $this) {
                $comanda->setTaula(null);
            }
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
