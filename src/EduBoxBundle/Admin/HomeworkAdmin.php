<?php


namespace EduBoxBundle\Admin;


use Doctrine\ORM\EntityRepository;
use EduBoxBundle\Entity\Lesson;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
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
    protected $translationDomain = 'EduBoxBundle';

    protected function configureRoutes(RouteCollection $collection)
    {
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name', null, ['translation_domain' => 'forms', 'label' => 'homework.name']);
        $list->add('lesson.name', null, ['translation_domain' => 'forms', 'label' => 'homework.lesson']);
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form->add('name', null, ['label' => 'homework.name'], ['translation_domain' => 'forms']);
        $form->add('lesson', EntityType::class, [
            'label' => 'homework.lesson',
            'class' => Lesson::class,
            'choice_label' => 'nameWithDate',
            'query_builder' => function(EntityRepository $qb) {
                $qb = $qb->createQueryBuilder('l');
                return $qb
                    ->andWhere('TRIM(l.name) <> '.$qb->expr()->literal(''))
                    ->andWhere($qb->expr()->isNotNull('l.name'))
                    ->andWhere($qb->expr()->isNotNull('l.date'));
            }
        ], ['translation_domain' => 'forms']);
        $form->add('content', CKEditorType::class, [
            'config' => array('toolbar' => 'standard'),
            'label' => 'homework.content'
        ], [
            'translation_domain' => 'forms'
        ]);
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('name', null, ['label' => 'homework.name', 'translation_domain' => 'forms']);
        $show->add('lesson', EntityType::class, [
            'label' => 'homework.lesson',
            'translation_domain' => 'forms',
            'class' => Lesson::class,
            'choice_label' => 'nameWithDate',
        ]);
        $show->add('content', TextareaType::class, ['label' => 'homework.content', 'translation_domain' => 'forms']);
    }

}