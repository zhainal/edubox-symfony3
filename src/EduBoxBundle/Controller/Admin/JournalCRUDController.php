<?php


namespace EduBoxBundle\Controller\Admin;


use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\Subject;
use EduBoxBundle\Entity\User;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\JsonResponse;

class JournalCRUDController extends CRUDController
{
    public function listAction()
    {
        $this->admin->checkAccess('list');
        $user = $this->getUser();
        if (!$user instanceof User) throw $this->createAccessDeniedException();
        if (!$user->hasRole('ROLE_TEACHER')) throw $this->createAccessDeniedException();
        $teacher_manager = $this->get('edubox.teacher_manager');
        $subjects = $teacher_manager->getSubjects($user);
        return $this->renderWithExtraParams('EduBoxBundle:Admin:journal/list.html.twig', [
            'subjects' => $subjects,
        ]);
    }

    public function showAction($studentsGroupId = null, $subjectId = null, $quarter = null)
    {
        $this->admin->checkAccess('show');
        $user = $this->getUser();
        if (!$user instanceof User) throw $this->createAccessDeniedException();
        if (!$user->hasRole('ROLE_TEACHER')) throw $this->createAccessDeniedException();

        $em = $this->get('doctrine.orm.entity_manager');
        $subject = $em->getRepository(Subject::class)->find($subjectId);
        if (!$subject instanceof Subject)
        {
            throw $this->createNotFoundException('Subject not found');
        }
        $studentsGroup = $em->getRepository(StudentsGroup::class)->find($studentsGroupId);
        if (!$studentsGroup instanceof StudentsGroup)
        {
            throw $this->createNotFoundException('Students group not found');
        }
        $quarter_manager = $this->get('edubox.quarter_manager');
        $quarter = $quarter_manager->getQuarter($quarter);

        $students_group_manager = $this->get('edubox.students_group_manager');
        $students = $students_group_manager->getStudents($studentsGroup);

        $mark_manager = $this->get('edubox.mark_manager');
        $marks = $mark_manager->getMarks($subject, $studentsGroup, $quarter);
        $averages = $quarter_manager->formatResult($quarter_manager->getAverages($subject, $students, $quarter));
        $dates = $mark_manager->getDatesTree($subject, $studentsGroup, $quarter);

        return $this->renderWithExtraParams('EduBoxBundle:Admin:journal/show.html.twig', [
            'subject' => $subject,
            'studentsGroup' => $studentsGroup,
            'students' => $students,
            'averages' => $averages,
            'quarterNumber' => $quarter,
            'marks' => $marks,
            'dates' => $dates,
            'quarter' => $quarter,
        ]);
    }

    public function editAction(
        $subjectId = null,
        $studentsGroupId = null
    ) {
        $this->admin->checkAccess('edit');
        $user = $this->getUser();
        if (!$user instanceof User) throw $this->createAccessDeniedException();
        if (!$user->hasRole('ROLE_TEACHER')) throw $this->createAccessDeniedException();

        $request = $this->getRequest();
        $mark = $request->get('mark');
        $date = $request->get('date');
        $hour = $request->get('hour');
        $comment = $request->get('comment');
        $userId = $request->get('userId');

        $user = $this->get('edubox.user_manager')->getObject($userId);
        if (!$user instanceof User) {
            throw $this->createNotFoundException('User not found');
        }
        elseif (!$user->hasRole('ROLE_STUDENT')) {
            throw $this->createNotFoundException('Given user does not student');
        }
        $subject = $this->get('edubox.subject_manager')->getObject($subjectId);
        if (!$subject instanceof Subject) {
            throw $this->createNotFoundException('Subject not found');
        }
        $studentsGroupRepository = $this->get('edubox.students_group_manager');
        $studentsGroup = $studentsGroupRepository->getObject($studentsGroupId);
        if (!$studentsGroup instanceof StudentsGroup) {
            throw $this->createNotFoundException('Students group not found');
        }
        $markManager = $this->get('edubox.mark_manager');
        $quarterManager = $this->get('edubox.quarter_manager');
        try {
            $date = new \DateTime($date);
            $quarter = $quarterManager->getQuarterByDate($date);
        }
        catch (\Exception $exception) {
            throw $this->createNotFoundException('Given date incorrect');
        }
        if ( !$markManager->hasSourceMark($mark) || !$markManager->hasDateHour($subject, $studentsGroup, $quarter, $date, $hour) ) {
            throw $this->createNotFoundException('Some attributes incorrect');
        }
        $students = $studentsGroupRepository->getStudents($studentsGroup);
        return new JsonResponse(
            round($this->get('edubox.mark_manager')->createMark($subject, $user, $mark, $date, $hour, $comment))
        );
    }
}