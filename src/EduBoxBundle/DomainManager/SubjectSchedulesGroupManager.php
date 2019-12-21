<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\SubjectSchedulesGroup;

class SubjectSchedulesGroupManager
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(SubjectSchedulesGroup $schedulesGroup)
    {
        $this->entityManager->persist($schedulesGroup);
        $this->entityManager->flush();
    }

    public function store(SubjectSchedulesGroup $schedulesGroup)
    {
        $this->entityManager->flush();
    }

    public function getStatus(SubjectSchedulesGroup $subjectSchedulesGroup)
    {
        return '-';
    }


}