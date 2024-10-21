<?php

namespace App\Entity;

use App\Repository\JugadorFemeninoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JugadorFemeninoRepository::class)]
class JugadorFemenino extends Jugador
{
    #[ORM\Column]
    private ?int $tiempoReaccion = null;

    public function getTiempoReaccion(): ?int
    {
        return $this->tiempoReaccion;
    }

    public function setTiempoReaccion(int $tiempoReaccion): static
    {
        $this->tiempoReaccion = $tiempoReaccion;

        return $this;
    }

    public function calcularHabilidad(): int
    {
        return $this->getHabilidad() + $this->getTiempoReaccion();
    }
}

