<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManagerInterface;
use EduBoxBundle\Entity\User;
use EduBoxBundle\Entity\UserMeta;

class ParentManager
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getStudents(User $user)
    {
        $studentIds = $this->entityManager
            ->getRepository('EduBoxBundle:UserMeta')
            ->findBy(['metaKey' => UserMeta::STUDENT_PARENT_ID, 'metaValue' => $user->getId()]);
        $userRepository = $this->entityManager->getRepository(User::class);
        $students = [];
        foreach ($studentIds as $studentId) {
            $student = $userRepository->find($studentId);
            if ($student instanceof User) {
                if ($student->hasRole('ROLE_STUDENT')) {
                    $students[] = $student;
                }
            }
        }
        return $students;
    }

}