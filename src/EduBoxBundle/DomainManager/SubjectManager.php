<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\Subject;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class SubjectManager
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(Subject $subject)
    {
        $this->entityManager->persist($subject);
        $this->entityManager->flush();
    }

    public function store(Subject $subject)
    {
        $this->entityManager->flush();
    }
}