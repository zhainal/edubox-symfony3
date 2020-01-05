<?php


namespace EduBoxBundle\Form\Handler;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\DomainManager\UserManager;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\User;
use EduBoxBundle\Entity\UserMeta;
use EduBoxBundle\Form\Type\AdminFormType;
use EduBoxBundle\Form\Type\ParentFormType;
use EduBoxBundle\Form\Type\StudentFormType;
use EduBoxBundle\Form\Type\TeacherFormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserFormHandler
{
    private $entityManager;
    private $userManager;
    private $formFields;
    private $passwordEncoder;

    public function __construct(UserManager $userManager, EntityManager $entityManager, UserPasswordEncoder $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function postCreateForm(FormInterface $form, $userId)
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }
        $roles = [
            'admin' => $user->hasRole('ROLE_ADMIN'),
            'teacher' => $user->hasRole('ROLE_TEACHER'),
            'parent' => $user->hasRole('ROLE_PARENT'),
            'student' => $user->hasRole('ROLE_STUDENT')
        ];

        $this->createFormFields($user, $roles);

        if ($roles['admin'])
        {
            $form->add('admin_form', AdminFormType::class);
        }
        if ($roles['teacher'])
        {
            $form->add('teacher_form', TeacherFormType::class);
        }
        if ($roles['student'])
        {
            $form->add('student_form', StudentFormType::class);
        }
        if ($roles['parent'])
        {
            $form->add('parent_form', ParentFormType::class);
        }

        foreach ($this->formFields as $formKey => $field)
        {
            if (empty($field['get'])) {
                foreach ($field as $subFormKey => $subfield)
                {
                    if (!is_callable(@$subfield['get'])) {
                        throw new HttpException(500, "No callable getter for $formKey => $subFormKey field");
                    }
                    $form->get($formKey)
                        ->get($subFormKey)
                        ->setData($subfield['get']());
                }
            }
            else {
                if (!is_callable(@$field['get'])) {
                    throw new HttpException(500, "No callable getter for $formKey field");
                }
                $form->get($formKey)->setData($field['get']());
            }
        }
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


        if ($roles['admin']) {
            $this->formFields['admin_form'] = [];
        }

        if ($roles['teacher']) {
            $this->formFields['teacher_form'] = [];
        }

        if ($roles['student']) {
            $groupRepo = $this->entityManager->getRepository(StudentsGroup::class);
            $userRepo = $this->entityManager->getRepository(User::class);

            $this->formFields['student_form'] = [
                'group' => [
                    'get' => function () use ($groupRepo, $user, $userMetaRepo) {
                        $groupId = $userMetaRepo->findOneByOrCreate(['user' => $user, 'metaKey' => UserMeta::STUDENT_GROUP_ID])->getMetaValue();
                        if ($groupId) {
                            $group = $groupRepo->find($groupId);
                            if (!$group) return null;
                            else return $group;
                        } else return null;
                    },
                    'set' => function ($value) use ($userMetaRepo, $user) {
                        if ($value instanceof StudentsGroup) {
                            return $userMetaRepo->findOneByOrCreate(['user' => $user, 'metaKey' => UserMeta::STUDENT_GROUP_ID])->setMetaValue($value->getId());
                        } else if ($value === null)
                        {
                            return $userMetaRepo->findOneByOrCreate(['user' => $user, 'metaKey' => UserMeta::STUDENT_GROUP_ID])->setMetaValue(null);
                        }

                        return null;
                    }
                ],
                'parent' => [
                    'get' => function() use ($userMetaRepo, $user, $userRepo) {
                        $parent_id = $userMetaRepo->findOneByOrCreate(['user' => $user, 'metaKey' => UserMeta::STUDENT_PARENT_ID])->getMetaValue();
                        if ($parent_id != null) {
                            $parent = $userRepo->createQueryBuilder('u');
                            $parent = $parent->where('u.id = :id')
                                ->andWhere($parent->expr()->like('u.roles', $parent->expr()->literal('%"ROLE_PARENT"%')))
                                ->setParameter('id', $parent_id)
                                ->getQuery()->getResult();
                            return isset($parent[0]) ? $parent[0] : null;
                        }
                        return null;
                    },
                    'set' => function($value) use ($userMetaRepo, $user) {
                        return $value == null
                            ? null
                            : $userMetaRepo->findOneByOrCreate(['user' => $user, 'metaKey' => UserMeta::STUDENT_PARENT_ID])->setMetaValue($value->getId());

                    }
                ]

            ];

        }

        if ($roles['parent']) {
            $userRepo = $this->entityManager->getRepository(User::class);

            $this->formFields['parent_form'] = [
                'children' => [
                    'get' => function() use ($userMetaRepo, $user, $userRepo) {
                        $userMetas = $userMetaRepo->findBy(['metaValue' => $user->getId(), 'metaKey' => UserMeta::STUDENT_PARENT_ID]);
                        $student_ids = [];
                        foreach ($userMetas as $userMeta) {
                            $student_ids[] = $userMeta->getUser()->getId();
                        }
                        if ($student_ids != null) {
                            $students = $userRepo->createQueryBuilder('u');
                            $students = $students
                                ->where($students->expr()->in('u.id', $student_ids))
                                ->andWhere($students->expr()->like('u.roles', $students->expr()->literal('%"ROLE_STUDENT"%')))
                                ->getQuery()->getResult();
                            return isset($students) ? $students : null;
                        }
                        return null;
                    },
                    'set' => function($value) use ($userMetaRepo, $user) {
                        $clear = $userMetaRepo->createQueryBuilder('m');
                        $clear
                            ->update('EduBoxBundle:UserMeta', 'm')
                            ->where(
                                $clear->expr()->andX(
                                    $clear->expr()->eq('m.metaValue', $user->getId()),
                                    $clear->expr()->eq('m.metaKey', $clear->expr()->literal(UserMeta::STUDENT_PARENT_ID))
                                )
                            )
                            ->set('m.metaValue', ':metaKey')
                            ->setParameter('metaKey', null)
                            ->getQuery()
                            ->execute();

                        if ($value) {
                            $students = $value;
                            $result = true;
                            foreach ($students as $student) {
                                $result = $result && $userMetaRepo->findOneByOrCreate(['user'=>$student, 'metaKey' => UserMeta::STUDENT_PARENT_ID])
                                        ->setMetaValue($user->getId());
                            }
                            return $result;
                        }

                        return null;
                    }
                ]
            ];
        }



        $this->formFields['roles'] = [
            'get' => function() use ($user) {
                return $user->getRoles();
            },
            'set' => function($value) use ($user, $userMetaRepo) {
                $newRoles = $value;
                $oldRoles = $user->getRoles();
                $removeRoles = [];
                foreach ($oldRoles as $role) {
                    if (!in_array($role, $newRoles) && $role != 'ROLE_USER') {
                        $removeRoles[] = $role;
                    }
                }

                // Get User Meta records which in ROLE_?
                $fields = [];
                foreach ($removeRoles as $role) {
                    if (is_array(@constant(UserMeta::class.'::'.$role))) {
                        foreach (@constant(UserMeta::class.'::'.$role) as $field) {
                            $fields[] = constant(UserMeta::class.'::'.$field);
                        }
                    }
                }

                // Delete meta records
                if (count($fields) > 0) {
                    $deleteMetaQB = $userMetaRepo->createQueryBuilder('m')
                        ->delete('EduBoxBundle:UserMeta', 'm')
                        ->where('m.user = :user')
                        ->setParameter('user', $user);
                    $deleteMetaQB
                        ->andWhere($deleteMetaQB->expr()->in('m.metaKey', $fields))
                        ->getQuery()
                        ->execute();
                }

                return $user->setRoles($value);
            }
        ];
    }

    private function updateUser(FormInterface $form)
    {
        foreach ($this->formFields as $formKey => $field)
        {
            if (!$form->get($formKey)->isDisabled()) {
                if (empty($field['set'])) {
                    foreach ($field as $subFormKey => $subfield)
                    {
                        if (!is_callable(@$subfield['set'])) {
                            throw new HttpException(500, "No callable setter for $formKey => $subFormKey field");
                        }
                        $subfield['set']($form->get($formKey)->get($subFormKey)->getData());
                    }
                }
                else {
                    if (!is_callable(@$field['set'])) {
                        throw new HttpException(500, "No callable setter for $formKey field");
                    }
                    $field['set']($form->get($formKey)->getData());
                }
            }
        }
        $this->entityManager->flush();
    }

    public function editHandle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $this->updateUser($form);
            return true;
        }

        return false;
    }

    public function createHandle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $valid_user = $form->getData();
            $valid_user->setPassword($this->passwordEncoder->encodePassword($valid_user, $valid_user->getPlainPassword()));
            $this->userManager->store($valid_user);
            return $valid_user;
        }

        return false;
    }
}