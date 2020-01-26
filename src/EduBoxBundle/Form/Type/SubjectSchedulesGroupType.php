<?php


namespace EduBoxBundle\Form\Type;


use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubjectSchedulesGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, ['label' => 'subject_schedules_group.name', 'translation_domain' => 'forms']);
        $builder->add('active', CheckboxType::class, [
            'required' => false,
            'mapped' => false,
            'data' => $options['active'],
            'label' => 'subject_schedules_group.active',
            'translation_domain' => 'forms'
        ]);
        $builder->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'active' => false,
        ]);
    }
}