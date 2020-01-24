<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class StatisticAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'edubox.admin.statistic';
    protected $baseRoutePattern = 'statistic';
    protected $translationDomain = 'EduBoxBundle';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();

        $collection->add('list', 'index/{studentsGroupId}/{subjectId}', [
            'studentsGroupId' => null,
            'subjectId' => null,
            'quarter' => null,
        ]);
    }

}