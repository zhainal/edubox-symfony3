<?php


namespace EduBoxBundle\Admin;


use Doctrine\DBAL\Types\TextType;
use EduBoxBundle\Entity\Homework;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class LessonAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'edubox.admin.lesson';
    protected $baseRoutePattern = 'lesson';
    protected $translationDomain = 'EduBoxBundle';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();

        $collection->add('list');
        $collection->add('listLesson', 'list/lessons/{subjectId}/{studentsGroupId}/{quarter}', ['subjectId' => null,'studentsGroupId' => null, 'quarter' => null]);
        $collection->add('edit', '{id}/edit');
        $collection->add('show', '{id}/show');
    }
}