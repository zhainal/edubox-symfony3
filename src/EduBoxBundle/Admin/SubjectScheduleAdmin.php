<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class SubjectScheduleAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'subject_schedule';
    protected $baseRouteName = 'subject_schedule';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();
        $collection->add('list', 'index');
    }
}