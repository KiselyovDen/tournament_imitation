<?php

namespace App\Repository;

use App\Entity\DivisionGameScore;
use App\Trait\EmFlushTrait;
use App\Trait\EmPurgeTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DivisionGameScore>
 *
 * @method DivisionGameScore|null find($id, $lockMode = null, $lockVersion = null)
 * @method DivisionGameScore|null findOneBy(array $criteria, array $orderBy = null)
 * @method DivisionGameScore[]    findAll()
 * @method DivisionGameScore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DivisionGameScoreRepository extends ServiceEntityRepository
{
    use EmFlushTrait;
    use EmPurgeTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DivisionGameScore::class);
    }

    public function save(DivisionGameScore $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DivisionGameScore $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
