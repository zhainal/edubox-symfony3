<?php


namespace EduBoxBundle\Form\Handler;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\DomainManager\CalendarManager;
use EduBoxBundle\Entity\Calendar;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;

class CreateCalendarFormHandler
{
    private $calendarManager;

    public function __construct(CalendarManager $calendarManager)
    {
        $this->calendarManager = $calendarManager;
    }

    public function handle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $validCalendar = $form->getData();
            $this->calendarManager->createCalendar($validCalendar);
            return true;
        }

        return false;

    }
}