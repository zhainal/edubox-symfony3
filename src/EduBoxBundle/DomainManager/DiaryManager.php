<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManagerInterface;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\Subject;
use EduBoxBundle\Entity\SubjectSchedule;
use EduBoxBundle\Entity\User;

class DiaryManager
{
    private $entityManager;
    private $subjectScheduleManager;
    private $subjectManager;
    private $lessonManager;
    private $studentManager;
    private $markManager;

    /**
     * DiaryManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param SubjectScheduleManager $subjectScheduleManager
     * @param SubjectManager $subjectManager
     * @param LessonManager $lessonManager
     * @param StudentManager $studentManager
     * @param MarkManager $markManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        SubjectScheduleManager $subjectScheduleManager,
        SubjectManager $subjectManager,
        LessonManager $lessonManager,
        StudentManager $studentManager,
        MarkManager $markManager
    ) {
        $this->entityManager = $entityManager;
        $this->subjectScheduleManager = $subjectScheduleManager;
        $this->subjectManager = $subjectManager;
        $this->lessonManager = $lessonManager;
        $this->studentManager = $studentManager;
        $this->markManager = $markManager;
    }



    public function getBeginDateOfWeek($next)
    {
        $date = time() - date('N')*3600*24;
        $date += $next*3600*24*7;
        return $date;
    }

    public function getDiary(User $student, $next)
    {
        $studentsGroup = $this->studentManager->getStudentsGroup($student);
        if (!$studentsGroup instanceof StudentsGroup) {
            return [];
        }
        $beginDateOfWeek = $this->getBeginDateOfWeek($next);
        $diary = $this->subjectScheduleManager->getSubjectSchedule($studentsGroup);
        if (!$diary instanceof SubjectSchedule) {
            return [];
        }
        $diary = $diary->getSchedule();
        foreach ($diary as $day => &$hours) {
            $date = (new \DateTime())->setTimestamp($beginDateOfWeek + $day*24*3600);
            $subjectsHour = [];
            foreach ($hours as $hour => &$subjectId) {
                $subject = $this->subjectManager->getObject($subjectId);
                if ($subject instanceof Subject) {
                    @$subjectsHour[$subject->getId()]++;
                    $subjectId = [
                        'subject' => $subject,
                        'subject_hour' => $subjectsHour[$subject->getId()],
                        'lesson' => $this->lessonManager->getLesson($subject, $studentsGroup, $date, $subjectsHour[$subject->getId()]),
                        'mark' => $this->markManager->getMark($subject, $student, $date, $subjectsHour[$subject->getId()]),
                        'hour' => $hour,
                    ];
                }
                else {
                    $subjectId = [
                        'subject' => null,
                        'lesson' => null,
                        'mark' => null,
                        'hour' => $hour,
                    ];
                }
            }
            $hours['date'] = $date;
        }
        return $diary;
    }
}