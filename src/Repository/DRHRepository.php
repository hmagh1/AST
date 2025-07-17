<?php
namespace App\Repository;

use App\Entity\DRH;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DRH>
 *
 * @method DRH|null find($id, $lockMode = null, $lockVersion = null)
 * @method DRH|null findOneBy(array $criteria, array $orderBy = null)
 * @method DRH[]    findAll()
 * @method DRH[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DRHRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DRH::class);
    }

    // Exemple de méthode personnalisée
    public function findLatest(int $limit = 5): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
