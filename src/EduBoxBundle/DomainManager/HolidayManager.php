<?php


namespace EduBoxBundle\DomainManager;

use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\Holiday;

class HolidayManager
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(Holiday $holiday)
    {
        $this->entityManager->persist($holiday);
        $this->entityManager->flush();
    }

    public function store(Holiday $holiday)
    {
        $this->entityManager->flush();
    }
}