<?php


namespace EduBoxBundle\Form\Handler;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\DomainManager\CalendarManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class EditCalendarFormHandler
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
            $this->calendarManager->storeCalendar($validCalendar);
            return true;
        }
        return false;
    }
}