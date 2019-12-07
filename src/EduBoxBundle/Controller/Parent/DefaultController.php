<?php

namespace EduBoxBundle\Controller\Parent;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function listAction()
    {
        return $this->render('EduBoxBundle::stats.html.twig');
    }
}
