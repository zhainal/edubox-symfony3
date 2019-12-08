<?php

namespace EduBoxBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController;

class TeacherCRUDController extends CRUDController
{
    public function listAction()
    {
        return $this->renderWithExtraParams('EduBoxBundle:Page:dashboard_admin.html.twig');
    }
}
