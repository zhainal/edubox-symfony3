<?php


namespace EduBoxBundle\Form\Type;


use EduBoxBundle\Entity\UserMeta;
use FOS\UserBundle\Form\Type\ChangePasswordFormType;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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
                'translation_domain' => 'forms',
                'label' => 'user.username',
                'disabled' => true
            ])
            ->add('email', EmailType::class, [
                'translation_domain' => 'forms',
                'label' => 'user.email',
                'constraints' => [
                    new Email()
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'translation_domain' => 'forms',
                'label' => 'user.roles',
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Teacher' => 'ROLE_TEACHER',
                    'Student' => 'ROLE_STUDENT',
                    'Parent' => 'ROLE_PARENT',
                ],
                'required' => false,
                'multiple' => true,
            ])
            ->add('enabled', CheckboxType::class, [
                'translation_domain' => 'forms',
                'label' => 'user.enabled',
                'required' => false,
            ])
            ->add('submit', SubmitType::class);

            if ($options['new']) {
                $builder->get('username')->setDisabled(false);
                $builder->add('plainPassword', RepeatedType::class, [
                    'translation_domain' => 'forms',
                    'label' => 'user.password',
                    'type' => PasswordType::class,
                    'options' => array(
                        'translation_domain' => 'FOSUserBundle',
                        'attr' => array(
                            'autocomplete' => 'new-password',
                        ),
                    ),
                    'first_options' => array('label' => 'form.new_password'),
                    'second_options' => array('label' => 'form.new_password_confirmation'),
                    'invalid_message' => 'fos_user.password.mismatch',
                ]);
            } else {
                $builder
                    ->add('phone', TextType::class, [
                        'translation_domain' => 'forms',
                        'label' => 'user.phone',
                        'constraints' => [
                            new PhoneNumber(),
                        ],
                        'required' => false,
                    ])
                    ->add('firstName', TextType::class, [
                        'translation_domain' => 'forms',
                        'label' => 'user.first_name',
                        'constraints' => [
                            new Length(['min' => 3, 'max' => 32]),
                        ],
                        'required' => false,
                    ])
                    ->add('middleName', TextType::class, [
                        'translation_domain' => 'forms',
                        'label' => 'user.middle_name',
                        'constraints' => [
                            new Length(['min' => 3, 'max' => 32]),
                        ],
                        'required' => false,
                    ])
                    ->add('lastName', TextType::class, [
                        'translation_domain' => 'forms',
                        'label' => 'user.last_name',
                        'constraints' => [
                            new Length(['min' => 3, 'max' => 32]),
                        ],
                        'required' => false,
                    ])
                    ->add('gender', ChoiceType::class, [
                        'translation_domain' => 'forms',
                        'label' => 'user.gender',
                        'choices' => [
                            'Male' => UserMeta::MALE,
                            'Female' => UserMeta::FEMALE,
                        ],
                        'required' => false,
                    ])
                    ->add('birthday',TextType::class, [
                        'translation_domain' => 'forms',
                        'label' => 'user.birthday',
                        'constraints' => [
                            new Date(),
                        ],
                        'required' => false,
                    ]);
            }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'new' => false,
        ]);
    }
}