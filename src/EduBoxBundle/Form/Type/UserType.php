<?php


namespace EduBoxBundle\Form\Type;


use EduBoxBundle\Entity\UserMeta;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public $student;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'disabled' => true
            ])
            ->add('email', EmailType::class, [
            'constraints' => [
                    new Email()
                ]
            ])
            ->add('phone', TextType::class, [
                'constraints' => [
                    new PhoneNumber(),
                ],
                'required' => false,
            ])
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new Length(['min' => 3, 'max' => 32]),
                ],
                'required' => false,
            ])
            ->add('middleName', TextType::class, [
                'constraints' => [
                    new Length(['min' => 3, 'max' => 32]),
                ],
                'required' => false,
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new Length(['min' => 3, 'max' => 32]),
                ],
                'required' => false,
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Male' => UserMeta::MALE,
                    'Female' => UserMeta::FEMALE,
                ],
                'required' => false,
            ])
            ->add('birthday',TextType::class, [
                'constraints' => [
                    new Date(),
                ],
                'required' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class);

    }
}