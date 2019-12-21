<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\Mark;

class MarkManager
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(Mark $mark)
    {
        $this->entityManager->persist($mark);
        $this->entityManager->flush();
    }

    public function store(Mark $mark)
    {
        $this->entityManager->flush();
    }
}