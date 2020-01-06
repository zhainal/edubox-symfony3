<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class LessonAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'edubox.admin.lesson';
    protected $baseRoutePattern = 'lesson';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();

        $collection->add('list');
        $collection->add('show', '{subjectId}/{studentsGroupId}/show');
        $collection->add('edit', '{id}/edit');
    }
}