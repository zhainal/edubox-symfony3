<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\UserMeta;

class UserMetaManager
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(UserMeta $userMeta)
    {
        $this->entityManager->persist($userMeta);
        $this->entityManager->flush();
    }

    public function store(UserMeta $userMeta)
    {
        $this->entityManager->flush();
    }
}