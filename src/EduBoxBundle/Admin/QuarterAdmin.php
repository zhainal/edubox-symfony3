<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class QuarterAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'edubox.admin.quarter';
    protected $baseRoutePattern = 'quarter';
    protected $translationDomain = 'EduBoxBundle';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();

        $collection->add('list', 'list/{studentsGroupId}', ['studentsGroupId' => null]);
        $collection->add('show', 'show/{studentId}', ['studentId' => null]);
    }


}