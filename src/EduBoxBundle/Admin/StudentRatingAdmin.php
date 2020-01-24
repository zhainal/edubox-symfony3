<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class StudentRatingAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'edubox.admin.student_rating';
    protected $baseRoutePattern = 'rating';
    protected $translationDomain = 'EduBoxBundle';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();
        $collection->add('list', 'index');
    }

}