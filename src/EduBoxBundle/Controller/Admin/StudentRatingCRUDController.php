<?php


namespace EduBoxBundle\Controller\Admin;


use EduBoxBundle\Entity\User;
use Sonata\AdminBundle\Controller\CRUDController;

class StudentRatingCRUDController extends CRUDController
{
    public function listAction()
    {
        $students = $this->get('edubox.student_manager')->getRating();
        return $this->renderWithExtraParams('EduBoxBundle:Admin:student_rating/list.html.twig', [
            'students' => $students
        ]);
    }

}