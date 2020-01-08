<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManagerInterface;
use EduBoxBundle\Entity\User;
use EduBoxBundle\Entity\UserMeta;

class ParentManager
{
    private $entityManager;
    private $userManager;

    public function __construct(EntityManagerInterface $entityManager, UserManager $userManager)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }

    public function getStudents(User $parent)
    {
        $rows =  $this->entityManager
            ->getRepository('EduBoxBundle:UserMeta')
            ->findBy(['metaKey' => UserMeta::STUDENT_PARENT_ID, 'metaValue' => $parent->getId()]);
        $students = [];
        foreach ($rows as $row) {
            $student = $row->getUser();
            if ($student instanceof User) {
                $students[] = $student;
            }
        }
        return $students;
    }


    public function hasStudent(User $parent, User $student)
    {
        $students = $this->getStudents($parent);
        $studentIds = [];
        foreach ($students as $_student) {
            $studentIds[] = $_student->getId();
        }
        return in_array($student->getId(), $studentIds);
    }

    public function hasOneStudent(User $parent)
    {
        $students = $this->getStudents($parent);
        if (count($students) == 1) {
            return reset($students);
        }
        else return false;
    }
}