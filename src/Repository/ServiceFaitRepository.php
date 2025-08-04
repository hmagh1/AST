<?php

namespace App\Repository;

use App\Entity\ServiceFait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ServiceFait>
 *
 * @method ServiceFait|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceFait|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceFait[]    findAll()
 * @method ServiceFait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceFaitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceFait::class);
    }

    public function findLatest(int $limit = 5): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findValidated(): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.valide = :valide')
            ->setParameter('valide', true)
            ->getQuery()
            ->getResult();
    }

    public function findByAstreignableId(int $astreignableId): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.astreignable = :astreignable')
            ->setParameter('astreignable', $astreignableId)
            ->getQuery()
            ->getResult();
    }
}
