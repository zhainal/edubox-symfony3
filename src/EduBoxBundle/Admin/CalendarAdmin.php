<?php


namespace EduBoxBundle\Admin;


use Doctrine\DBAL\Types\TextType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class CalendarAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'calendar';
    protected $baseRouteName = 'edubox.admin.calendar';
    protected $translationDomain = 'EduBoxBundle';

    protected function configureRoutes(RouteCollection $collection)
    {
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('year', TextType::class, ['label' => 'calendar.year', 'translation_domain' => 'forms']);
    }



}