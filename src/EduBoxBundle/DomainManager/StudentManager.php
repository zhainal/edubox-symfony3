<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManagerInterface;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\SubjectSchedule;
use EduBoxBundle\Entity\User;

class StudentManager
{
    private $entityManager;
    private $userMetaManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserMetaManager $userMetaManager
    ) {
        $this->entityManager = $entityManager;
        $this->userMetaManager = $userMetaManager;
    }

    /**
     * @param User $student
     * @return StudentsGroup|null
     */
    public function getStudentsGroup(User $student)
    {
        return $this->userMetaManager->getStudentsGroup($student);
    }

    public function getSubjects(User $user)
    {
        $studentsGroup = $this->getStudentsGroup($user);
        if (!$studentsGroup instanceof StudentsGroup) {
            return [];
        }
        return $studentsGroup->getSubjects();
    }

}