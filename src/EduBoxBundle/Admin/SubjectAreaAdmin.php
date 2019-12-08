<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class SubjectAreaAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'subject';
    protected $baseRouteName = 'subject';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();
        $collection->add('list', 'index');
    }
}