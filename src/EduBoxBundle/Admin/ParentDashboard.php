<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class ParentDashboard extends  AbstractAdmin
{
    protected $baseRoutePattern = 'parent';
    protected $baseRouteName = 'parent';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}