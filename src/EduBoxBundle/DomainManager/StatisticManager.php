<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\Mark;
use EduBoxBundle\Entity\Setting;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\Subject;
use EduBoxBundle\Entity\User;

class StatisticManager
{
    private $entityManager;
    private $settingManager;
    private $studentManager;
    private $quarterManager;
    private $studentsGroupManager;

    public function __construct(
        EntityManager $entityManager,
        SettingManager $settingManager,
        StudentManager $studentManager,
        QuarterManager $quarterManager,
        StudentsGroupManager $studentsGroupManager
    ) {
        $this->entityManager = $entityManager;
        $this->settingManager = $settingManager;
        $this->studentManager = $studentManager;
        $this->quarterManager = $quarterManager;
        $this->studentsGroupManager = $studentsGroupManager;
    }

    public function updatePerformanceStudent(User $student, Subject $subject, $quarter)
    {
        if (!$this->quarterManager->hasQuarter($quarter)){
            return false;
        }
        $studentsGroup = $this->studentManager->getStudentsGroup($student);
        if (!$studentsGroup instanceof StudentsGroup) {
            return false;
        }
        $beginDate = $this->quarterManager->getBeginDate($quarter);
        $endDate = $this->quarterManager->getEndDate($quarter);
        $dates = $this->quarterManager->getDates($subject, $studentsGroup, $beginDate, $endDate);
        if (count($dates) > 0) {
            // get max marks sum
            $count = 0;
            foreach ($dates as $date => $hours) {
                $count += (int)$hours;
            }
            $maxMarkSum = $count * 5;
            // get current marks sum
            $marks = $this->entityManager->getRepository(Mark::class)->createQueryBuilder('m');
            $currentMarkSum = (int)$marks
                ->select('SUM(m.mark) as total')
                ->andWhere('m.user = :user')->setParameter('user', $student)
                ->andWhere('m.subject = :subject')->setParameter('subject', $subject)
                ->andWhere($marks->expr()->in('m.date', array_keys($dates)))
                ->getQuery()->getResult()[0]['total'];
        }
        else {
            $currentMarkSum = 0;
            $maxMarkSum = 0;
        }
        $row = $this->settingManager->getPerformance($quarter, $studentsGroup, $subject, $student);
        if ($row instanceof Setting) {
            $row->setSettingValue(json_encode(['max'=>$maxMarkSum,'current'=>$currentMarkSum]));
            $this->settingManager->store($row);
            return json_decode($row->getSettingValue());
        }
        return false;
    }

    public function updatePerformanceSubject(StudentsGroup $studentsGroup, Subject $subject, $quarter)
    {
        if (!$this->quarterManager->hasQuarter($quarter)){
            return false;
        }
        $students = $this->studentsGroupManager->getStudents($studentsGroup);
        $currentMarkSum = 0;
        $maxMarkSum = 0;
        foreach ($students as $student) {
            $row = $this->updatePerformanceStudent($student, $subject, $quarter);
            if (is_object($row)) {
                $currentMarkSum += (int)$row->current;
                $maxMarkSum += (int)$row->max;
            }
        }
        $row = $this->settingManager->getPerformance($quarter, $studentsGroup, $subject);
        if ($row instanceof Setting) {
            $row->setSettingValue(json_encode(['max'=>$maxMarkSum, 'current'=>$currentMarkSum]));
            $this->settingManager->store($row);
            return json_decode($row->getSettingValue());
        }
        return false;
    }

    public function updatePerformance(StudentsGroup $studentsGroup, $quarter)
    {
        if (!$this->quarterManager->hasQuarter($quarter)){
            return false;
        }
        $subjects = $studentsGroup->getSubjects();
        $currentMarkSum = 0;
        $maxMarkSum = 0;
        foreach ($subjects as $subject) {
            $row = $this->updatePerformanceSubject($studentsGroup, $subject, $quarter);
            if (is_object($row)) {
                $currentMarkSum += (int)$row->current;
                $maxMarkSum += (int)$row->max;
            }
        }
        $row = $this->settingManager->getPerformance($quarter, $studentsGroup);
        if ($row instanceof Setting) {
            $row->setSettingValue(json_encode(['max'=>$maxMarkSum, 'current'=>$currentMarkSum]));
            $this->settingManager->store($row);
            return json_decode($row->getSettingValue());
        }
        return false;
    }



    public function updateAttendanceStudent(User $student, Subject $subject, $quarter)
    {
        if (!$this->quarterManager->hasQuarter($quarter)){
            return false;
        }
        $studentsGroup = $this->studentManager->getStudentsGroup($student);
        if (!$studentsGroup instanceof StudentsGroup) {
            return false;
        }
        $beginDate = $this->quarterManager->getBeginDate($quarter);
        $endDate = $this->quarterManager->getEndDate($quarter);
        $dates = $this->quarterManager->getDates($subject, $studentsGroup, $beginDate, $endDate);
        if (count($dates) > 0) {
            // get max DC count
            $count = 0;
            foreach ($dates as $date => $hours) {
                $count += (int)$hours;
            }
            $maxDCCount = $count;
            // get current marks sum
            $marks = $this->entityManager->getRepository(Mark::class)->createQueryBuilder('m');
            $currentDCCount = (int)$marks
                ->select('COUNT(m.mark) as total')
                ->andWhere('m.mark = :mark')->setParameter('mark', Mark::MARK_DC)
                ->andWhere('m.user = :user')->setParameter('user', $student)
                ->andWhere('m.subject = :subject')->setParameter('subject', $subject)
                ->andWhere($marks->expr()->in('m.date', array_keys($dates)))
                ->getQuery()->getResult()[0]['total'];
        }
        else {
            $currentDCCount = 0;
            $maxDCCount = 0;
        }
        $row = $this->settingManager->getAttendance($quarter, $studentsGroup, $subject, $student);
        if ($row instanceof Setting) {
            $row->setSettingValue(json_encode(['max'=>$maxDCCount,'current'=>$currentDCCount]));
            $this->settingManager->store($row);
            return json_decode($row->getSettingValue());
        }
        return false;
    }

    public function updateAttendanceSubject(StudentsGroup $studentsGroup, Subject $subject, $quarter)
    {
        if (!$this->quarterManager->hasQuarter($quarter)){
            return false;
        }
        $students = $this->studentsGroupManager->getStudents($studentsGroup);
        $currentDCCount = 0;
        $maxDCCount = 0;
        foreach ($students as $student) {
            $row = $this->updateAttendanceStudent($student, $subject, $quarter);
            if (is_object($row)) {
                $currentDCCount += (int)$row->current;
                $maxDCCount += (int)$row->max;
            }
        }
        $row = $this->settingManager->getAttendance($quarter, $studentsGroup, $subject);
        if ($row instanceof Setting) {
            $row->setSettingValue(json_encode(['max'=>$maxDCCount, 'current'=>$currentDCCount]));
            $this->settingManager->store($row);
            return json_decode($row->getSettingValue());
        }
        return false;
    }

    public function updateAttendance(StudentsGroup $studentsGroup, $quarter)
    {
        if (!$this->quarterManager->hasQuarter($quarter)){
            return false;
        }
        $subjects = $studentsGroup->getSubjects();
        $currentDCCount = 0;
        $maxDCCount = 0;
        foreach ($subjects as $subject) {
            $row = $this->updateAttendanceSubject($studentsGroup, $subject, $quarter);
            if (is_object($row)) {
                $currentDCCount += (int)$row->current;
                $maxDCCount += (int)$row->max;
            }
        }
        $row = $this->settingManager->getAttendance($quarter, $studentsGroup);
        if ($row instanceof Setting) {
            $row->setSettingValue(json_encode(['max'=>$maxDCCount, 'current'=>$currentDCCount]));
            $this->settingManager->store($row);
            return json_decode($row->getSettingValue());
        }
        return false;
    }
}