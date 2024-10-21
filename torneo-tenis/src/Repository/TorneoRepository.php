<?php

namespace App\Repository;

use App\Entity\Torneo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Torneo>
 */
class TorneoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Torneo::class);
    }

    public function findByCriteria(?string $fecha, ?string $genero, ?string $nombre): array
    {
        $qb = $this->createQueryBuilder('t');

        if ($fecha) {
            $qb->andWhere('t.fechaTorneo = :fecha')
               ->setParameter('fecha', $fecha);
        }

        if ($genero) {
            $qb->andWhere('t.genero = :genero')
               ->setParameter('genero', $genero);
        }

        if ($nombre) {
            $qb->andWhere('t.nombre LIKE :nombre')
               ->setParameter('nombre', '%' . $nombre . '%');
        }

        return $qb->getQuery()->getResult();
    }
}
