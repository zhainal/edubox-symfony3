<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\Subject;
use EduBoxBundle\Entity\SubjectSchedule;
use EduBoxBundle\Entity\SubjectSchedulesGroup;
use EduBoxBundle\Entity\User;

class TeacherManager
{
    private $entityManager;
    private $subjectScheduleManager;
    private $subjectSchedulesGroupManager;
    private $subjectManager;

    public function __construct(
        EntityManager $entityManager,
        SubjectScheduleManager $subjectScheduleManager,
        SubjectSchedulesGroupManager $subjectSchedulesGroupManager,
        SubjectManager $subjectManager
    ) {
        $this->entityManager = $entityManager;
        $this->subjectScheduleManager = $subjectScheduleManager;
        $this->subjectSchedulesGroupManager = $subjectSchedulesGroupManager;
        $this->subjectManager = $subjectManager;
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

    public function hasStudentsGroup(User $parent, StudentsGroup $studentsGroup, Subject $subject = null) {
        $subjects = $this->getSubjects($parent);
        $studentsGroupIds = [];
        $subjectIds = [];
        foreach ($subjects as $subject) {
            $subjectIds[] = $subject->getId();
            $studentsGroups = $subject->getStudentsGroups();
            foreach ($studentsGroups as $studentsGroup) {
                $studentsGroupIds[] = $studentsGroup->getId();
            }
        }
        $result = in_array($studentsGroup->getId(), $studentsGroupIds);
        if ($subject instanceof Subject) {
            $result = $result && in_array($subject->getId(), $subjectIds);
        }
        return $result;
    }

    public function getLessonsBy(
        User $user,
        Subject $subject,
        $day,
        $hour,
        SubjectSchedulesGroup $subjectSchedulesGroup = null
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

    public function getSubjectSchedule(User $teacher)
    {
        $group = $this->subjectSchedulesGroupManager->getActiveGroup();
        if (!$group instanceof SubjectSchedulesGroup) {
            throw new \Exception('The system has not selected a schedule, inform the administrator about it.');
        }
        $subjects = $teacher->getSubjects();
        $subjectIds = [];
        foreach ($subjects as $subject) {
            if (!$subject instanceof Subject) continue;
            $subjectIds[] = $subject->getId();
        }
        $subjectSchedules = $group->getSubjectSchedules();
        $teacherSchedule = [];
        foreach ($subjectSchedules as $subjectSchedule) {
            if (!$subjectSchedule instanceof SubjectSchedule) continue;
            $schedule = $subjectSchedule->getSchedule();
            foreach ($schedule as $day => $hours) {
                foreach ($hours as $hour => $subjectId) {
                    if (in_array($subjectId, $subjectIds)) {
                        $teacherSchedule[$day][$hour] = [
                            'subject' => $this->subjectManager->getObject($subjectId),
                            'studentsGroup' => $subjectSchedule->getStudentsGroup()
                        ];
                    }
                }
            }
        }
        return $teacherSchedule;
    }
}