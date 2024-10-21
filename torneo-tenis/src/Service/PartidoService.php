<?php

namespace App\Service;

use App\Entity\Partido;
use App\Entity\Jugador;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Clase para gestionar la lógica de los partidos entre jugadores.
 */
class PartidoService
{
    private EntityManagerInterface $entityManager;

    /**
     * PartidoService constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Simula un partido entre dos jugadores
     *
     * @param Jugador $jugador1
     * @param Jugador $jugador2
     * @return Jugador El jugador que ganó el partido
     */
    public function simularPartido(Jugador $jugador1, Jugador $jugador2): Jugador
    {
        // agrego un parámetro de suerte, número aleatorio para influir en el resultado
        $suerte1 = rand(0, 10);
        $suerte2 = rand(0, 10);

        // si son iguales, vuelve a generar otro número aleatorio para evitar el empate
        while ($suerte1 == $suerte2) {
            $suerte2 = rand(0, 10);
        }

        // 70 % Habilidad + 30% suerte
        $habilidad1 = ($jugador1->calcularHabilidad() * 0.7) + ($suerte1 * 0.3);
        $habilidad2 = ($jugador2->calcularHabilidad() * 0.7) + ($suerte2 * 0.3);

        // Determina el ganador
        $ganador = $habilidad1 > $habilidad2 ? $jugador1 : $jugador2;

        // persisto el resultado en la base de datos
        $partido = new Partido();
        $partido->setJugador1($jugador1);
        $partido->setJugador2($jugador2);
        $partido->setGanador($ganador);

        // persisto el objeto Partido
        $this->entityManager->persist($partido);
        $this->entityManager->flush();

        return $ganador;
    }
}
