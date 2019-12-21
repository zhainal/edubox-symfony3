<?php


namespace EduBoxBundle\Form\Handler;


use EduBoxBundle\DomainManager\SubjectAreaManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class SubjectAreaFormHandler
{
    private $subjectAreaManager;

    public function __construct(SubjectAreaManager $subjectAreaManager)
    {
        $this->subjectAreaManager = $subjectAreaManager;
    }


    public function createHandle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $valid_subject_area = $form->getData();
            $this->subjectAreaManager->create($valid_subject_area);
            return true;
        }
        return false;
    }

    public function editHandle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $valid_subject_area = $form->getData();
            $this->subjectAreaManager->store($valid_subject_area);
            return true;
        }
        return false;
    }

}