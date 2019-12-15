<?php


namespace EduBoxBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class DateWithoutYear extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date', TextType::class, [
            'required' => false,
        ]);
        $builder
            ->get('date')
            ->addModelTransformer(new CallbackTransformer(
                function ($dateWithYear) use ($builder) {
                    return $dateWithYear->format('m-d');
                },
                function ($dateWithoutYear) {
                    if ($dateWithoutYear)
                        return (new \DateTime('2029'. '-' . $dateWithoutYear));
                    else
                        return $dateWithoutYear;
                }
            ));
    }

    public function getParent()
    {
        return TextType::class;
    }
}