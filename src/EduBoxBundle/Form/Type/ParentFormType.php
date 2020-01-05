<?php


namespace EduBoxBundle\Form\Type;


use Doctrine\ORM\EntityRepository;
use EduBoxBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;

class ParentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('children', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'full_name',
                'required' => false,
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                    $er = $er->createQueryBuilder('u');
                    $er->where($er->expr()->like('u.roles', $er->expr()->literal('%"ROLE_STUDENT"%')));
                    return $er;
                },
            ]);
    }

    public function getParent()
    {
        return FormType::class;
    }

}