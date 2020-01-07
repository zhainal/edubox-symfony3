<?php


namespace EduBoxBundle\Form\Type;


use EduBoxBundle\Entity\Homework;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LessonFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, ['required' => false]);
        $builder->add('content', TextareaType::class, ['required' => false]);
        $builder->add('homeworks', EntityType::class, [
            'disabled' => true,
            'class' => Homework::class,
            'choice_label' => 'name',
            'multiple' => true
        ]);
    }
}