<?php


namespace EduBoxBundle\Controller\Admin;


use EduBoxBundle\Entity\User;
use Sonata\AdminBundle\Controller\CRUDController;

class DiaryCRUDController extends CRUDController
{
    public function listAction($next = null)
    {
        $this->admin->checkAccess('list');
        $user = $this->getUser();
        if (!$user instanceof User) throw $this->createAccessDeniedException();

        if (!$user->hasRole('ROLE_STUDENT') && !$user->hasRole('ROLE_PARENT'))
            throw $this->createAccessDeniedException('Not enough role');

        // if the user has role parent
        if ($user->hasRole('ROLE_PARENT')) {
            $parentManager = $this->get('edubox.parent_manager');
            $studentId = $this->getRequest()->getSession()->get('_student_id');
            $student = $this->get('edubox.user_manager')->getObject($studentId);
            if (!$student instanceof User) {
                $students = $parentManager->getStudents($user);
                if ($students[0] instanceof User) $student = $students[0];
            }
            if (!$student instanceof User)
                throw $this->createNotFoundException('Student not found');
            if (!$student->hasRole('ROLE_STUDENT'))
                throw $this->createNotFoundException('Student not found');
            if (!$this->get('edubox.parent_manager')->hasStudent($user, $student))
                throw $this->createNotFoundException('Student not found');
            $user = $student;
        }

        $diary = $this->get('edubox.diary_manager')->getDiary($user, $next);
        return $this->renderWithExtraParams('EduBoxBundle:Admin:diary/show.html.twig', [
            'diary' => $diary,
            'next' => $next,
        ]);
    }
}