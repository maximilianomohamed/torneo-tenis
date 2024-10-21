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

/**
 * @OA\Info(
 *     title="Torneo-tenis by Maxi Mohamed",
 *     version="1.0.0",
 *     description="API documentation for the Torneo-tenis project"
 * )
 */
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
     * @Route("/api/torneos/hombres/jugar", methods={"POST"})
     * @OA\Post(
     *     path="/api/torneos/hombres/jugar",
     *     summary="Jugar torneo masculino",
     *     @OA\RequestBody(
     *         description="Torneo masculino",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="nombreTorneo", type="string", example="Torneo de Hombres"),
     *             @OA\Property(property="jugadores", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="nombre", type="string", example="Jugador 1"),
     *                 @OA\Property(property="habilidad", type="integer", example=80, minimum=0, maximum=100),
     *                 @OA\Property(property="fuerza", type="integer", example=70, minimum=0, maximum=100),
     *                 @OA\Property(property="velocidad", type="integer", example=75, minimum=0, maximum=100),
     *             )),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="ganador", type="string", example="Jugador 1")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Se requieren jugadores, un número par de jugadores, o el nombre del torneo.")
     *         )
     *     )
     * )
     */
    public function jugarTorneoHombres(Request $request): JsonResponse
    {
        return $this->jugarTorneo($request, 'Masculino', JugadorMasculino::class);
    }

    /**
     * @Route("/api/torneos/mujeres/jugar", methods={"POST"})
     * @OA\Post(
     *     path="/api/torneos/mujeres/jugar",
     *     summary="Jugar torneo femenino",
     *     @OA\RequestBody(
     *         description="Torneo femenino",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="nombreTorneo", type="string", example="Torneo de Mujeres"),
     *             @OA\Property(property="jugadores", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="nombre", type="string", example="Jugadora 1"),
     *                 @OA\Property(property="habilidad", type="integer", example=80, minimum=0, maximum=100),
     *                 @OA\Property(property="tiempoReaccion", type="integer", example=0.5, minimum=0),
     *             )),
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Se requieren jugadoras, un número par de jugadoras, o el nombre del torneo.")
     *         )
     *     )
     * )
     */

    public function jugarTorneoMujeres(Request $request): JsonResponse
    {
        return $this->jugarTorneo($request, 'Femenino', JugadorFemenino::class);
    }

    /**
     * @Route("/api/torneos/resultados", name="api_torneos_consultar_resultados", methods={"GET"})
     * @OA\Get(
     *     path="/api/torneos/resultados",
     *     summary="Consultar resultados de torneos",
     *     description="Este endpoint permite consultar los resultados de los torneos de tenis según filtros opcionales.",
     *     @OA\Parameter(
     *         name="fecha",
     *         in="query",
     *         description="Fecha del torneo en formato Y-m-d (opcional).",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="genero",
     *         in="query",
     *         description="Género del torneo (Masculino o Femenino) (opcional).",
     *         required=false,
     *         @OA\Schema(type="string", enum={"Masculino", "Femenino"})
     *     ),
     *     @OA\Parameter(
     *         name="nombre",
     *         in="query",
     *         description="Nombre del torneo (opcional).",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Resultados encontrados",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="nombreTorneo", type="string", example="Torneo de Hombres"),
     *                 @OA\Property(property="resultado", type="string", example="Ganador: Jugador 1")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="No se encontraron resultados",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No se encontraron torneos que respondan a los filtros proporcionados."),
     *             @OA\Property(property="filtros", type="object",
     *                 @OA\Property(property="fecha", type="string", example="2024-10-21"),
     *                 @OA\Property(property="genero", type="string", example="Masculino"),
     *                 @OA\Property(property="nombre", type="string", example="Torneo de Hombres")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="La fecha debe tener el formato Y-m-d, o el género debe ser 'Masculino' o 'Femenino'.")
     *         )
     *     )
     * )
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
