<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManagerInterface;
use EduBoxBundle\Entity\Mark;
use EduBoxBundle\Entity\Quarter;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\Subject;
use EduBoxBundle\Entity\User;
use EduBoxBundle\Event\MarkCreatedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MarkManager
{
    private $entityManager;
    private $calendarManager;
    private $studentsGroupManager;
    private $quarterManager;
    private $eventDispatcher;

    /**
     * MarkManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param CalendarManager $calendarManager
     * @param StudentsGroupManager $studentsGroupManager
     * @param QuarterManager $quarterManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CalendarManager $calendarManager,
        StudentsGroupManager $studentsGroupManager,
        QuarterManager $quarterManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->entityManager = $entityManager;
        $this->calendarManager = $calendarManager;
        $this->studentsGroupManager = $studentsGroupManager;
        $this->quarterManager = $quarterManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function create(Mark $mark)
    {
        $this->entityManager->persist($mark);
        $this->entityManager->flush();
    }

    public function store(Mark $mark)
    {
        $this->entityManager->flush();
    }

    public function getSourceMarks() {
        return ['2', '3', '4', '5', 'dc'];
    }

    public function hasSourceMark($mark) {
        return in_array($mark, $this->getSourceMarks());
    }

    public function getSourceMark($mark) {
        $marks = $this->getSourceMarks();
        $mark = strtolower($mark);
        if (in_array($mark, $marks)) {
            return $mark;
        }
        throw new \Exception('Incorrect mark: '.$mark);
    }

    public function getAverage(Subject $subject, User $user, $number)
    {
        $mark = $this->getAverageFloat($subject, $user, $number);
        if ($mark == Quarter::NO_MARK || !is_int($mark))
        {
            return $mark;
        }
        return round($mark);
    }

    public function getAverageFloat(Subject $subject, User $user, $number)
    {
        $quarterRepository = $this->entityManager->getRepository(Quarter::class);
        $quarter = $quarterRepository->findOneBy([
            'number' => $number,
            'subject' => $subject,
            'user' => $user,
        ]);
        if (!$quarter) {
            return Quarter::NO_MARK;
        }
        return $quarter->getMark();
    }

    public function getMarks(Subject $subject, StudentsGroup $studentsGroup, $quarter)
    {
        $calendar = $this->calendarManager->getActiveCalendar();
        $quarter = $this->quarterManager->getQuarter($quarter);
        $beginDate = $calendar->getBeginDate($quarter);
        $endDate = $calendar->getEndDate($quarter);
        return $this->formatForJournal(
            $this->getMarksBy($studentsGroup, $subject, $beginDate, $endDate)
        );

    }

    public function formatForJournal($marksArr)
    {
        $result = [];
        foreach ($marksArr as $mark) {
            if ($mark instanceof Mark) {
                $date = $mark->getDate();
                $result
                    [$mark->getUser()->getId()]
                    [$date->format('Y')]
                    [$date->format('m')]
                    [$date->format('d')]
                    [$mark->getHour()] = $mark;
            }
        }
        return $result;
    }

    public function groupByStudents($marks)
    {
        $marksByStudents = [];
        foreach ($marks as $mark) {
            $marksByStudents[$mark->getUser()->getId()][] = $mark;
        }
        return $marksByStudents;
    }


    public function createNotExistsMarks(
        Subject $subject,
        StudentsGroup $studentsGroup,
        \DateTime $beginDate,
        \DateTime $endDate
    ) {
        $markRepository = $this->entityManager->getRepository(Mark::class);
        $students = $this->studentsGroupManager->getStudents($studentsGroup);
        $dates = $this->quarterManager->getDates($subject, $studentsGroup, $beginDate, $endDate);

        foreach ($students as $student) {
            foreach ($dates as $date) {
                foreach ($dates as $date => $hours) {
                    for ($hour = 1; $hour <= $hours; $hour++) {
                        $markRepository->findOneByOrCreate([
                            'user' => $student,
                            'subject' => $subject,
                            'date' => (new \DateTime())->setTimestamp(strtotime($date)),
                            'hour' => $hour,
                        ]);
                    }
                }
            }
        }
    }

    public function getDatesTree(
        Subject $subject,
        StudentsGroup $studentsGroup,
        $quarter
    ) {
        $calendar = $this->calendarManager->getActiveCalendar();
        $beginDate = $calendar->getBeginDate($quarter);
        $endDate = $calendar->getEndDate($quarter);
        $dates = $this->quarterManager->getDates($subject, $studentsGroup, $beginDate, $endDate);
        $tree = [];

        foreach ($dates as $date) {
            foreach ($dates as $date => $hours) {
                $date = strtotime($date);
                foreach (range(1,$hours) as $hour) {
                    $tree[date('Y', $date)][date('m', $date)][date('d', $date)][$hour] = date('N', $date);
                }
            }
        }

        return $tree;
    }

    /**
     * @param StudentsGroup|null $studentsGroup
     * @param Subject|null $subject
     * @param \DateTime|null $beginDate
     * @param \DateTime|null $endDate
     * @return array
     */
    public function getMarksBy(
        StudentsGroup $studentsGroup = null,
        Subject $subject = null,
        \DateTime $beginDate = null,
        \DateTime $endDate = null
    ) {
        $repository = $this->entityManager->getRepository(Mark::class);

        if ($studentsGroup instanceof StudentsGroup) {
            $students = $this->studentsGroupManager->getStudents($studentsGroup);
            $student_ids = [];
            foreach ($students as $student) {
                $student_ids[] = $student->getId();
            }
        }

        $marks = $repository->createQueryBuilder('m');

        if ($beginDate instanceof \DateTime) {
            $marks->andwhere('m.date >= :beginDate')->setParameter('beginDate', $beginDate->format('Y-m-d'));
        }
        if ($endDate instanceof \DateTime) {
            $marks->andWhere('m.date <= :endDate')->setParameter('endDate', $endDate->format('Y-m-d'));
        }
        if ($subject instanceof Subject) {
            $marks->andWhere('m.subject = :subject')->setParameter('subject', $subject);
        }
        if (isset($student_ids) && is_array($student_ids)) {
            $marks->andWhere($marks->expr()->in('m.user', $student_ids));
        }

        return $marks->getQuery()->getResult();
    }

    public function createMark(
        Subject $subject,
        User $user,
        $mark,
        \DateTime $date,
        $hour,
        $comment
    ) {
        $mark = $this->getSourceMark($mark);
        $cell = $this->entityManager->getRepository(Mark::class)->findOneByOrCreate([
            'date' => $date,
            'hour' => $hour,
            'user' => $user,
            'subject' => $subject,
        ]);
        $cell->setMark($mark);
        $cell->setComment($comment);
        $this->store($cell);

        $event = new MarkCreatedEvent($cell);
        $this->eventDispatcher->dispatch(MarkCreatedEvent::MARK_CREATED, $event);

        $quarter = $this->quarterManager->getQuarterByDate($date);
        return $this->quarterManager->getQuarterMark($user, $subject, $quarter);
    }

    public function getMark(Subject $subject, User $user, \DateTime $date, $hour)
    {
        $repository = $this->entityManager->getRepository(Mark::class);
        return $repository->findOneBy([
            'subject' => $subject,
            'user' => $user,
            'date' => $date,
            'hour' => $hour,
        ]);
    }
}