<?php


namespace EduBoxBundle\Form\Handler;


use EduBoxBundle\DomainManager\StudentsGroupManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class StudentsGroupFormHandler
{
    private $students_group_manager;

    public function __construct(StudentsGroupManager $students_group_manager)
    {
        $this->students_group_manager = $students_group_manager;
    }

    public function createHandle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $valid_students_group = $form->getData();
            $this->students_group_manager->create($valid_students_group);
            return true;
        }
        return false;
    }

    public function editHandle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $valid_students_group = $form->getData();
            $this->students_group_manager->store($valid_students_group);
            return true;
        }
        return false;
    }

}