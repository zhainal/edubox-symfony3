<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class SubjectAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'edubox.admin.subject';
    protected $baseRoutePattern = 'subject';
    protected $translationDomain = 'EduBoxBundle';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();
        $collection->add('list', 'list');
        $collection->add('create', 'create');
        $collection->add('edit', 'edit/{id}');
        $collection->add('delete', 'delete/{id}');
    }
}