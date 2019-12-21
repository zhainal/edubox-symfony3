<?php


namespace EduBoxBundle\Form\Handler;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\DomainManager\CalendarManager;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class CalendarFormHandler
{
    private $calendarManager;

    public function __construct(CalendarManager $calendarManager)
    {
        $this->calendarManager = $calendarManager;
    }

    public function editHandle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // validation
            $fields = [
                    'quarterOneBegin',
                    'quarterOneEnd',
                    'quarterTwoBegin',
                    'quarterTwoEnd',
                    'quarterThreeBegin',
                    'quarterThreeEnd',
                    'quarterFourBegin',
                    'quarterFourEnd',
                ];
            $year = $form->get('year')->getData();
            $lastTimestamp = 0;
            foreach ($fields as $field)
            {
                $value = $form->get($field)->getData();
                if ($value) {
                    $fieldYear = $value->format('Y');
                    $fieldTimestamp = $value->getTimestamp();
                    if ($fieldYear < $year || $fieldYear > $year+1)
                    {
                        $form->get($field)->addError(new FormError('This date not set correctly'));
                    }
                    if ($lastTimestamp > $fieldTimestamp) {
                        $form->get($field)->addError(new FormError('This date must be more previous'));
                    }
                    $lastTimestamp = $fieldTimestamp;
                }
            }

            if ($form->isValid())
            {
                $validCalendar = $form->getData();
                $this->calendarManager->store($validCalendar);
                return true;
            }
        }


        return false;
    }

    public function createHandle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $validCalendar = $form->getData();
            $this->calendarManager->create($validCalendar);
            return true;
        }

        return false;
    }
}