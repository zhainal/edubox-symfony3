<?php


namespace EduBoxBundle\Form;


use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
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
                ]
            ])
            ->add('fullName', TextareaType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => '256']),
                ]
            ])
            ->add('address', TextareaType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => '256']),
                ]
            ])
            ->add('phone', TextType::class, [
                'constraints' => [
                    new PhoneNumber()
                ]
            ])
            ->add('email', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ]
            ])
            ->add('director', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => '32']),
                ]
            ])
            ->add('submit', SubmitType::class);
    }
}