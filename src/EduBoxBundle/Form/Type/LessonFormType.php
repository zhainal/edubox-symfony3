<?php


namespace EduBoxBundle\Form\Type;


use EduBoxBundle\Entity\Homework;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\Form\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LessonFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, ['required' => false, 'label' => 'lesson.name', 'translation_domain' => 'forms']);
        $builder->add('content', CKEditorType::class, [
            'config' => array('toolbar' => 'standard'),
            'required' => false,
            'label' => 'lesson.content',
            'translation_domain' => 'forms'
        ]);
        $builder->add('homeworks', EntityType::class, [
            'label' => 'lesson.homeworks',
            'translation_domain' => 'forms',
            'disabled' => true,
            'class' => Homework::class,
            'choice_label' => 'name',
            'multiple' => true
        ]);
    }
}