<?php


namespace EduBoxBundle\Form\Handler;


use EduBoxBundle\DomainManager\LessonManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class LessonFormHandler
{
    private $lessonManager;

    public function __construct(LessonManager $lessonManager)
    {
        $this->lessonManager = $lessonManager;
    }

    public function handle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $valid_lesson = $form->getData();
            $this->lessonManager->store($valid_lesson);
            return true;
        }
        return false;
    }

}