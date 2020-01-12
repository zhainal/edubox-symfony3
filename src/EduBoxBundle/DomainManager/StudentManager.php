<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManagerInterface;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\User;
use EduBoxBundle\Entity\UserMeta;

class StudentManager
{
    private $entityManager;
    private $userMetaManager;
    private $userManager;

    /**
     * StudentManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserMetaManager $userMetaManager
     * @param UserManager $userManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserMetaManager $userMetaManager,
        UserManager $userManager
    ) {
        $this->entityManager = $entityManager;
        $this->userMetaManager = $userMetaManager;
        $this->userManager = $userManager;
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

    public function hasStudentsGroup(User $student, StudentsGroup $studentsGroup)
    {
        $currentStudentsGroup = $this->getStudentsGroup($student);
        if (!$currentStudentsGroup instanceof StudentsGroup) {
            return false;
        }
        return $currentStudentsGroup->getId() == $studentsGroup->getId();
    }

    public function getParent(User $user)
    {
        $parentId = $this->userMetaManager->getValue($user, UserMeta::STUDENT_PARENT_ID);
        $parent = $this->userManager->getObject($parentId);
        return $parent;
    }

}