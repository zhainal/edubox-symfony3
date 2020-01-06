<?php

namespace EduBoxBundle\DomainManager;

use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\SubjectSchedule;
use EduBoxBundle\Entity\SubjectSchedulesGroup;
use EduBoxBundle\Entity\User;
use EduBoxBundle\Entity\UserMeta;

class StudentsGroupManager
{
    private $entityManager;
    private $userMetaManager;

    public function __construct(EntityManager $entityManager, UserMetaManager $userMetaManager)
    {
        $this->entityManager = $entityManager;
        $this->userMetaManager = $userMetaManager;
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

    public function getObject($studentsGroupId = null)
    {
        if ($studentsGroupId == null) {
            return null;
        }
        $studentsGroup = $this->entityManager->getRepository(StudentsGroup::class)->find($studentsGroupId);
        if ($studentsGroup) {
            return $studentsGroup;
        }
        return null;
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

    public function getStudents(StudentsGroup $studentsGroup)
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $students = $userRepository->createQueryBuilder('u')
            ->join('u.userMeta','m')
            ->where('m.metaKey = :key')
            ->andWhere('m.metaValue = :value')
            ->setParameter('key', UserMeta::STUDENT_GROUP_ID)
            ->setParameter('value', $studentsGroup->getId())
            ->getQuery()
            ->getResult();

        return $students;
    }

    public function getStudentsGroup(User $user)
    {
        return $this->userMetaManager->getStudentsGroup($user);
    }
}