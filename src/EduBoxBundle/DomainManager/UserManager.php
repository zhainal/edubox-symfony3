<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\User;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class UserManager
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createUser()
    {
        $user = new User();
        $user->setEnabled(true);
        $this->entityManager->persist($user);
        return $user;
    }

    public function store(User $user)
    {
        $this->entityManager->flush();
    }
}