<?php


namespace EduBoxBundle\EventListener;


use EduBoxBundle\DomainManager\QuarterManager;
use EduBoxBundle\DomainManager\SMSManager;
use EduBoxBundle\DomainManager\StudentManager;
use EduBoxBundle\Entity\User;
use EduBoxBundle\Event\MarkCreatedEvent;

class SendMessageToParentListener
{
    private $studentManager;
    private $SMSManager;

    public function __construct(StudentManager $studentManager, SMSManager $SMSManager)
    {
        $this->studentManager = $studentManager;
        $this->SMSManager = $SMSManager;
    }

    public function onMarkCreated(MarkCreatedEvent $markCreatedEvent)
    {
        $mark = $markCreatedEvent->getMark();
        $student = $mark->getUser();
        $parent = $this->studentManager->getParent($student);
        if ($parent instanceof User) {
            $this->SMSManager->sendMsg($parent, 'Student '.$student->getFullName().' received a mark of '.$mark->getMark().' for the subject '.$mark->getSubject()->getName().' in '.$mark->getDate()->format('F j'));
        }
    }
}