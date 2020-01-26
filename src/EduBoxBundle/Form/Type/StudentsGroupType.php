<?php


namespace EduBoxBundle\Form\Type;

use EduBoxBundle\Entity\Subject;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

class StudentsGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('number', NumberType::class, [
            'translation_domain' => 'forms',
            'label' => 'students_group.number',
        ]);
        $builder->add('letter', TextType::class, [
            'translation_domain' => 'forms',
            'label' => 'students_group.letter',
        ]);
        $builder->add('subjects', EntityType::class, [
            'translation_domain' => 'forms',
            'label' => 'students_group.subjects',
            'class' => Subject::class,
            'choice_label' => 'name',
            'required' => false,
            'multiple' => true,
            'disabled' => true,
        ]);
    }
}