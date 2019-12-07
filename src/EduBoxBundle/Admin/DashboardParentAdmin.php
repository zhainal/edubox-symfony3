<?php


namespace EduBoxBundle\Admin;


use EduBoxBundle\Controller\Admin\DefaultController;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class DashboardParentAdmin extends  AbstractAdmin
{
    protected $baseRoutePattern = 'parent';
    protected $baseRouteName = 'parent';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();
        if (true) {
            $collection->add('list');
        }
    }
}