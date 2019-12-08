<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class MessageAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'message';
    protected $baseRouteName = 'message';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();
        $collection->add('list', 'index');
    }
}