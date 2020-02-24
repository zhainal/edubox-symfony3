<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\SubjectSchedule;
use EduBoxBundle\Entity\SubjectSchedulesGroup;

class SubjectSchedulesGroupManager
{
    private $entityManager;
    private $settingManager;

    public function __construct(EntityManager $entityManager, SettingManager $settingManager)
    {
        $this->entityManager = $entityManager;
        $this->settingManager = $settingManager;
    }

    public function create(SubjectSchedulesGroup $schedulesGroup)
    {
        $this->entityManager->persist($schedulesGroup);
        $this->entityManager->flush();
    }

    public function store(SubjectSchedulesGroup $schedulesGroup, $active = false)
    {
        $this->entityManager->flush();
        $setting = $this->settingManager->getSetting('subjectSchedulesGroup');
        if ($active) {
            $setting->setSettingValue($schedulesGroup->getId());
        }
        elseif ($setting->getSettingValue() == $schedulesGroup->getId()) {
            $setting->setSettingValue(null);
        }
        $this->settingManager->store($setting);
    }

    public function getStatus(SubjectSchedulesGroup $subjectSchedulesGroup)
    {
        return '-';
    }

    public function getActiveGroup()
    {
        return $this->settingManager->getSubjectSchedulesGroup();
    }

    public function getSchedule(SubjectSchedulesGroup $subjectSchedulesGroup, StudentsGroup $studentsGroup)
    {
        $repository = $this->entityManager->getRepository(SubjectSchedule::class);
        $schedule = $repository->createQueryBuilder('s');

        $schedule = $schedule
            ->where('s.studentsGroup = :studentsGroup')
            ->andWhere('s.subjectSchedulesGroup = :subjectSchedulesGroup')
            ->setParameter('studentsGroup', $studentsGroup)
            ->setParameter('subjectSchedulesGroup', $subjectSchedulesGroup)
            ->getQuery()
            ->getResult();
        return isset($schedule[0]) ? $schedule[0] : false;
    }



}