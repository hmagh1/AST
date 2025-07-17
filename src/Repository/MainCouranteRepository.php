<?php
namespace App\Repository;

use App\Entity\MainCourante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MainCourante>
 *
 * @method MainCourante|null find($id, $lockMode = null, $lockVersion = null)
 * @method MainCourante|null findOneBy(array $criteria, array $orderBy = null)
 * @method MainCourante[]    findAll()
 * @method MainCourante[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MainCouranteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MainCourante::class);
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
