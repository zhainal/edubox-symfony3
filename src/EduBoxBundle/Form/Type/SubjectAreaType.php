<?php


namespace EduBoxBundle\Form\Type;


use EduBoxBundle\Entity\Subject;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SubjectAreaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, ['label' => 'subject_area.name', 'translation_domain' => 'forms']);
        $builder->add('subjects', EntityType::class, [
            'label' => 'subject_area.subjects',
            'translation_domain' => 'forms',
            'disabled' => true,
            'class' => Subject::class,
            'choice_label' => 'name',
            'multiple' => true,
            'required' => false,
        ]);
        $builder->add('submit', SubmitType::class);
    }

}