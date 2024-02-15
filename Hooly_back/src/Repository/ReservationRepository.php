<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findAllReservationByWeek($date)
    {
        if (!$date instanceof \DateTime) {
            try {
                $date = new \DateTime($date);
            } catch (\Exception $e) {
                throw new \InvalidArgumentException("La date fournie est invalide.");
            }
        }
        $monday = (clone $date)->modify('monday this week')->setTime(0, 0);
        $sunday = (clone $monday)->modify('+6 days')->setTime(23, 59, 59);
        $query = $this->createQueryBuilder('r');
        $query->where('r.date >= :monday and r.date <= :sunday')->setParameter('monday',$monday)->setParameter('sunday',$sunday);
        return $query->getQuery()->getResult();
    }
}
