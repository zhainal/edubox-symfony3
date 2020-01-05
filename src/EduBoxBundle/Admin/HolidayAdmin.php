<?php


namespace EduBoxBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class HolidayAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'edubox.admin.holiday';
    protected $baseRoutePattern = 'holiday';


    public function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('name')
            ->add('begin')
            ->add('end')
            ->add('moved');
    }

    public function configureFormFields(FormMapper $form)
    {
        $form
            ->add('name')
            ->add('begin')
            ->add('end')
            ->add('moved');
    }

}