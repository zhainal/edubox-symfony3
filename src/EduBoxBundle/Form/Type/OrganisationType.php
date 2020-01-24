<?php


namespace EduBoxBundle\Form\Type;


use Doctrine\ORM\EntityRepository;
use EduBoxBundle\DomainManager\UserManager;
use EduBoxBundle\Entity\User;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrganisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shortName', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => '32'])
                ],
                'label' => 'organisation.short_name',
                'translation_domain' => 'forms'
            ])
            ->add('fullName', TextareaType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => '256']),
                ],
                'label' => 'organisation.full_name',
                'translation_domain' => 'forms'
            ])
            ->add('address', TextareaType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => '256']),
                ],
                'label' => 'organisation.address',
                'translation_domain' => 'forms'
            ])
            ->add('phone', TextType::class, [
                'constraints' => [
                    new PhoneNumber()
                ],
                'label' => 'organisation.phone',
                'translation_domain' => 'forms'
            ])
            ->add('email', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
                'label' => 'organisation.email',
                'translation_domain' => 'forms'
            ])
            ->add('director', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'fullname',
                'query_builder' => function (EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('u');
                    $qb->where($qb->expr()->like('u.roles', $qb->expr()->literal('%"ROLE_ADMIN"%')));
                    return $qb;
                },
                'required' => false,
                'label' => 'organisation.administrator',
                'translation_domain' => 'forms'
            ])

            ->add('smsApiId', TextType::class, [
                'required' => false,
                'label' => 'organisation.sms_api_id',
                'translation_domain' => 'forms'
            ])
            ->add('smsEnabled', CheckboxType::class, [
                'required' => false,
                'label' => 'organisation.sms_enabled',
                'translation_domain' => 'forms'
            ])
            ->add('smsBalance', NumberType::class, [
                'disabled' => true,
                'label' => 'organisation.sms_balance',
                'translation_domain' => 'forms'
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'save',
                'translation_domain' => 'forms',
            ]);

        $builder->get('smsEnabled')->addModelTransformer(new CallbackTransformer(
            function ($boolAsInt) {
                return (bool)$boolAsInt;
            },
            function ($bool) {
                return $bool;
            }
        ));
        $builder->get('director')->addModelTransformer(new CallbackTransformer(
            function ($userAsId) use ($options) {
                return $options['userManager'] instanceof UserManager ? $options['userManager']->getObject($userAsId) : null;
            },
            function ($userAsObject) {
                return $userAsObject instanceof User ? $userAsObject->getId() : null;
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'userManager' => null,
        ]);
    }
}