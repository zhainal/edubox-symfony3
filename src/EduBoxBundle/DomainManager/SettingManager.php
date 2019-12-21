<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\Setting;

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
}