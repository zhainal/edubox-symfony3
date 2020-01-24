<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class UserAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'edubox.admin.user';
    protected $baseRoutePattern = 'user';
    protected $translationDomain = 'EduBoxBundle';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['create', 'edit']);
        $collection->add('list', 'list/{studentsGroupId}', ['studentsGroupId' => null]);
        $collection->add('show', 'show/{id}');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('fullname')
            ->add('enabled');
    }


}