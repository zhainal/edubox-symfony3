<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class DiaryAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'edubox.admin.diary';
    protected $baseRoutePattern = 'diary';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();
        $collection->add('list', 'show/{next}', ['next' => null]);
    }
}