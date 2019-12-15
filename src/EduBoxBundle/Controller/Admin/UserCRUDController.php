<?php


namespace EduBoxBundle\Controller\Admin;


use EduBoxBundle\Entity\StudentClass;
use EduBoxBundle\Entity\User;
use EduBoxBundle\Entity\UserMeta;
use EduBoxBundle\Form\UserType;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Exception\LockException;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserCRUDController extends CRUDController
{

    public function editAction($id = null)
    {
        $request = $this->getRequest();

        $formHandler = $this->get('edubox.edit_user_form_handler');

        $form = $this->createForm(UserType::class);
        if (!$formHandler->postCreateForm($form, $id))
        {
            throw $this->createNotFoundException('User not found');
        }

        if ($formHandler->handle($form, $request)) {
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