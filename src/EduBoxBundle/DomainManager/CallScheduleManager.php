<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\CallSchedule;

class CallScheduleManager
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(CallSchedule $callSchedule)
    {
        $this->entityManager->persist($callSchedule);
        $this->entityManager->flush();
    }

    public function store(CallSchedule $callSchedule)
    {
        $this->entityManager->flush();
    }
}