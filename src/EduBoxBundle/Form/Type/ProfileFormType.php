<?php


namespace EduBoxBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', null, ['label' => 'user.phone', 'translation_domain' => 'forms'])
            ->add('profilePictureFile', null, ['label' => 'user.picture', 'translation_domain' => 'forms']);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getName()
    {
        return 'readypeeps_user_profile';
    }
}