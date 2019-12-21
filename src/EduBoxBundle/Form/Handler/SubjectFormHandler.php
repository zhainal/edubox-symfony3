<?php


namespace EduBoxBundle\Form\Handler;


use EduBoxBundle\DomainManager\SubjectManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class SubjectFormHandler
{
    private $subjectManger;

    public function __construct(SubjectManager $subjectManager)
    {
        $this->subjectManger = $subjectManager;
    }


    public function createHandle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $valid_subject = $form->getData();
            $this->subjectManger->create($valid_subject);
            return true;
        }
        return false;
    }

    public function editHandle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $valid_subject = $form->getData();
            $this->subjectManger->store($valid_subject);
            return true;
        }
        return false;
    }

}