<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\Calendar;

class CalendarManager
{
    private $entityManager;
    private $settingManager;

    public function __construct(EntityManager $entityManager, SettingManager $settingManager)
    {
        $this->entityManager = $entityManager;
        $this->settingManager = $settingManager;
    }

    public function create(Calendar $calendar)
    {
        $this->entityManager->persist($calendar);
        $this->entityManager->flush();
    }

    public function store(Calendar $calendar)
    {
        $this->entityManager->flush();
    }

    /**
     * @return Calendar|null
     * @throws \Exception
     */
    public function getActiveCalendar()
    {
        return $this->settingManager->getCalendar();
    }
}