<?php

namespace App\Repository;

use App\Entity\Game;
use App\Enum\GameType;
use App\Trait\EmFlushTrait;
use App\Trait\EmPurgeTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    use EmFlushTrait;
    use EmPurgeTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function save(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param GameType[] $gameType
     */
    public function removeGamesByType(array $gameType): void
    {
        $emptyRsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $query = $this->getEntityManager()->createNativeQuery('DELETE FROM game WHERE game_type IN (?)', $emptyRsm);
        $query->setParameter(1, $gameType);
        $query->execute();
    }
}
