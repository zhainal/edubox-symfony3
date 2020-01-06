<?php


namespace EduBoxBundle\Controller\Admin;


use EduBoxBundle\Form\Type\LessonFormType;
use Sonata\AdminBundle\Controller\CRUDController;

class LessonCRUDController extends CRUDController
{
    public function listAction()
    {
        $user = $this->getUser();
        $subjects = $this->get('edubox.teacher_manager')->getSubjects($user);
        return $this->renderWithExtraParams('EduBoxBundle:Admin:lesson/list.html.twig', ['subjects' => $subjects]);
    }

    public function showAction($subjectId = null, $studentsGroupId = null, $quarter = null)
    {
        $subjectManager = $this->get('edubox.subject_manager');
        $studentsGroupManager = $this->get('edubox.students_group_manager');
        if (($subject = $subjectManager->getObject($subjectId)) != null && ($studentsGroup = $studentsGroupManager->getObject($studentsGroupId)) != null) {
            $lessonManager = $this->get('edubox.lesson_manager');
            $lessons = $lessonManager->getLessonsByQuarter($subject, $studentsGroup, $quarter);
            return $this->renderWithExtraParams('EduBoxBundle:Admin:lesson/show.html.twig', ['lessons' => $lessons]);
        }
        else {
            $this->createNotFoundException('Subject or Students group does not exists');
        }
    }

    public function editAction($id = null)
    {
        $lesson = $this->get('edubox.lesson_manager')->getObject($id);
        if ($lesson == null) {
            throw $this->createNotFoundException('Lesson not found');
        }
        $form = $this->createForm(LessonFormType::class, $lesson);
        $formHandler = $this->get('edubox.lesson_form_handler');
        if ($formHandler->handle($form, $this->getRequest())) {
            $this->addFlash('success', 'Lesson saved');
        }
        return $this->renderWithExtraParams('EduBoxBundle:Admin:lesson/edit.html.twig', [
            'form' => $form->createView(),
            'lesson' => $lesson,
        ]);
    }
}