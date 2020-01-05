<?php


namespace EduBoxBundle\Form\Handler;


use EduBoxBundle\DomainManager\SubjectSchedulesGroupManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class SubjectSchedulesGroupFormHandler
{
    private $subjectSchedulesGroupManager;

    public function __construct(SubjectSchedulesGroupManager $subjectSchedulesGroupManager)
    {
        $this->subjectSchedulesGroupManager = $subjectSchedulesGroupManager;
    }


    public function createHandle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $valid_subject_schedules_group = $form->getData();
            $this->subjectSchedulesGroupManager->create($valid_subject_schedules_group);
            return true;
        }
        return false;
    }

    public function editHandle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid())
        {
            $valid_subject_schedules_group = $form->getData();
            $active = $form->get('active')->getData();
            $this->subjectSchedulesGroupManager->store($valid_subject_schedules_group, $active);
            return true;
        }
        return false;
    }

}