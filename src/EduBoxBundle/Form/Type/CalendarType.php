<?php


namespace EduBoxBundle\Form\Type;


use Doctrine\ORM\Mapping\UniqueConstraint;
use EduBoxBundle\Entity\Holiday;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('year', NumberType::class, [
            'constraints' => [
            ]
        ]);

        if (!$options['new']) {
            $builder->add('holidays', EntityType::class, [
                'class' => Holiday::class,
                'multiple' => true,
                'choice_label' => 'name',
                'required' => false,
            ]);
            $builder->add('quarterOneBegin', TextType::class, [
                'required' => false,
            ]);
            $builder
                ->get('quarterOneBegin')
                ->addModelTransformer(new CallbackTransformer(
                    function ($dateObject) {
                        if ($dateObject)
                            return $dateObject->format('Y-m-d');
                        else
                            return null;
                    },
                    function ($dateString) {
                        $dateString = trim($dateString);
                        if ($dateString)
                            return new \DateTime($dateString);
                        else
                            return null;
                    }
                ));
            
            $builder->add('quarterOneEnd', TextType::class, [
                'required' => false,
            ]);
            $builder
                ->get('quarterOneEnd')
                ->addModelTransformer(new CallbackTransformer(
                    function ($dateObject) {
                        if ($dateObject)
                            return $dateObject->format('Y-m-d');
                        else
                            return null;
                    },
                    function ($dateString) {
                        $dateString = trim($dateString);
                        if ($dateString)
                            return new \DateTime($dateString);
                        else
                            return null;
                    }
                ));

            $builder->add('quarterTwoBegin', TextType::class, [
                'required' => false,
            ]);
            $builder
                ->get('quarterTwoBegin')
                ->addModelTransformer(new CallbackTransformer(
                    function ($dateObject) {
                        if ($dateObject)
                            return $dateObject->format('Y-m-d');
                        else
                            return null;
                    },
                    function ($dateString) {
                        $dateString = trim($dateString);
                        if ($dateString)
                            return new \DateTime($dateString);
                        else
                            return null;
                    }
                ));

            $builder->add('quarterTwoEnd', TextType::class, [
                'required' => false,
            ]);
            $builder
                ->get('quarterTwoEnd')
                ->addModelTransformer(new CallbackTransformer(
                    function ($dateObject) {
                        if ($dateObject)
                            return $dateObject->format('Y-m-d');
                        else
                            return null;
                    },
                    function ($dateString) {
                        $dateString = trim($dateString);
                        if ($dateString)
                            return new \DateTime($dateString);
                        else
                            return null;
                    }
                ));

            $builder->add('quarterThreeBegin', TextType::class, [
                'required' => false,
            ]);
            $builder
                ->get('quarterThreeBegin')
                ->addModelTransformer(new CallbackTransformer(
                    function ($dateObject) {
                        if ($dateObject)
                            return $dateObject->format('Y-m-d');
                        else
                            return null;
                    },
                    function ($dateString) {
                        $dateString = trim($dateString);
                        if ($dateString)
                            return new \DateTime($dateString);
                        else
                            return null;
                    }
                ));

            $builder->add('quarterThreeEnd', TextType::class, [
                'required' => false,
            ]);
            $builder
                ->get('quarterThreeEnd')
                ->addModelTransformer(new CallbackTransformer(
                    function ($dateObject) {
                        if ($dateObject)
                            return $dateObject->format('Y-m-d');
                        else
                            return null;
                    },
                    function ($dateString) {
                        $dateString = trim($dateString);
                        if ($dateString)
                            return new \DateTime($dateString);
                        else
                            return null;
                    }
                ));

            $builder->add('quarterFourBegin', TextType::class, [
                'required' => false,
            ]);
            $builder
                ->get('quarterFourBegin')
                ->addModelTransformer(new CallbackTransformer(
                    function ($dateObject) {
                        if ($dateObject)
                            return $dateObject->format('Y-m-d');
                        else
                            return null;
                    },
                    function ($dateString) {
                        $dateString = trim($dateString);
                        if ($dateString)
                            return new \DateTime($dateString);
                        else
                            return null;
                    }
                ));

            $builder->add('quarterFourEnd', TextType::class, [
                'required' => false,
            ]);
            $builder
                ->get('quarterFourEnd')
                ->addModelTransformer(new CallbackTransformer(
                    function ($dateObject) {
                        if ($dateObject)
                            return $dateObject->format('Y-m-d');
                        else
                            return null;
                    },
                    function ($dateString) {
                        $dateString = trim($dateString);
                        if ($dateString)
                            return new \DateTime($dateString);
                        else
                            return null;
                    }
                ));

        }

        $builder->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'new' => false,
        ]);
    }
}