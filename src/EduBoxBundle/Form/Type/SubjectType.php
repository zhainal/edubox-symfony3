<?php


namespace EduBoxBundle\Form\Type;


use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\Subject;
use EduBoxBundle\Entity\SubjectArea;
use EduBoxBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class);
        $builder->add('subjectArea', EntityType::class, [
            'class' => SubjectArea::class,
            'choice_label' => 'name',
            'required' => false,
        ]);
        $builder->add('user', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'full_name',
            'required' => false,
        ]);
        $builder->add('studentsGroups', EntityType::class, [
            'class' => StudentsGroup::class,
            'choice_label' => 'name',
            'required' => false,
            'multiple' => true,
        ]);
        $builder->add('submit', SubmitType::class);
    }

}