<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\Subject;
use EduBoxBundle\Entity\SubjectSchedule;
use EduBoxBundle\Entity\User;

class SubjectScheduleManager
{
    private $entityManager;
    private $settingManager;

    public function __construct(EntityManager $entityManager, SettingManager $settingManager)
    {
        $this->entityManager = $entityManager;
        $this->settingManager = $settingManager;
    }

    public function create(SubjectSchedule $subjectSchedule)
    {
        $this->entityManager->persist($subjectSchedule);
        $this->entityManager->flush();
    }

    public function store(SubjectSchedule $subjectSchedule, array $data = null)
    {
        $this->entityManager->flush();
    }

    public function hasDay(SubjectSchedule $subjectSchedule, $day)
    {
        $schedule = $subjectSchedule->getSchedule();
        if (isset($schedule[$day]))
        {
            return true;
        }
        else {
            return false;
        }
    }

    public function hasHour(SubjectSchedule $subjectSchedule, $day, $hour)
    {
        $schedule = $subjectSchedule->getSchedule();
        if ($this->hasDay($subjectSchedule, $day))
        {
            if (isset($schedule[$day][$hour]))
            {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function getDays(SubjectSchedule $subjectSchedule)
    {
        $schedule = $subjectSchedule->getSchedule();
        $dayNames = array_keys($schedule);
        return $dayNames;
    }

    public function getHours(SubjectSchedule $subjectSchedule, $day)
    {
        $schedule = $subjectSchedule->getSchedule();
        if (!$this->hasDay($subjectSchedule, $day))
        {
            return null;
        }
        $hourNames = array_keys($schedule[$day]);

        return $hourNames;
    }

    public function getSubject(SubjectSchedule $subjectSchedule, $day, $hour)
    {
        if (!$this->hasHour($subjectSchedule, $day, $hour))
        {
            return null;
        }
        $schedule = $subjectSchedule->getSchedule();
        $subject_id = (int)$schedule[$day][$hour];
        if (!$subject_id)
        {
            return null;
        }
        $subject = $this->entityManager
            ->getRepository(Subject::class)
            ->find($subject_id);
        if (!$subject)
        {
            return null;
        }
        return $subject;
    }

    public function addDay(SubjectSchedule $subjectSchedule, $day)
    {
        $schedule = $subjectSchedule->getSchedule();
        $schedule[$day] = [];
        $this->store($subjectSchedule->setSchedule($schedule));
        return $subjectSchedule;
    }

    public function addHour(SubjectSchedule $subjectSchedule, $day, $hour)
    {
        if (!$this->hasDay($subjectSchedule, $day))
        {
            return null;
        }
        $schedule = $subjectSchedule->getSchedule();
        $schedule[$day][$hour] = [];
        $subjectSchedule->setSchedule($schedule);
        $this->store($subjectSchedule);
        return $subjectSchedule;
    }

    public function setSubject(SubjectSchedule $subjectSchedule, $day, $hour, Subject $subject = null, $strict = false)
    {
        if ($strict)
        {
            if (!$this->hasHour($subjectSchedule, $day, $hour))
            {
                return null;
            }
        }
        $schedule = $subjectSchedule->getSchedule();
        if ($subject !== null) {
            $schedule[$day][$hour] = $subject->getId();
        }
        else {
            $schedule[$day][$hour] = null;
        }
        $subjectSchedule->setSchedule($schedule);
        $this->store($subjectSchedule);
        return $subjectSchedule;
    }

    public function getUserLessonsBy(
        User $user,
        Subject $subject,
        SubjectSchedule $subjectSchedule,
        $day,
        $hour
    ) {
        $result = [];
        $scheduleSubject = $this->getSubject($subjectSchedule, $day, $hour);
        if ($scheduleSubject)
        {
            if ($scheduleSubject->getId() == $subject->getId())
            {
                $subjectUser = $scheduleSubject->getUser();
                if ($user->getId() == $subjectUser->getId())
                {
                    $result[] = ['subject'=>$subject, 'subjectSchedule' => $subjectSchedule];
                }
            }
        }
        return $result;
    }
}