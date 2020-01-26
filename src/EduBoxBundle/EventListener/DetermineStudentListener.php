<?php


namespace EduBoxBundle\EventListener;


use EduBoxBundle\DomainManager\UserManager;
use EduBoxBundle\Entity\User;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class DetermineStudentListener
{
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $userId = $request->get('_student_id');
        $user = $this->userManager->getObject($userId);
        if ($user instanceof User) {
            if ($user->hasRole('ROLE_STUDENT')) {
                $request->getSession()->set('_student_id', $user->getId());
            }
        }
    }
}