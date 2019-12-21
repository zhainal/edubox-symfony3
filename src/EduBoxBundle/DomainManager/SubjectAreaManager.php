<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\SubjectArea;

class SubjectAreaManager
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(SubjectArea $subjectArea)
    {
        $this->entityManager->persist($subjectArea);
        $this->entityManager->flush();
    }

    public function store(SubjectArea $subjectArea)
    {
        $this->entityManager->flush();
    }
}