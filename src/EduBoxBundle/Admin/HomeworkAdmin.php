<?php


namespace EduBoxBundle\Admin;


use Doctrine\ORM\EntityRepository;
use EduBoxBundle\Entity\Lesson;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class HomeworkAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'edubox.admin.homework';
    protected $baseRoutePattern = 'homework';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clear();

        $collection->add('list');
        $collection->add('create');
        $collection->add('edit');
        $collection->add('show', '{id}/show');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name');
        $list->add('lesson.name');
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form->add('name');
        $form->add('lesson', EntityType::class, [
            'class' => Lesson::class,
            'choice_label' => 'nameWithDate',
            'query_builder' => function(EntityRepository $qb) {
                $qb = $qb->createQueryBuilder('l');
                return $qb
                    ->andWhere('TRIM(l.name) <> '.$qb->expr()->literal(''))
                    ->andWhere($qb->expr()->isNotNull('l.name'))
                    ->andWhere($qb->expr()->isNotNull('l.date'));
            }
        ]);
        $form->add('content');
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('name');
        $show->add('lesson', EntityType::class, [
            'class' => Lesson::class,
            'choice_label' => 'nameWithDate',
        ]);
        $show->add('content', TextareaType::class);
    }

}