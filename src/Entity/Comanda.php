<?php

namespace App\Entity;

use App\Repository\ComandaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComandaRepository::class)
 */
class Comanda
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Taula::class, inversedBy="comandas")
     */
    private $taula;

    /**
     * @ORM\ManyToMany(targetEntity=Producte::class, inversedBy="comandas")
     */
    private $productes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $acabat;

    /**
     * @ORM\Column(type="datetime")
     */
    private $horaAcabat;

    /**
     * @ORM\Column(type="float")
     */
    private $preuTotal;

    /**
     * @ORM\ManyToOne(targetEntity=Bar::class, inversedBy="comandes")
     */
    private $bar;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comentari;

    public function __construct()
    {
        $this->productes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaula(): ?Taula
    {
        return $this->taula;
    }

    public function setTaula(?Taula $taula): self
    {
        $this->taula = $taula;

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
        $this->productes[] = $producte;
        /*if (!$this->productes->contains($producte)) {

        }*/

        return $this;
    }

    public function removeProducte(Producte $producte): self
    {
        $this->productes->removeElement($producte);

        return $this;
    }

    public function getAcabat(): ?bool
    {
        return $this->acabat;
    }

    public function setAcabat(bool $acabat): self
    {
        $this->acabat = $acabat;

        return $this;
    }

    public function getHoraAcabat(): ?\DateTimeInterface
    {
        return $this->horaAcabat;
    }

    public function setHoraAcabat(\DateTimeInterface $horaAcabat): self
    {
        $this->horaAcabat = $horaAcabat;

        return $this;
    }

    public function getPreuTotal(): ?float
    {
        return $this->preuTotal;
    }

    public function setPreuTotal(float $preuTotal): self
    {
        $this->preuTotal = $preuTotal;

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

    public function getComentari(): ?string
    {
        return $this->comentari;
    }

    public function setComentari(?string $comentari): self
    {
        $this->comentari = $comentari;

        return $this;
    }
}
