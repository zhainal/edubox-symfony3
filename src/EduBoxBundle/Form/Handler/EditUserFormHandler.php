<?php


namespace EduBoxBundle\Form\Handler;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\StudentClass;
use EduBoxBundle\Entity\User;
use EduBoxBundle\Entity\UserMeta;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EditUserFormHandler
{
    private $entityManager;
    private $formFields;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function postCreateForm(FormInterface $form, $userId)
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);
        if (!$user) {
            return false;
        }
        $roles = [
            'admin' => $user->hasRole('ROLE_ADMIN'),
            'teacher' => $user->hasRole('ROLE_TEACHER'),
            'parent' => $user->hasRole('ROLE_PARENT'),
            'student' => $user->hasRole('ROLE_STUDENT')
        ];

        $this->createFormFields($user, $roles);

        if ($roles['student'])
        {
            $form
                ->add('has_role_student', HiddenType::class)
                ->add('group', EntityType::class, [
                    'class' => 'EduBoxBundle\Entity\StudentClass',
                    'choice_label' => 'name',
                    'required' => false,
                ])
                ->add('parent', EntityType::class, [
                    'class' => 'EduBoxBundle\Entity\User',
                    'choice_label' => 'username',
                    'required' => false,
                ]);
        }

        foreach ($this->formFields as $formKey => $field)
        {
            if (!is_callable($field['get'])) {
                throw new HttpException(500, "No callable getter for $formKey field");
            }
            $form->get($formKey)->setData($field['get']());
        }

        return true;
    }

    private function createFormFields(User $user, array $roles)
    {
        $userMetaRepo = $this->entityManager->getRepository(UserMeta::class);

        $this->formFields = [
            'username' => [
                'get' => function () use ($user) {
                    return $user->getUsername();
                },
                'set' => function ($value) use ($user) {
                    return $user->setUsername($value);
                },
            ],
            'email' => [
                'get' => function () use ($user) {
                    return $user->getEmail();
                },
                'set' => function ($value) use ($user) {
                    return $user->setEmail($value);
                },
            ],
            'enabled' => [
                'get' => function () use ($user) {
                    return $user->isEnabled();
                },
                'set' => function ($value) use ($user) {
                    return $user->setEnabled($value);
                },
            ],
            'firstName' => [
                'get' => function () use ($user) {
                    return $user->getFirstName();
                },
                'set' => function ($value) use ($user) {
                    return $user->setFirstName($value);
                },
            ],
            'middleName' => [
                'get' => function () use ($user) {
                    return $user->getMiddleName();
                },
                'set' => function ($value) use ($user) {
                    return $user->setMiddleName($value);
                },
            ],
            'lastName' => [
                'get' => function () use ($user) {
                    return $user->getLastName();
                },
                'set' => function ($value) use ($user) {
                    return $user->setLastName($value);
                },
            ],
            'phone' => [
                'get' => function () use ($user) {
                    return $user->getPhone();
                },
                'set' => function ($value) use ($user) {
                    return $user->setPhone($value);
                },
            ],
            'birthday' => [
                'get' => function () use ($user) {
                    if ($user->getBirthday() instanceof \DateTime) {
                        return date('Y-m-d', $user->getBirthday()->getTimestamp());
                    } else
                    {
                        return null;
                    }
                },
                'set' => function ($value) use ($user) {
                    return $user->setBirthday((new \DateTime($value)));
                },
            ],
            'gender' => [
                'get' => function () use ($user) {
                    return $user->getGender();
                },
                'set' => function ($value) use ($user) {
                    return $user->setGender($value);
                },
            ],
        ];

        if ($roles['student']) {
            $groupRepo = $this->entityManager->getRepository(StudentClass::class);

            $this->formFields['group'] = [
                'get' => function () use ($groupRepo, $user, $userMetaRepo) {
                    $groupId = $userMetaRepo->findOneByOrCreate(['user' => $user, 'metaKey' => 'group_id'])->getMetaValue();
                    if ($groupId) {
                        $group = $groupRepo->find($groupId);
                        if (!$group) return null;
                        else return $group;
                    } else return null;
                },
                'set' => function ($value) use ($userMetaRepo, $user) {
                    if ($value instanceof StudentClass) {
                        return $userMetaRepo->findOneByOrCreate(['user' => $user, 'metaKey' => 'group_id'])->setMetaValue($value->getId());
                    } else if ($value === null)
                    {
                        return $userMetaRepo->findOneByOrCreate(['user' => $user, 'metaKey' => 'group_id'])->setMetaValue(null);
                    }

                    return null;
                }
            ];

        }
    }

    private function updateUser(FormInterface $form)
    {
        foreach ($this->formFields as $formKey => $field)
        {
            if (is_callable($field['set'])) {
                $field['set']($form->get($formKey)->getData());
            } else {
                $form->addError(new FormError('Unable to change "'.$form->get($formKey)->getName().'" field'));
            }
        }
        $this->entityManager->flush();
    }

    public function handle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $this->updateUser($form);
            return true;
        }

        return false;
    }
}