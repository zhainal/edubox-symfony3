<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\Subject;
use EduBoxBundle\Entity\SubjectSchedule;
use EduBoxBundle\Entity\SubjectSchedulesGroup;
use EduBoxBundle\Entity\User;

class TeacherManager
{
    private $entityManager;
    private $subjectScheduleManager;

    public function __construct(EntityManager $entityManager, SubjectScheduleManager $subjectScheduleManager)
    {
        $this->entityManager = $entityManager;
        $this->subjectScheduleManager = $subjectScheduleManager;
    }

    public function create(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function store(User $user)
    {
        $this->entityManager->flush();
    }

    public function getLessonsBy(
        User $user,
        Subject $subject,
        SubjectSchedulesGroup $subjectSchedulesGroup,
        $day,
        $hour
    ) {
        $result = [];
        foreach ($subjectSchedulesGroup->getSubjectSchedules() as $subjectSchedule) {
            $result = array_merge(
                $result,
                $this->subjectScheduleManager->getUserLessonsBy(
                    $user,
                    $subject,
                    $subjectSchedule,
                    $day,
                    $hour
                )
            );
        }
        return $result;
    }

    public function hasStudent(User $teacher, User $student)
    {
        if ($student->hasRole('ROLE_STUDENT')) {

        }
    }

    public function getSubjects(User $teacher)
    {
        return $teacher->getSubjects();
    }

    public function getStudentsGroups()
    {
        $subjectRepository = $this->entityManager->getRepository(Subject::class);
        return $subjectRepository->findBy([]);
    }
}