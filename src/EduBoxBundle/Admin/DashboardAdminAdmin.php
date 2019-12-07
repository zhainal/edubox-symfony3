<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class DashboardAdminAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'admin';
    protected $baseRouteName = 'admin';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();
        $collection->add('list', 'index');

        $collection->add('edit', 'edit');
    }
}