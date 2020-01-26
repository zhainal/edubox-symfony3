<?php


namespace EduBoxBundle\Form\Type;


use Doctrine\ORM\EntityRepository;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\Subject;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubjectScheduleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('submit', SubmitType::class, ['label' => 'save', 'translation_domain' => 'forms']);
        $builder->add('studentsGroup', EntityType::class, [
            'label' => 'subject_schedule.class',
            'translation_domain' => 'forms',
            'class' => StudentsGroup::class,
            'choice_label' => 'name',
            'required' => true,
            'disabled' => true,
        ]);
        if (!$options['new'])
        {
            $builder->get('studentsGroup')
                ->setAttribute('disabled', true);
            $builder->add('schedule', FormType::class);
            foreach (range(1,6) as $day)
            {
                $builder->get('schedule')
                    ->add($day, FormType::class);
                foreach (range(1, $options['hours']) as $hour)
                {
                    $builder->get('schedule')
                        ->get($day)
                        ->add($hour, EntityType::class, [
                            'label' => 'subject_schedule.hour',
                            'class' => Subject::class,
                            'query_builder' => function (EntityRepository $er) use ($options) {
                                return $er->createQueryBuilder('s')
                                    ->innerJoin('s.studentsGroups', 'g')
                                    ->where('g.id = '.$options['studentsGroupId']);
                            },
                            'choice_label' => 'name',
                            'required' => false,
                        ]);
                    $builder->get('schedule')
                        ->get($day)
                        ->get($hour)
                        ->addModelTransformer(new CallbackTransformer(
                            function ($subjectId) use ($options) {
                                return $options['subjectRepository'] && $subjectId ? $options['subjectRepository']->find($subjectId) : null;
                            },
                            function ($subject) {
                                return $subject ? $subject->getId() : null;
                            }
                        ));
                }
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'new' => false,
            'hours' => 6,
            'subjectRepository' => null,
            'studentsGroupId' => null,
        ]);
    }
}