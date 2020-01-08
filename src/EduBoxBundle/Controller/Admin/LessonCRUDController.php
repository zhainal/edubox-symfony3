<?php


namespace EduBoxBundle\Controller\Admin;


use EduBoxBundle\Entity\Lesson;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\User;
use EduBoxBundle\Form\Type\LessonFormType;
use Sonata\AdminBundle\Controller\CRUDController;

class LessonCRUDController extends CRUDController
{
    public function listAction()
    {
        $this->admin->checkAccess('list');
        if ($this->isGranted('ROLE_TEACHER')) {
            return $this->listTeacher();
        }
        elseif ($this->isGranted('ROLE_STUDENT')) {
            return $this->listStudent();
        }
    }

    public function listTeacher()
    {
        $user = $this->getUser();
        $subjects = $this->get('edubox.teacher_manager')->getSubjects($user);
        return $this->renderWithExtraParams('EduBoxBundle:Admin:lesson/list.html.twig', ['subjects' => $subjects]);
    }

    public function listStudent()
    {
        $user = $this->getUser();
        $studentManager = $this->get('edubox.student_manager');
        $studentsGroup = $studentManager->getStudentsGroup($user);
        if (!$studentsGroup instanceof StudentsGroup) {
            throw new \Exception('You don\'t have a students group');
        }
        $subjects = $studentManager->getSubjects($user);
        return $this->renderWithExtraParams('EduBoxBundle:Admin:lesson/student/list.html.twig', [
            'subjects' => $subjects,
            'studentsGroup' => $studentsGroup,
        ]);
    }


    public function listLessonAction($quarter = null, $subjectId = null, $studentsGroupId = null)
    {
        $this->admin->checkAccess('list');
        if ($this->isGranted('ROLE_TEACHER')) {
            return $this->listLessonTeacher($subjectId, $studentsGroupId, $quarter);
        }
        elseif ($this->isGranted('ROLE_STUDENT')) {
            return $this->listLessonStudent($subjectId, $quarter);
        }
    }

    public function listLessonTeacher($subjectId, $studentsGroupId, $quarter)
    {
        $subjectManager = $this->get('edubox.subject_manager');
        $studentsGroupManager = $this->get('edubox.students_group_manager');
        if (($subject = $subjectManager->getObject($subjectId)) != null && ($studentsGroup = $studentsGroupManager->getObject($studentsGroupId)) != null) {
            $lessonManager = $this->get('edubox.lesson_manager');
            $lessons = $lessonManager->getLessonsByQuarter($subject, $studentsGroup, $quarter);
            return $this->renderWithExtraParams('EduBoxBundle:Admin:lesson/list_lesson.html.twig', [
                'lessons' => $lessons,
                'studentsGroup' => $studentsGroup,
                'subject' => $subject,
            ]);
        }
        else {
            $this->createNotFoundException('Subject or Students group does not exists');
        }
    }

    public function listLessonStudent($subjectId, $quarter)
    {
        $user = $this->getUser();
        $subjectManager = $this->get('edubox.subject_manager');
        $studentManager = $this->get('edubox.student_manager');
        if (($subject = $subjectManager->getObject($subjectId)) != null && ($studentsGroup = $studentManager->getStudentsGroup($user)) != null) {
            $lessonManager = $this->get('edubox.lesson_manager');
            $lessons = $lessonManager->getLessonsByQuarter($subject, $studentsGroup, $quarter);
            return $this->renderWithExtraParams('EduBoxBundle:Admin:lesson/student/list_lesson.html.twig', [
                'lessons' => $lessons,
                'subject' => $subject,
            ]);
        }
        else {
            $this->createNotFoundException('Subject or Students group does not exists');
        }
    }


    public function editAction($id = null)
    {
        $this->admin->checkAccess('edit');
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

    public function showAction($id = null)
    {
        $this->admin->checkAccess('show');
        $lesson = $this->get('edubox.lesson_manager')->getObject($id);
        if (!$lesson instanceof Lesson) {
            $this->createNotFoundException('Lesson not found');
        }
        if ($this->isGranted('ROLE_TEACHER')) {
            return $this->showTeacher($lesson);
        }
        elseif ($this->isGranted('ROLE_STUDENT')) {
            return $this->showStudent($lesson);
        }
    }

    public function showTeacher(Lesson $lesson)
    {
        return $this->renderWithExtraParams('EduBoxBundle:Admin:lesson/show.html.twig', [
            'lesson' => $lesson,
        ]);
    }

    public function showStudent(Lesson $lesson)
    {
        return $this->renderWithExtraParams('EduBoxBundle:Admin:lesson/student/show.html.twig', [
            'lesson' => $lesson,
        ]);
    }
}