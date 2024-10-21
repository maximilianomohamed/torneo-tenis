<?php
namespace App\Controller;

use App\Entity\JugadorMasculino;
use App\Entity\JugadorFemenino;
use App\Service\TorneoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Annotations as OA;

class ApiController extends AbstractController
{
    private TorneoService $torneoService;
    private EntityManagerInterface $entityManager;

    public function __construct(TorneoService $torneoService, EntityManagerInterface $entityManager)
    {
        $this->torneoService = $torneoService;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/torneos/hombres/jugar", name="api_torneos_hombres_jugar", methods={"POST"})
     * @OA\Post(
    *     path="/api/torneos/hombres/jugar",
    *     summary="Jugar torneo de hombres",
    *     description="Este endpoint permite iniciar un torneo de hombres.",
    *     @OA\Response(
    *         response=200,
    *         description="Respuesta exitosa",
    *     ),
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"jugador_id"},
    *             @OA\Property(property="jugador_id", type="integer", example=1),
    *         )
    *     )
    * )
    */
    public function jugarTorneoHombres(Request $request): JsonResponse
    {
        return $this->jugarTorneo($request, 'Masculino', JugadorMasculino::class);
    }

    /**
     * @Route("/api/torneos/mujeres/jugar", name="api_torneos_mujeres_jugar", methods={"POST"})
     */
    public function jugarTorneoMujeres(Request $request): JsonResponse
    {
        return $this->jugarTorneo($request, 'Femenino', JugadorFemenino::class);
    }

     /**
     * @Route("/api/torneos/resultados", name="api_torneos_consultar_resultados", methods={"GET"})
     */
    public function consultarResultados(Request $request): JsonResponse
    {
        $fecha = $request->query->get('fecha');
        $genero = $request->query->get('genero');
        $nombre = $request->query->get('nombre');
        // valido que la fecha sea genero Datetime y el genero sea 'Masculino' o 'Femenino'
        if ($fecha !== null && \DateTime::createFromFormat('Y-m-d', $fecha) === false) {
            return new JsonResponse(['error' => 'La fecha debe tener el formato Y-m-d.'], Response::HTTP_BAD_REQUEST);
        }

        if ($genero !== null && $genero !== 'Masculino' && $genero !== 'Femenino') {
            return new JsonResponse(['error' => 'El genero debe ser "Masculino" o "Femenino".'], Response::HTTP_BAD_REQUEST);
        }
        $resultados = $this->torneoService->consultarResultados($fecha, $genero, $nombre);

        if (empty($resultados)) {
            return new JsonResponse([
                'message' => 'No se encontraron torneos que respondan a los filtros proporcionados.',
                'filtros' => [
                    'fecha' => $fecha,
                    'genero' => $genero,
                    'nombre' => $nombre,
                ]
            ], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($resultados);
    }

    //funcion privada para jugar torneo, utiliza la logica del servicio para simular el torneo
    private function jugarTorneo(Request $request, string $tipo, string $jugadorClass): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $jugadores = $data['jugadores'] ?? [];
        $nombre = $data['nombreTorneo'] ?? '';

        $validacionError = $this->validarDatos($jugadores, $nombre, $tipo);
        if ($validacionError) {
            return $validacionError;
        }

        $jugadoresEntidad = [];
        foreach ($jugadores as $jugadorData) {
            $jugadorEntidad = $this->crearJugador($jugadorData, $jugadorClass);
            if (!$jugadorEntidad) {
                return new JsonResponse(['error' => 'Datos de jugador inválidos.'], response::HTTP_BAD_REQUEST);
            }
            $jugadoresEntidad[] = $jugadorEntidad;
            $this->entityManager->persist($jugadorEntidad);
        }

        $this->entityManager->flush();

        $ganador = $this->torneoService->simularTorneo($jugadoresEntidad, $nombre, $tipo);
        return new JsonResponse(['ganador' => $ganador->getNombre()]);
    }

    //funcion privada para validar los datos que vienen por POST
    private function validarDatos(array $jugadores, string $nombre, string $tipo): ?JsonResponse
    {
        if (empty($jugadores)) {
            return new JsonResponse(['error' => 'Se requieren jugadores.'], response::HTTP_BAD_REQUEST);
        }
        if (count($jugadores) % 2 !== 0) {
            return new JsonResponse(['error' => 'Se requiere un número par de jugadores.'], response::HTTP_BAD_REQUEST);
        }
        if (empty($nombre)) {
            return new JsonResponse(['error' => 'Se requiere el nombre del torneo.'], response::HTTP_BAD_REQUEST);
        }
        return null;
    }

    // funcion privada crea el jugador dependiendo el tipo

    private function crearJugador(array $jugadorData, string $jugadorClass)
    {
        if (empty($jugadorData['nombre']) || empty($jugadorData['habilidad'])) {
            return null;
        }

        if ($jugadorClass === JugadorMasculino::class) {
            $jugadorEntidad = new JugadorMasculino();
            $jugadorEntidad->setFuerza($jugadorData['fuerza'] ?? null);
            $jugadorEntidad->setVelocidad($jugadorData['velocidad'] ?? null);
        } else {
            $jugadorEntidad = new JugadorFemenino();
            $jugadorEntidad->setTiempoReaccion($jugadorData['tiempoReaccion'] ?? null);
        }

        $jugadorEntidad->setNombre($jugadorData['nombre']);
        $jugadorEntidad->setHabilidad($jugadorData['habilidad']);
        return $jugadorEntidad;
    }

}
