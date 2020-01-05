<?php


namespace EduBoxBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends Controller
{
    /**
     * @Route(path="/", name="homepage")
     */
    public function homeAction()
    {
        return $this->render('EduBoxBundle:Page:home.html.twig');
    }
}