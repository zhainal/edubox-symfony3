<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\User;
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

    public function getValue(User $user, $metaKey)
    {
        $repository = $this->entityManager->getRepository(UserMeta::class);
        $userMeta = $repository->findOneBy(['user' => $user, 'metaKey' => $metaKey]);
        return $userMeta instanceof UserMeta ? $userMeta->getMetaValue() : null;
    }

    public function toObject($id, $class)
    {
        $repository = $this->entityManager->getRepository($class);
        if ($id == null) {
            return null;
        }
        return $repository->find($id);
    }

    public function getStudentsGroup(User $user)
    {
        $id = $this->getValue($user, UserMeta::STUDENT_GROUP_ID);
        return $this->toObject($id, StudentsGroup::class);
    }
}