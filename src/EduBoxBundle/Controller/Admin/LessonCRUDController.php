<?php


namespace EduBoxBundle\Controller\Admin;


use EduBoxBundle\Entity\Lesson;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\Subject;
use EduBoxBundle\Entity\User;
use EduBoxBundle\Form\Type\LessonFormType;
use Sonata\AdminBundle\Controller\CRUDController;

class LessonCRUDController extends CRUDController
{
    public function listAction()
    {
        $this->admin->checkAccess('list');
        $request = $this->getRequest();

        if ($this->isGranted('ROLE_TEACHER')) {
            return $this->teacherLessonSubjectList();
        }
        elseif ($this->isGranted('ROLE_STUDENT')) {
            return $this->studentLessonSubjectList();
        }
        elseif ($this->isGranted('ROLE_PARENT')) {
            return $this->parentLessonSubjectList((int)$request->get('student'));
        }
        else {
            throw $this->createAccessDeniedException();
        }
    }

    public function listLessonAction($subjectId, $studentsGroupId, $quarter)
    {
        $this->admin->checkAccess('list');
        $subject = $this->get('edubox.subject_manager')->getObject($subjectId);
        $studentsGroup = $this->get('edubox.students_group_manager')->getObject($studentsGroupId);
        $user = $this->getUser();
        if (!$subject instanceof Subject || !$studentsGroup instanceof StudentsGroup || !$user instanceof User) {
            throw $this->createNotFoundException('Subject or students group or user not found');
        }
        if (!$subject->hasStudentsGroup($studentsGroup)) {
            throw $this->createNotFoundException('Students group with that subject not found');
        }
        $quarter = $this->get('edubox.quarter_manager')->getQuarter($quarter);
        $this->checkAccessLesson($user, $studentsGroup, $subject);

        $lessons = $this->get('edubox.lesson_manager')->getLessonsByQuarter($subject, $studentsGroup, $quarter);
        return $this->renderWithExtraParams('EduBoxBundle:Admin:lesson/list.html.twig', [
            'lessons' => $lessons,
            'studentsGroup' => $studentsGroup,
            'subject' => $subject,
            'quarter' => $quarter,
        ]);
    }

    public function editAction($id = null)
    {
        $this->admin->checkAccess('edit');
        $lesson = $this->get('edubox.lesson_manager')->getObject($id);
        if ($lesson == null) {
            throw $this->createNotFoundException('Lesson not found');
        }
        $subject = $lesson->getSubject();
        $studentsGroup = $lesson->getStudentsGroup();
        $user = $this->getUser();
        if (!$subject instanceof Subject || !$studentsGroup instanceof StudentsGroup || !$user instanceof User) {
            throw $this->createNotFoundException('Subject or students group or user not found');
        }
        $this->checkAccessLesson($user, $studentsGroup, $subject);
        
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
        $subject = $lesson->getSubject();
        $studentsGroup = $lesson->getStudentsGroup();
        $user = $this->getUser();
        if (!$subject instanceof Subject || !$studentsGroup instanceof StudentsGroup || !$user instanceof User) {
            throw $this->createNotFoundException('Subject or students group or user not found');
        }
        $this->checkAccessLesson($user, $studentsGroup, $subject);

        return $this->renderWithExtraParams('EduBoxBundle:Admin:lesson/show.html.twig', [
            'lesson' => $lesson,
        ]);
    }

    private function parentLessonSubjectList($studentId)
    {
        $parent = $this->getUser();
        if (!$parent instanceof User) {
            throw $this->createAccessDeniedException();
        }
        $parentManager = $this->get('edubox.parent_manager');
        $student = $parentManager->getStudent($parent, $studentId);
        if ($student instanceof User) {
            $subjects = $this->get('edubox.student_manager')->getSubjects($student);
            return $this->renderWithExtraParams('EduBoxBundle:Admin:lesson/teacher/subject_list.html.twig', [
                'subjects' => $subjects
            ]);
        }
        $students = $parentManager->getStudents($parent);
        return $this->renderWithExtraParams('EduBoxBundle:Admin:parent/student_list.html.twig', [
            'route' => 'edubox.admin.lesson_list',
            'students' => $students
        ]);
    }

    private function teacherLessonSubjectList()
    {
        $teacher = $this->getUser();
        if (!$teacher instanceof User) {
            throw $this->createAccessDeniedException();
        }
        $subjects = $this->get('edubox.teacher_manager')->getSubjects($teacher);
        return $this->renderWithExtraParams('EduBoxBundle:Admin:lesson/teacher/subject_list.html.twig', [
            'subjects' => $subjects
        ]);
    }

    private function studentLessonSubjectList()
    {
        $student = $this->getUser();
        if (!$student instanceof User) {
            throw $this->createAccessDeniedException();
        }
        $studentsGroup = $this->get('edubox.student_manager')->getStudentsGroup($student);
        $subjects = $this->get('edubox.student_manager')->getSubjects($student);
        return $this->renderWithExtraParams('EduBoxBundle:Admin:lesson/student/subject_list.html.twig', [
            'subjects' => $subjects,
            'studentsGroup' => $studentsGroup,
        ]);
    }

    private function checkAccessTeacher(User $parent, StudentsGroup $studentsGroup, Subject $subject)
    {
        if (!$this->get('edubox.teacher_manager')->hasStudentsGroup($parent, $studentsGroup, $subject)) {
            throw $this->createAccessDeniedException('The teacher does not have the students group or the subject');
        }
    }

    private function checkAccessStudent(User $student, StudentsGroup $studentsGroup)
    {
        if (!$this->get('edubox.student_manager')->hasStudentsGroup($student, $studentsGroup)) {
            throw $this->createAccessDeniedException('The student does not have the students group');
        }
    }

    private function checkAccessParent(User $parent, StudentsGroup $studentsGroup)
    {
        if (!$this->get('edubox.parent_manager')->hasStudentsGroup($parent, $studentsGroup)) {
            throw $this->createAccessDeniedException('The parent does not have an access to students group');
        }
    }

    private function checkAccessLesson(User $user, StudentsGroup $studentsGroup, Subject $subject)
    {
        if ($this->isGranted('ROLE_TEACHER')) {
            $this->checkAccessTeacher($user, $studentsGroup, $subject);
        }
        elseif ($this->isGranted('ROLE_STUDENT')) {
            $this->checkAccessStudent($user, $studentsGroup);
        }
        elseif ($this->isGranted('ROLE_PARENT')) {
            $this->checkAccessParent($user, $studentsGroup);
        }
        else {
            throw $this->createAccessDeniedException();
        }
    }

}