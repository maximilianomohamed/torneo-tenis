<?php

namespace App\Entity;

use App\Repository\JugadorMasculinoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JugadorMasculinoRepository::class)]
class JugadorMasculino extends Jugador
{
    #[ORM\Column]
    private ?int $fuerza = null;

    #[ORM\Column]
    private ?int $velocidad = null;

    public function getFuerza(): ?int
    {
        return $this->fuerza;
    }

    public function setFuerza(int $fuerza): static
    {
        $this->fuerza = $fuerza;

        return $this;
    }

    public function getVelocidad(): ?int
    {
        return $this->velocidad;
    }

    public function setVelocidad(int $velocidad): static
    {
        $this->velocidad = $velocidad;

        return $this;
    }

    public function calcularHabilidad(): int
    {
        return $this->getHabilidad() + $this->getFuerza() + $this->getVelocidad();
    }
}

