<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\Lesson;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\Subject;

class LessonManager
{
    private $entityManager;
    private $quarterManager;
    private $studentsGroupManager;

    public function __construct(EntityManager $entityManager, QuarterManager $quarterManager, StudentsGroupManager $studentsGroupManager)
    {
        $this->entityManager = $entityManager;
        $this->quarterManager = $quarterManager;
        $this->studentsGroupManager = $studentsGroupManager;
    }

    public function store(Lesson $lesson)
    {
        $this->entityManager->flush();
    }

    public function getObject($lessonId)
    {
        if ($lessonId == null) {
            return null;
        }
        $lesson = $this->entityManager->getRepository(Lesson::class)->find($lessonId);
        if ($lesson) {
            return $lesson;
        }
        return null;
    }

    public function getLessonsByQuarter(Subject $subject, StudentsGroup $studentsGroup, $quarter = null)
    {
        if ($quarter == null) $quarter = $this->quarterManager->getCurrentQuarter();
        $this->quarterManager->isQuarterCorrect($quarter);
        $beginDate = $this->quarterManager->getBeginDate($quarter);
        $endDate = $this->quarterManager->getEndDate($quarter);
        $this->createNotExistsLessons($subject, $studentsGroup, $beginDate, $endDate);
        return $this->getLessonsBy($subject, $studentsGroup, $beginDate, $endDate);
    }

    public function createNotExistsLessons(Subject $subject, StudentsGroup $studentsGroup, \DateTime $beginDate, \DateTime $endDate)
    {
        $repository = $this->entityManager->getRepository(Lesson::class);
        $dates = $this->quarterManager->getDates($subject, $studentsGroup, $beginDate, $endDate);

        foreach ($dates as $date) {
            foreach ($dates as $date => $hours) {
                for ($hour = 1; $hour <= $hours; $hour++) {
                    $repository->findOneByOrCreate([
                        'studentsGroup' => $studentsGroup,
                        'subject' => $subject,
                        'date' => (new \DateTime())->setTimestamp(strtotime($date)),
                        'hour' => $hour,
                    ]);
                }
            }
        }
    }

    public function getLessonsBy(Subject $subject = null, StudentsGroup $studentsGroup = null, \DateTime $beginDate = null, \DateTime $endDate = null)
    {
        $repository = $this->entityManager->getRepository(Lesson::class);
        $lessons = $repository->createQueryBuilder('l');

        if ($beginDate != null) {
            $lessons->andwhere('l.date >= :beginDate')->setParameter('beginDate', $beginDate->format('Y-m-d H:i:s'));
        }
        if ($endDate != null) {
            $lessons->andWhere('l.date <= :endDate')->setParameter('endDate', $endDate->format('Y-m-d H:i:s'));
        }
        if ($subject != null) {
            $lessons->andWhere('l.subject = :subject')->setParameter('subject', $subject);
        }
        if ($studentsGroup != null) {
            $lessons->andWhere('l.studentsGroup = :studentsGroup')->setParameter('studentsGroup', $studentsGroup);
        }

        return $lessons->getQuery()->getResult();
    }

}