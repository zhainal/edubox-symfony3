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
        $builder->add('number', NumberType::class);
        $builder->add('letter', TextType::class);
        $builder->add('subjects', EntityType::class, [
            'class' => Subject::class,
            'choice_label' => 'name',
            'required' => false,
            'multiple' => true,
            'disabled' => true,
        ]);
        $builder->add('submit', SubmitType::class);
    }
}