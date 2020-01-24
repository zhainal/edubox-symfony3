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
            ],
            'label' => 'calendar.year',
            'translation_domain' => 'forms',
        ]);

        if (!$options['new']) {
            $builder->add('holidays', EntityType::class, [
                'class' => Holiday::class,
                'multiple' => true,
                'choice_label' => 'name',
                'required' => false,
                'label' => 'calendar.holidays',
                'translation_domain' => 'forms',
            ]);
            $builder->add('quarterOneBegin', TextType::class, [
                'required' => false,
                'label' => 'calendar.quarter_1_begin',
                'translation_domain' => 'forms',
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
                'label' => 'calendar.quarter_1_end',
                'translation_domain' => 'forms',
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
                'label' => 'calendar.quarter_2_begin',
                'translation_domain' => 'forms',
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
                'label' => 'calendar.quarter_2_end',
                'translation_domain' => 'forms',
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
                'label' => 'calendar.quarter_3_begin',
                'translation_domain' => 'forms',
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
                'label' => 'calendar.quarter_3_end',
                'translation_domain' => 'forms',
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
                'label' => 'calendar.quarter_4_begin',
                'translation_domain' => 'forms',
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
                'label' => 'calendar.quarter_4_end',
                'translation_domain' => 'forms',
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

        $builder->add('submit', SubmitType::class, [
            'label' => $options['new'] ? 'create' : 'save',
            'translation_domain' => 'forms',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'new' => false,
        ]);
    }
}