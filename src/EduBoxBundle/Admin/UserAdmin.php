<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class UserAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'user';
    protected $baseRouteName = 'user';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'create', 'edit']);
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('username')
            ->add('enabled');
    }


}