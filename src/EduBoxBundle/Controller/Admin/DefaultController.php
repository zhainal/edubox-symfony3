<?php

namespace EduBoxBundle\Controller\Admin;

use EduBoxBundle\Admin\DashboardAdminAdmin;
use EduBoxBundle\EduBoxBundle;
use EduBoxBundle\Entity\User;
use Sonata\AdminBundle\Controller\CRUDController;

class DefaultController extends CRUDController
{
    public function indexAction()
    {
        return $this->renderWithExtraParams('EduBoxBundle::stats.html.twig');
    }


    public function listAction()
    {
        $user = $this->getUser();
        $form = $this->createFormBuilder($user)->getForm();
        $form->add('username');

        return $this->renderWithExtraParams('@SonataAdmin/CRUD/edit.html.twig', [
            'form' => $form->createView(),
            'object' => $user,
            'action' => 'list',
        ]);
    }
}
