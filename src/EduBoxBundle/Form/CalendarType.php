<?php


namespace EduBoxBundle\Form;


use EduBoxBundle\Form\Type\DateWithoutYear;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class);
        $builder->add('year', NumberType::class);

        if (!$options['new']) {
            $builder->add('quarterOneBegin', TextType::class, [
                'required' => false,
            ]);
            $builder
                ->get('quarterOneBegin')
                ->addModelTransformer(new CallbackTransformer(
                    function ($dateWithYear) {
                        if ($dateWithYear)
                            return $dateWithYear->format('m-d');
                        else 
                            return null;
                    },
                    function ($dateWithoutYear) use ($builder) {
                        if (trim($dateWithoutYear) != '')
                            return (new \DateTime($builder->getData()->getYear(). '-' . $dateWithoutYear));
                        else
                            return $dateWithoutYear;
                    }
                ));

            $builder->add('quarterOneEnd', TextType::class, [
                'required' => false,
            ]);
            $builder
                ->get('quarterOneEnd')
                ->addModelTransformer(new CallbackTransformer(
                    function ($dateWithYear) {
                        if ($dateWithYear)
                            return $dateWithYear->format('m-d');
                        else 
                            return null;
                    },
                    function ($dateWithoutYear) use ($builder) {
                        if (trim($dateWithoutYear) != '')
                            return (new \DateTime($builder->getData()->getYear(). '-' . $dateWithoutYear));
                        else
                            return $dateWithoutYear;
                    }
                ));

            $builder->add('quarterTwoBegin', TextType::class, [
                'required' => false,
            ]);
            $builder
                ->get('quarterTwoBegin')
                ->addModelTransformer(new CallbackTransformer(
                    function ($dateWithYear) {
                        if ($dateWithYear)
                            return $dateWithYear->format('m-d');
                        else 
                            return null;
                    },
                    function ($dateWithoutYear) use ($builder) {
                        if (trim($dateWithoutYear) != '')
                            return (new \DateTime($builder->getData()->getYear(). '-' . $dateWithoutYear));
                        else
                            return $dateWithoutYear;
                    }
                ));

            $builder->add('quarterTwoEnd', TextType::class, [
                'required' => false,
            ]);
            $builder
                ->get('quarterTwoEnd')
                ->addModelTransformer(new CallbackTransformer(
                    function ($dateWithYear) {
                        if ($dateWithYear)
                            return $dateWithYear->format('m-d');
                        else 
                            return null;
                    },
                    function ($dateWithoutYear) use ($builder) {
                        if (trim($dateWithoutYear) != '')
                            return (new \DateTime($builder->getData()->getYear(). '-' . $dateWithoutYear));
                        else
                            return $dateWithoutYear;
                    }
                ));

            $builder->add('quarterThreeBegin', TextType::class, [
                'required' => false,
            ]);
            $builder
                ->get('quarterThreeBegin')
                ->addModelTransformer(new CallbackTransformer(
                    function ($dateWithYear) {
                        if ($dateWithYear)
                            return $dateWithYear->format('m-d');
                        else 
                            return null;
                    },
                    function ($dateWithoutYear) use ($builder) {
                        if (trim($dateWithoutYear) != '')
                            return (new \DateTime($builder->getData()->getYear(). '-' . $dateWithoutYear));
                        else
                            return $dateWithoutYear;
                    }
                ));

            $builder->add('quarterThreeEnd', TextType::class, [
                'required' => false,
            ]);
            $builder
                ->get('quarterThreeEnd')
                ->addModelTransformer(new CallbackTransformer(
                    function ($dateWithYear) {
                        if ($dateWithYear)
                            return $dateWithYear->format('m-d');
                        else 
                            return null;
                    },
                    function ($dateWithoutYear) use ($builder) {
                        if (trim($dateWithoutYear) != '')
                            return (new \DateTime($builder->getData()->getYear(). '-' . $dateWithoutYear));
                        else
                            return $dateWithoutYear;
                    }
                ));

            $builder->add('quarterFourBegin', TextType::class, [
                'required' => false,
            ]);
            $builder
                ->get('quarterFourBegin')
                ->addModelTransformer(new CallbackTransformer(
                    function ($dateWithYear) {
                        if ($dateWithYear)
                            return $dateWithYear->format('m-d');
                        else 
                            return null;
                    },
                    function ($dateWithoutYear) use ($builder) {
                        if (trim($dateWithoutYear) != '')
                            return (new \DateTime($builder->getData()->getYear(). '-' . $dateWithoutYear));
                        else
                            return $dateWithoutYear;
                    }
                ));

            $builder->add('quarterFourEnd', TextType::class, [
                'required' => false,
            ]);
            $builder
                ->get('quarterFourEnd')
                ->addModelTransformer(new CallbackTransformer(
                    function ($dateWithYear) {
                        if ($dateWithYear)
                            return $dateWithYear->format('m-d');
                        else 
                            return null;
                    },
                    function ($dateWithoutYear) use ($builder) {
                        if (trim($dateWithoutYear) != '')
                            return (new \DateTime($builder->getData()->getYear(). '-' . $dateWithoutYear));
                        else
                            return $dateWithoutYear;
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