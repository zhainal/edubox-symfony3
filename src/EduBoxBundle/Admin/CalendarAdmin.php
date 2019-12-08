<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class CalendarAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'calendar';
    protected $baseRouteName = 'calendar';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();
        $collection->add('list', 'index');
    }

}