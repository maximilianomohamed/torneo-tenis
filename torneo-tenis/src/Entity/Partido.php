<?php

namespace App\Entity;

use App\Repository\PartidoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartidoRepository::class)]
class Partido
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Jugador::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Jugador $jugador1 = null;

    #[ORM\ManyToOne(targetEntity: Jugador::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Jugador $jugador2 = null;

    #[ORM\ManyToOne(targetEntity: Jugador::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Jugador $ganador = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJugador1(): ?Jugador
    {
        return $this->jugador1;
    }

    public function setJugador1(Jugador $jugador1): static
    {
        $this->jugador1 = $jugador1;

        return $this;
    }

    public function getJugador2(): ?Jugador
    {
        return $this->jugador2;
    }

    public function setJugador2(Jugador $jugador2): static
    {
        $this->jugador2 = $jugador2;

        return $this;
    }

    public function getGanador(): ?Jugador
    {
        return $this->ganador;
    }

    public function setGanador(Jugador $ganador): static
    {
        $this->ganador = $ganador;

        return $this;
    }    

}
