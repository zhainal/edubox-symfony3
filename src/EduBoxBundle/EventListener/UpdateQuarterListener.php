<?php


namespace EduBoxBundle\EventListener;


use EduBoxBundle\DomainManager\QuarterManager;
use EduBoxBundle\Event\MarkCreatedEvent;

class UpdateQuarterListener
{
    private $quarterManager;

    public function __construct(QuarterManager $quarterManager)
    {
        $this->quarterManager = $quarterManager;
    }

    public function onMarkCreated(MarkCreatedEvent $markCreatedEvent)
    {
        $mark = $markCreatedEvent->getMark();
        $quarter = $this->quarterManager->getQuarterByDate($mark->getDate());
        $this->quarterManager->updateQuarter($mark->getSubject(), $mark->getUser(), $quarter);
    }

}