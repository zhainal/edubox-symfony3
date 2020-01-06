<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\Calendar;
use EduBoxBundle\Entity\Setting;
use EduBoxBundle\Entity\SubjectSchedulesGroup;

class SettingManager
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(Setting $setting)
    {
        $this->entityManager->persist($setting);
        $this->entityManager->flush();
    }

    public function store(Setting $setting)
    {
        $this->entityManager->flush();
    }

    public function getKeys()
    {
        return array(
            'shortName' => 'short_name',
            'fullName' => 'full_name',
            'address' => 'address',
            'phone' => 'phone',
            'email' => 'email',
            'director' => 'director',
            'calendar' => 'calendar_id',
            'subjectSchedulesGroup' => 'subject_schedules_group_id',
        );
    }


    public function getSetting($name)
    {
        $keys = $this->getKeys();
        if (in_array($name, array_keys($keys))) {
            $repository = $this->entityManager->getRepository(Setting::class);
            return $repository->findOneByOrCreate(['settingKey'=>$keys[$name]]);
        }
        return null;
    }

    /**
     * @return Calendar|null
     * @throws \Exception
     */
    public function getCalendar()
    {
        $calendar_id = $this->getSetting('calendar')->getSettingValue();
        if ($calendar_id) {
            $calendarRepository = $this->entityManager->getRepository(Calendar::class);
            $calendar = $calendarRepository->find($calendar_id);
            if ($calendar) {
                return $calendar;
            }
        }
        throw new \Exception('The organization has not yet selected a calendar, please contact the administration.');
    }

    public function getSubjectSchedulesGroup()
    {
        $group_id = $this->getSetting('subjectSchedulesGroup')->getSettingValue();
        if ($group_id) {
            $groupRepository = $this->entityManager->getRepository(SubjectSchedulesGroup::class);
            $group = $groupRepository->find($group_id);
            if ($group) {
                return $group;
            }
        }
        throw new \Exception('The organization has not yet selected a subject schedules group, please contact the administration.');
    }

}