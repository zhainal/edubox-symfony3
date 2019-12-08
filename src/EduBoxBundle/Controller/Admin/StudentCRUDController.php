<?php

namespace EduBoxBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController;

class StudentCRUDController extends CRUDController
{
    public function listAction()
    {
        return $this->renderWithExtraParams('EduBoxBundle:Page:dashboard_admin.html.twig');
    }
}
