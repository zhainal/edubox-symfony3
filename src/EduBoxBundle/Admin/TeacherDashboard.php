<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class TeacherDashboard extends  AbstractAdmin
{
    protected $baseRoutePattern = 'teacher';
    protected $baseRouteName = 'teacher';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}