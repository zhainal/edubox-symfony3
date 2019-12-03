<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class StudentDashboard extends  AbstractAdmin
{
    protected $baseRoutePattern = 'student';
    protected $baseRouteName = 'student';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}