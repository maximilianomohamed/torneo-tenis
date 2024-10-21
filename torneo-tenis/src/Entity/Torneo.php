<?php

namespace App\Entity;

use App\Repository\TorneoRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: TorneoRepository::class)]
class Torneo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    private string $nombre; 

    #[ORM\OneToMany(targetEntity: Jugador::class, mappedBy: 'torneo', cascade: ['persist', 'remove'])]
    private Collection $jugadores;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $fechaTorneo;

    #[ORM\ManyToOne(targetEntity: Jugador::class)]
    private ?Jugador $ganador = null;

    #[ORM\Column(type: 'string')]
    private string $genero;

    public function __construct()
    {
        $this->jugadores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): string 
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static 
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection<int, Jugador>
     */
    public function getJugadores(): Collection
    {
        return $this->jugadores;
    }

    public function addJugador(Jugador $jugador): static
    {
        if (!$this->jugadores->contains($jugador)) {
            $this->jugadores->add($jugador);
        }

        return $this;
    }

    public function removeJugador(Jugador $jugador): static
    {
        $this->jugadores->removeElement($jugador);
        return $this;
    }

    public function getFechaTorneo(): \DateTime
    {
        return $this->fechaTorneo;
    }

    public function setFechaTorneo(\DateTime $fechaTorneo): static
    {
        $this->fechaTorneo = $fechaTorneo;

        return $this;
    }

    public function getGanador(): ?Jugador
    {
        return $this->ganador;
    }

    public function setGanador(?Jugador $ganador): static
    {
        $this->ganador = $ganador;

        return $this;
    }

    public function getGenero(): string
    {
        return $this->genero;
    }

    public function setGenero(string $genero): static
    {
        $this->genero = $genero;

        return $this;
    }
}
