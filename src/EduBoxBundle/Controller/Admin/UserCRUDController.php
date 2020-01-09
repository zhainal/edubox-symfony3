<?php


namespace EduBoxBundle\Controller\Admin;


use EduBoxBundle\Form\Type\UserType;
use Sonata\AdminBundle\Controller\CRUDController;

class UserCRUDController extends CRUDController
{

    public function editAction($id = null)
    {
        $request = $this->getRequest();

        $formHandler = $this->get('edubox.user_form_handler');

        $form = $this->createForm(UserType::class);

        $formHandler->postCreateForm($form, $id);

        if ($formHandler->editHandle($form, $request)) {
            $form = $this->createForm(UserType::class);
            $formHandler->postCreateForm($form, $id);
            $this->addFlash('success', 'Saved');
        }

        return $this->renderWithExtraParams('EduBoxBundle:Admin:user/edit.html.twig', [
            'user' => $form->createView(),
        ]);
    }

    public function createAction()
    {
        $request = $this->getRequest();
        $formHandler = $this->get('edubox.user_form_handler');
        $userManager = $this->get('edubox.user_manager');
        $user = $userManager->createUser();
        $form = $this->createForm(UserType::class, $user, ['new' => true]);

        if ($user = $formHandler->createHandle($form, $request)) {
            return $this->redirectToRoute('edubox.admin.user_edit', ['id'=>$user->getId()]);
        }

        return $this->renderWithExtraParams('EduBoxBundle:Admin:user/create.html.twig', [
            'new_user_form' => $form->createView(),
        ]);
    }
}