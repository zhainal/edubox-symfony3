<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\Calendar;

class CalendarManager
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createCalendar(Calendar $calendar)
    {
        $this->entityManager->persist($calendar);
        $this->entityManager->flush();
    }

    public function storeCalendar(Calendar $calendar)
    {
        $this->entityManager->flush();
    }
}