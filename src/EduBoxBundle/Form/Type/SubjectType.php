<?php


namespace EduBoxBundle\Form\Type;


use Doctrine\ORM\EntityRepository;
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
        $builder->add('name', TextType::class, [
            'translation_domain' => 'forms',
            'label' => 'subject.name',
        ]);
        $builder->add('subjectArea', EntityType::class, [
            'translation_domain' => 'forms',
            'label' => 'subject.subject_area',
            'class' => SubjectArea::class,
            'choice_label' => 'name',
            'required' => false,
        ]);
        $builder->add('user', EntityType::class, [
            'translation_domain' => 'forms',
            'label' => 'subject.teacher',
            'class' => User::class,
            'choice_label' => 'full_name',
            'required' => false,
            'query_builder' => function (EntityRepository $er) {
                $er = $er->createQueryBuilder('u');
                $er->where($er->expr()->like('u.roles', $er->expr()->literal('%"ROLE_TEACHER"%')));
                return $er;
            },
        ]);
        $builder->add('studentsGroups', EntityType::class, [
            'translation_domain' => 'forms',
            'label' => 'subject.classes',
            'class' => StudentsGroup::class,
            'choice_label' => 'name',
            'required' => false,
            'multiple' => true,
        ]);
        $builder->add('submit', SubmitType::class);
    }

}