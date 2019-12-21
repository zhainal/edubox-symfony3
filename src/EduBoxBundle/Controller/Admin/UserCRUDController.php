<?php


namespace EduBoxBundle\Controller\Admin;


use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Form\Type\UserType;
use Sonata\AdminBundle\Controller\CRUDController;

class UserCRUDController extends CRUDController
{

    public function editAction($id = null)
    {
        $request = $this->getRequest();

        $formHandler = $this->get('edubox.user_form_handler');

        $form = $this->createForm(UserType::class);
        if (!$formHandler->postCreateForm($form, $id))
        {
            throw $this->createNotFoundException('User not found');
        }

        if ($formHandler->editHandle($form, $request)) {
            $this->addFlash('success', 'Saved');
        }

        return $this->renderWithExtraParams('EduBoxBundle:Admin:user_edit.html.twig', [
            'user' => $form->createView(),
        ]);
    }

    public function createAction()
    {

    }
}