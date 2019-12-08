<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class NoticeAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'notice';
    protected $baseRouteName = 'notice';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();
        $collection->add('list', 'index');
    }
}