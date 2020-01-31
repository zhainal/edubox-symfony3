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
            $student = $this->get('edubox.parent_manager')->getActiveStudent($user, $this->getRequest());
            if (!$student instanceof User) {
                throw $this->createNotFoundException('Student not found');
            }
            $user = $student;
        }

        $diary = $this->get('edubox.diary_manager')->getDiary($user, $next);
        return $this->renderWithExtraParams('EduBoxBundle:Admin:diary/show.html.twig', [
            'diary' => $diary,
            'next' => $next,
        ]);
    }
}