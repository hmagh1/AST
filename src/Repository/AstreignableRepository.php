<?php
namespace App\Repository;

use App\Entity\Astreignable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Astreignable>
 *
 * @method Astreignable|null find($id, $lockMode = null, $lockVersion = null)
 * @method Astreignable|null findOneBy(array $criteria, array $orderBy = null)
 * @method Astreignable[]    findAll()
 * @method Astreignable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AstreignableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Astreignable::class);
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
