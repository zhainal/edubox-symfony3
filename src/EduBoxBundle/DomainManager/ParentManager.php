<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManagerInterface;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\User;
use EduBoxBundle\Entity\UserMeta;

class ParentManager
{
    private $entityManager;
    private $userManager;
    private $studentManager;

    public function __construct(EntityManagerInterface $entityManager, UserManager $userManager, StudentManager $studentManager)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        $this->studentManager = $studentManager;
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
                if ($student->hasRole('ROLE_STUDENT')) {
                    $students[] = $student;
                }
            }
        }
        return $students;
    }

    public function getStudent(User $parent, $studentId)
    {
        $students = $this->getStudents($parent);
        $studentIds = [];
        foreach ($students as $student) {
            $studentIds[] = $student->getId();
        }
        if (!in_array($studentId, $studentIds)) {
            return null;
        }
        return $this->userManager->getObject($studentId);
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

    public function hasStudentsGroup(User $parent, StudentsGroup $studentsGroup)
    {
        $students = $this->getStudents($parent);
        $result = false;
        foreach ($students as $student) {
            $result = $result || $this->studentManager->hasStudentsGroup($student, $studentsGroup);
        }
        return $result;
    }
}