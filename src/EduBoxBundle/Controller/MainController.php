<?php


namespace EduBoxBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function homeAction()
    {
        return $this->render('EduBoxBundle::home.html.twig');
    }


}