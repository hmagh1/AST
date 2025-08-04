<?php

namespace App\Repository;

use App\Entity\PlanningAstreinte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlanningAstreinte>
 *
 * @method PlanningAstreinte|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanningAstreinte|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanningAstreinte[]    findAll()
 * @method PlanningAstreinte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanningAstreinteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanningAstreinte::class);
    }

    public function findLatest(int $limit = 5): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByStatut(string $statut): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.statut = :statut')
            ->setParameter('statut', $statut)
            ->getQuery()
            ->getResult();
    }

    public function findActive(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.statut = :statut')
            ->setParameter('statut', 'active') // adapte la valeur à ton cas métier
            ->getQuery()
            ->getResult();
    }
}
