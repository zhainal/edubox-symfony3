<?php

namespace EduBoxBundle\DomainManager;

use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\SubjectSchedule;
use EduBoxBundle\Entity\SubjectSchedulesGroup;

class StudentsGroupManager
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(StudentsGroup $studentsGroup)
    {
        $this->entityManager->persist($studentsGroup);
        $this->entityManager->flush();
    }

    public function store(StudentsGroup $studentsGroup)
    {
        $this->entityManager->flush();
    }

    public function getSubectScheduleStatus(StudentsGroup $studentsGroup, SubjectSchedulesGroup $subjectSchedulesGroup)
    {
        $subjectScheduleRepository = $this->entityManager->getRepository(SubjectSchedule::class);
        $subjectSchedule = $subjectScheduleRepository->findOneBy([
            'studentsGroup' => $studentsGroup,
            'subjectSchedulesGroup' => $subjectSchedulesGroup,
        ]);
        if (!$subjectSchedule)
        {
            return 'Not created';
        }
        else {
            return 'Created';
        }
    }
}