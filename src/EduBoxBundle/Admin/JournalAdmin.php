<?php


namespace EduBoxBundle\Admin;


use EduBoxBundle\Entity\StudentsGroup;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class JournalAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'edubox.admin.journal';
    protected $baseRoutePattern = 'journal';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();
        $collection->add('list', 'list');
        $collection->add('show', '{studentsGroupId}/{subjectId}/show/{quarter}', ['quarter' => null]);
        $collection->add('edit', '{studentsGroupId}/{subjectId}/edit');
    }

}