<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class SubjectScheduleAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'edubox.admin.subject_schedule';
    protected $baseRoutePattern = 'subject_schedule';
    protected $translationDomain = 'EduBoxBundle';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();
        $collection->add('list', 'list');
        $collection->add('create', 'create');
        $collection->add('edit', 'edit/{id}');
        $collection->add('delete', 'delete/{id}');

        $collection->add('listSchedule', 'group/{id}/list');
        $collection->add('editSchedule', 'group/edit/{schedulesGroupId}/{studentsGroupId}');
    }
}