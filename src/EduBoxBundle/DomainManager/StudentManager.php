<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\Mark;
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
     * @param EntityManager $entityManager
     * @param UserMetaManager $userMetaManager
     * @param UserManager $userManager
     */
    public function __construct(
        EntityManager $entityManager,
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

    public function withParent(User $user)
    {
        $user->parent = $this->getParent($user);
        return $user;
    }

    public function getRating()
    {
        $qb = $this->entityManager->getRepository(Mark::class)->createQueryBuilder('m');
        $qb
            ->select(['u.id as userId', 'COUNT(m.id) as total'])
            ->innerJoin('m.user','u')
            ->where('m.mark = 5')
            ->groupBy('m.user')
            ->orderBy('total','desc')
            ->setMaxResults(50);
        $studentIds = $qb->getQuery()->getResult();

        $students = [];
        foreach ($studentIds as $studentId) {
            $student = $this->userManager->getObject($studentId['userId']);
            if ($student instanceof User) {
                if ($student->hasRole('ROLE_STUDENT')) {
                    $student->score = $studentId['total'];
                    $students[] = $student;
                }
            }
        }
        return $students;
    }

}