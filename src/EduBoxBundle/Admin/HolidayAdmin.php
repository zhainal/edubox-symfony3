<?php


namespace EduBoxBundle\Admin;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class HolidayAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'edubox.admin.holiday';
    protected $baseRoutePattern = 'holiday';
    protected $translationDomain = 'EduBoxBundle';


    public function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('name', null, ['label' => 'holiday.name', 'translation_domain' => 'forms'])
            ->add('begin', null,  ['label' => 'holiday.begin', 'translation_domain' => 'forms'])
            ->add('end', null,  ['label' => 'holiday.end', 'translation_domain' => 'forms'])
            ->add('moved', null,  ['label' => 'holiday.moved', 'translation_domain' => 'forms']);
    }

    public function configureFormFields(FormMapper $form)
    {
        $form
            ->add('name', TextType::class, ['label' => 'holiday.name'], ['translation_domain' => 'forms'])
            ->add('begin', null, ['label' => 'holiday.begin'], ['translation_domain' => 'forms'])
            ->add('end', null, ['label' => 'holiday.end'], ['translation_domain' => 'forms'])
            ->add('moved', null, ['label' => 'holiday.moved'], ['translation_domain' => 'forms']);
    }

}