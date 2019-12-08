<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class CallScheduleAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'call_schedule';
    protected $baseRouteName = 'call_schedule';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();
        $collection->add('list', 'index');
    }
}