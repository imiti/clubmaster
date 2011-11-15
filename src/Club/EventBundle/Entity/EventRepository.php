<?php

namespace Club\EventBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends EntityRepository
{
  public function getByDate(\DateTime $date)
  {
    $c = new \DateTime($date->format('Y-m-d'));;

    $start = $c->format('Y-m-d');
    $c->modify('+1 month');
    $c->modify('-1 day');
    $stop = $c->format('Y-m-d');

    return $this->_em->createQueryBuilder()
      ->select('e')
      ->from('ClubEventBundle:Event','e')
      ->where('e.start_date >= :start_date')
      ->andWhere('e.stop_date <= :stop_date')
      ->setParameter('start_date', $start)
      ->setParameter('stop_date', $stop)
      ->getQuery()
      ->getResult();
  }

  public function getComing()
  {
    return $this->_em->createQueryBuilder()
      ->select('e')
      ->from('ClubEventBundle:Event','e')
      ->where('e.start_date > :start')
      ->setParameter('start', new \DateTime())
      ->getQuery()
      ->getResult();
  }

  public function getAllBetween(\DateTime $start, \DateTime $end=null)
  {
    $qb = $this->_em->createQueryBuilder()
      ->select('e')
      ->from('ClubEventBundle:Event','e')
      ->where('e.start_date >= :start_date')
      ->setParameter('start_date', $start->format('Y-m-d H:i:s'));

    if ($end != null) {
      $qb
      ->andWhere('e.start_date <= :stop_date')
      ->setParameter('stop_date', $end->format('Y-m-d H:i:s'));
    }

    return $qb->getQuery()->getResult();
  }
}