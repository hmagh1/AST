<?php
namespace App\Repository;

use App\Entity\AdministrateurUCAC;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdministrateurUCAC>
 *
 * @method AdministrateurUCAC|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdministrateurUCAC|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdministrateurUCAC[]    findAll()
 * @method AdministrateurUCAC[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdministrateurUCACRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdministrateurUCAC::class);
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
