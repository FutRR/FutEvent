<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function searchByKeyword(string $keywords): array
    {
        $qb = $this->createQueryBuilder('e')
            ->distinct()
            ->leftJoin('e.category', 'c')
            ->leftJoin('e.creator', 'creator');

        $words = array_filter(explode(' ', trim($keywords)));

        foreach ($words as $index => $word) {
            $qb->andWhere(
                $qb->expr()->orX(
                    "e.title LIKE :word$index",
                    "e.description LIKE :word$index",
                    "c.name LIKE :word$index",
                    "creator.username LIKE :word$index"
                )
            )
                ->setParameter("word$index", "%$word%");
        }

        $qb->orderBy('e.datetime_start', 'DESC');
        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Event[] Returns an array of Event objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Event
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
