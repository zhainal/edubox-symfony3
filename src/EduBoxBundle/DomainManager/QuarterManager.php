<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\Mark;
use EduBoxBundle\Entity\Quarter;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\Subject;
use EduBoxBundle\Entity\User;

class QuarterManager
{
    private $entityManager;
    private $calendarManager;
    private $studentsGroupManager;
    private $subjectSchedulesGroupManager;

    public function __construct(
        EntityManager $entityManager,
        CalendarManager $calendarManager,
        StudentsGroupManager $studentsGroupManager,
        SubjectSchedulesGroupManager $subjectSchedulesGroupManager
    ) {
        $this->entityManager = $entityManager;
        $this->calendarManager = $calendarManager;
        $this->studentsGroupManager = $studentsGroupManager;
        $this->subjectSchedulesGroupManager = $subjectSchedulesGroupManager;
    }

    public function create(Quarter $quarter)
    {
        $this->entityManager->persist($quarter);
        $this->entityManager->flush();
    }

    public function store(Quarter $quarter)
    {
        $this->entityManager->flush();
    }

    public function getQuarter($quarter)
    {
        if (!$this->hasQuarter($quarter)) {
            return $this->getCurrentQuarter();
        }
        return $quarter;
    }


    /**
     * @param $quarter
     * @throws \Exception
     */
    public function isQuarterCorrect($quarter)
    {
        if (!$this->hasQuarter($quarter)) {
            throw new \Exception('Quarter is not correct');
        }
    }

    public function getBeginDate($quarter)
    {
        return $this->calendarManager->getActiveCalendar()->getBeginDate($quarter);
    }

    public function getEndDate($quarter)
    {
        return $this->calendarManager->getActiveCalendar()->getEndDate($quarter);
    }

    public function getQuarters()
    {
        return [1,2,3,4];
    }

    public function getCurrentQuarter()
    {
        return 2;
    }

    public function hasQuarter($quarter)
    {
        return in_array($quarter, $this->getQuarters());
    }

    public function getAverages(Subject $subject, $students, $quarter)
    {
        $student_ids = [];
        foreach ($students as $student) {
            $student_ids[] = $student->getId();
        }
        $repository = $this->entityManager->getRepository(Quarter::class);
        $quarters = $repository->createQueryBuilder('q');
        $quarters
            ->where('q.number = :numer')
            ->andWhere($quarters->expr()->in('q.user', $student_ids))
            ->andWhere('q.subject = :subject');
        $quarters
            ->setParameter('numer', $quarter)
            ->setParameter('subject', $subject);

        $quarters = $quarters->getQuery()->getResult();
        return $quarters;
    }

    public function formatResult(array $rows, $round = false)
    {
        $quarters = [];
        foreach ($rows as $row) {
            $quarters[$row->getUser()->getId()] = $round ? round($row->getMark()) : $row->getMark();
        }
        return $quarters;
    }

    public function getQuarterDates()
    {
        $quarters = $this->getQuarters();
        $quarter_dates = [];
        foreach ($quarters as $quarter) {
            $begin = $this->getBeginDate($quarter);
            $end = $this->getEndDate($quarter);
            if (!$begin || !$end) {
                continue;
            }
            if ($begin instanceof \DateTime) {
                $begin = $begin->format('Y-m-d');
            }
            if ($end instanceof \DateTime) {
                $end = $end->format('Y-m-d');
            }
            $begin = strtotime($begin);
            $end = strtotime($end);
            $quarter_dates[$quarter]['begin'] = $begin;
            $quarter_dates[$quarter]['end'] = $end;
        }
        return $quarter_dates;
    }

    public function getQuarterByDate($date)
    {
        if ($date instanceof \DateTime) {
            $date = $date->format('Y-m-d');
        }
        $date = strtotime($date);
        $quarter_dates = $this->getQuarterDates();
        foreach ($quarter_dates as $quarter => $qdate) {
            if ($qdate['begin'] <= $date && $qdate['end'] >= $date) {
                return $quarter;
            }
        }
        throw new \Exception('Cannot find quarter for a given date');
    }

    public function getQuarterMark(User $user, Subject $subject, $quarter)
    {
        $quarterRepository = $this->entityManager->getRepository(Quarter::class);
        $quarter = $quarterRepository->findOneBy([
            'user' => $user,
            'subject' => $subject,
            'number' => $quarter,
        ]);
        if ($quarter) {
            $mark = $quarter->getMark();
            if ($mark < 1) {
                return Quarter::NO_MARK;
            }
            else {
                return $mark;
            }
        }
        else {
            return Quarter::NO_MARK;
        }
    }

    public function getQuartersByUser(User $user)
    {
        $studentsGroup = $this->studentsGroupManager->getStudentsGroup($user);
        if (!$studentsGroup instanceof StudentsGroup) {
            return [];
        }
        $subjects = $studentsGroup->getSubjects();
        $subjectsWithQuarter = [];
        foreach ($subjects as $subject) {
            $subjectsWithQuarter[] = [
                'name' => $subject->getName(),
                'quarter' => [
                    1 => $this->getQuarter($user, $subject, 1),
                    2 => $this->getQuarter($user, $subject, 2),
                    3 => $this->getQuarter($user, $subject, 3),
                    4 => $this->getQuarter($user, $subject, 4),
                ]
            ];
        }
        return $subjectsWithQuarter;
    }

    public function getDates(
        Subject $subject,
        StudentsGroup $studentsGroup,
        \DateTime $beginDate,
        \DateTime $endDate
    ) {
        $schedulesGroup = $this->subjectSchedulesGroupManager->getActiveGroup();
        $subjectSchedule = $this->subjectSchedulesGroupManager->getSchedule($schedulesGroup, $studentsGroup);
        $schedule = $subjectSchedule->getSchedule();
        $dates = [];
        foreach ($schedule as $dayNum => $day) {
            for ($date = $beginDate->getTimestamp(); $date <= $endDate->getTimestamp(); $date += 3600*24) {
                foreach ($day as $subjectId) {
                    if (date('N', $date) == $dayNum && $subjectId == $subject->getId()) {
                        if (isset($dates[date('Y-m-d',$date)])) {
                            $dates[date('Y-m-d', $date)]++;
                        } else {
                            $dates[date('Y-m-d',$date)] = 1;
                        }
                    }
                }
            }
        }
        ksort($dates);
        return $dates;
    }
}