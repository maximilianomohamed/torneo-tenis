<?php

namespace App\Service;

use App\Entity\Torneo;
use App\Entity\Jugador;
use App\Repository\TorneoRepository;
use Doctrine\ORM\EntityManagerInterface;

class TorneoService
{
    private EntityManagerInterface $entityManager;
    private TorneoRepository $torneoRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->torneoRepository = $this->entityManager->getRepository(Torneo::class);
    }

    /**
     * Simula un torneo con un conjunto de jugadores
     *
     * @param Jugador[] $jugadores
     * @param string $nombreTorneo
     * @return Jugador
     * @throws \Exception
     */
    public function simularTorneo(array $jugadores, string $nombreTorneo, string $generoTorneo): Jugador
    {
        // Guardar el torneo y su ganador
        $torneo = new Torneo();
        $torneo->setNombre($nombreTorneo);
        $torneo->setFechaTorneo(new \DateTime());
        $torneo->setGenero($generoTorneo);

        //juegan hasta que solo quede un ganador
        while (count($jugadores) > 1) {
            $ganadores = [];
            for ($i = 0; $i < count($jugadores); $i += 2) {
                $jugador1 = $jugadores[$i];
                $jugador2 = $jugadores[$i + 1];

                //agrego los jugadores al torneo
                $torneo->addJugador($jugador1);
                $torneo->addJugador($jugador2);

                //simulo partido
                $partidoService = new PartidoService($this->entityManager);
                $ganador = $partidoService->simularPartido($jugador1, $jugador2);

                //agrego ganador a la siguiente ronda
                $ganadores[] = $ganador;
            }
            //actualizo la lista de jugadores
            $jugadores = $ganadores;
        }

        $torneo->setGanador($jugadores[0]); 

        $this->entityManager->persist($torneo);
        $this->entityManager->flush();

        return $jugadores[0];
    }

    
    /**
     * @param array $jugadores lista de jugadores que participan en el torneo
     * @param string $nombreTorneo nombre del torneo
     * @param string $generoTorneo genero del torneo, puede ser "Masculino" o "Femenino"
     * @return Jugador el jugador que gano el torneo
     * @throws \Exception si hay un error al guardar el torneo
     */
    public function consultarResultados(?string $fecha, ?string $genero, ?string $nombre): array
    {
        $respuesta = $this->torneoRepository->findByCriteria($fecha, $genero, $nombre);
        $resultados = [];
        foreach ($respuesta as $torneo) {
            $resultados[] = [
                'fecha' => $torneo->getFechaTorneo()->format('Y-m-d'),
                'genero' => $torneo->getGenero(),
                'nombreTorneo' => $torneo->getNombre(),
                'ganador' => $torneo->getGanador()->getNombre(),
                'habilidad' => $torneo->getGanador()->getHabilidad(),
            ];
        }
        return $resultados;
    }
}
