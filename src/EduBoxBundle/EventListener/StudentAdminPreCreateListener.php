<?php


namespace EduBoxBundle\EventListener;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\StudentClass;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Event\AdminEvent;

class StudentAdminPreCreateListener
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onAdminPreCreate(AdminEvent $event)
    {
        $event->studentClassRepository = $this->entityManager->getRepository(StudentsGroup::class);
    }
}