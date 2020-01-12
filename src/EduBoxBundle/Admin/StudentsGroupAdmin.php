<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class StudentsGroupAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'group';
    protected $baseRouteName = 'edubox.admin.students_group';
    protected $studentClassRepository;


    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();
        $collection->add('list', 'list');
        $collection->add('create', 'create');
        $collection->add('edit', 'edit/{id}');
        $collection->add('delete', 'delete/{id}');
    }

    protected function configureBatchActions($actions)
    {
    }
}