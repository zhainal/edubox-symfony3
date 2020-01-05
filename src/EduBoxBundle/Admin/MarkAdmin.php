<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class MarkAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'edubox.admin.mark';
    protected $baseRoutePattern = 'mark';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();
        $collection->add('list', 'list');
        $collection->add('editJournal', '{subjectId}/{studentsGroupId}/edit');
        $collection->add('createMark', '{subjectId}/{studentsGroupId}/add_mark');
        $collection->add('showJournal', '{subjectId}/{studentsGroupId}/show');
    }
}