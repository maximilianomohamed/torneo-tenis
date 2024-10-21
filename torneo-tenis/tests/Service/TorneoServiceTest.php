<?php

namespace App\Tests\Service;

use App\Entity\Torneo;
use App\Entity\Jugador;
use App\Entity\JugadorMasculino;
use App\Entity\JugadorFemenino;
use App\Service\TorneoService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class TorneoServiceTest extends TestCase
{
    private TorneoService $torneoService;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->torneoService = new TorneoService($this->entityManager);
    }

    public function testSimularTorneoMasculino()
    {
        //Creacion de jugadores masculinos
        $jugador1 = new JugadorMasculino();
        $jugador1->setNombre("Jugador 1");
        $jugador1->setHabilidad(100);
        $jugador1->setFuerza(80);
        $jugador1->setVelocidad(65);

        $jugador2 = new JugadorMasculino();
        $jugador2->setNombre("Jugador 2");
        $jugador2->setHabilidad(90);
        $jugador2->setFuerza(85);
        $jugador2->setVelocidad(75);

        $jugador3 = new JugadorMasculino();
        $jugador3->setNombre("Jugador 3");
        $jugador3->setHabilidad(60);
        $jugador3->setFuerza(80);
        $jugador3->setVelocidad(50);

        $jugador4 = new JugadorMasculino();
        $jugador4->setNombre("Jugador 4");
        $jugador4->setHabilidad(88);
        $jugador4->setFuerza(85);
        $jugador4->setVelocidad(78);

        //simular torneo
        $ganador = $this->torneoService->simularTorneo([$jugador1, $jugador2, $jugador3, $jugador4], 'Torneo Masculino Prueba', 'Masculino');

        // respuesta test
        $this->assertInstanceOf(Jugador::class, $ganador);
        $this->assertContains($ganador, [$jugador1, $jugador2, $jugador3, $jugador4]);
    }

    public function testSimularTorneoFemenino()
    {
        //Creacion de jugadoras femeninas
        $jugadora1 = new JugadorFemenino();
        $jugadora1->setNombre("Jugadora 1");
        $jugadora1->setHabilidad(95);
        $jugadora1->setTiempoReaccion(85);

        $jugadora2 = new JugadorFemenino();
        $jugadora2->setNombre("Jugadora 2");
        $jugadora2->setHabilidad(75);
        $jugadora2->setTiempoReaccion(70);

        $jugadora3 = new JugadorFemenino();
        $jugadora3->setNombre("Jugadora 3");
        $jugadora3->setHabilidad(90);
        $jugadora3->setTiempoReaccion(82);

        $jugadora4 = new JugadorFemenino();
        $jugadora4->setNombre("Jugadora 4");
        $jugadora4->setHabilidad(89);
        $jugadora4->setTiempoReaccion(78);

        //simular torneo
        $ganadora = $this->torneoService->simularTorneo([$jugadora1, $jugadora2, $jugadora3, $jugadora4], 'Torneo Femenino Prueba', 'Femenino');
        
        // respuesta test
        $this->assertInstanceOf(Jugador::class, $ganadora);
        $this->assertContains($ganadora, [$jugadora1, $jugadora2, $jugadora3, $jugadora4]);
    }
}
