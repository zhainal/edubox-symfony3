<?php


namespace EduBoxBundle\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use EduBoxBundle\Entity\Calendar;
use EduBoxBundle\Entity\Holiday;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\Subject;
use EduBoxBundle\Entity\SubjectArea;
use EduBoxBundle\Entity\SubjectSchedule;
use EduBoxBundle\Entity\SubjectSchedulesGroup;
use EduBoxBundle\Entity\User;
use EduBoxBundle\Entity\UserMeta;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EduBoxFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $studentsGroups = [
            1 => [
                'number' => 1,
                'letter' => 'A',
            ],
        ];
        foreach ($studentsGroups as $key => $item) {
            $studentsGroup = new StudentsGroup();
            $studentsGroup->setLetter($item['letter']);
            $studentsGroup->setNumber($item['number']);
            $manager->persist($studentsGroup);
            $studentsGroups[$key]['object'] = $studentsGroup;
        }
        $manager->flush();

        // Creating subject areas
        $subjectAres = [
            1 => [
                "name" => "Exact science"
            ],
            2 => [
                "name" => "Human science"
            ],
            3 => [
                "name" => "Natural science"
            ],
        ];
        foreach ($subjectAres as $key => $item) {
            $subjectArea = new SubjectArea();
            $subjectArea->setName($item['name']);
            $manager->persist($subjectArea);
            $subjectAres[$key]['object'] = $subjectArea;
        }
        $manager->flush();


        // Create users
        $users = [
            1 => [
                "username" => "admin",
                "email" => "admin@mail.com",
                "lastname" => "Jones",
                "firstname" => "Philip",
                "gender" => User::GENDER_MALE,
                "birthday" => "1940-09-03",
                "role" => "ROLE_ADMIN",
                "image" => "/assets/img/user/male.jpg",
            ],
            2 => [
                "username" => "teacher",
                "email" => "teacher@mail.com",
                "lastname" => "Hall",
                "firstname" => "Robert",
                "gender" => User::GENDER_MALE,
                "birthday" => "1993-09-23",
                "role" => "ROLE_TEACHER",
                "image" => "/assets/img/user/male.jpg",
            ],
            3 => [
                "username" => "parent",
                "email" => "parent@mail.com",
                "lastname" => "Heller",
                "firstname" => "Jessica",
                "gender" => User::GENDER_FEMALE,
                "birthday" => "1967-02-24",
                "role" => "ROLE_PARENT",
                "image" => "/assets/img/user/female.jpeg",
            ],
            4 => [
                "username" => "student",
                "parent" => 3,
                "email" => "student@mail.com",
                "lastname" => "Heller",
                "firstname" => "Paul",
                "gender" => User::GENDER_MALE,
                "birthday" => "",
                "role" => "ROLE_STUDENT",
                "class" => 1,
                "image" => "/assets/img/user/male.jpg",
            ],
        ];
        foreach ($users as $key => $item) {
            $user = new User();
            $user->setEnabled(true);
            $user->setUsername($item['username']);
            $user->setPlainPassword($item['username']);
            $user->setRoles([$item['role']]);
            $user->setEmail($item['email']);
            $user->setGender($item['gender']);
            if (isset($item['birthday']) && $item['birthday'] != "")
                $user->setBirthday((new \DateTime())->setTimestamp(strtotime($item['birthday'])));
            $user->setLastName($item['lastname']);
            $user->setFirstName($item['firstname']);
            $manager->persist($user);
            $users[$key]['object'] = $user;
            $manager->flush();
            if (isset($item['image']) && file_exists($this->getWebDir() . $item['image'])) {
                $temp = tmpfile();
                fwrite($temp, file_get_contents($this->getWebDir() . $item['image']));
                $image = stream_get_meta_data($temp)['uri'].'.'.pathinfo($this->getWebDir() . $item['image'], PATHINFO_EXTENSION);
                rename(stream_get_meta_data($temp)['uri'], $image);
                $user->setProfilePictureFile(
                    (new UploadedFile(
                        $image,
                        basename($image),
                        null,
                        null,
                        null,
                        true
                    ))
                );
            }
        }

        foreach ($users as $key => $item) {
            if ($item['object'] instanceof User) {
                if (isset($item['parent']) && $item['parent'] > 0) {
                    if (
                        $users[$item['parent']] &&
                        $users[$item['parent']]['object'] instanceof User
                    )
                        $this->setParent($item['object'], $users[$item['parent']]['object'], $manager);
                }
                if (isset($item['class']) && $item['class'] > 0) {
                    if (
                        $studentsGroups[$item['class']] &&
                        $studentsGroups[$item['class']]['object'] instanceof StudentsGroup

                )
                    $this->setStudentsGroup($item['object'], $manager, $studentsGroups[$item['class']]['object']);
                }
            }
        }


        // Create subjects
        $subjects = [
            1 => ["name" => "Mathematics", "subjectArea" => 1, "classes" => [1], "teacher" => 2],
        ];
        foreach ($subjects as $key => $item) {
            $subject = new Subject();
            $subject->setName($item['name']);
            $subject->setSubjectArea($subjectAres[$item['subjectArea']]['object']);
            foreach ($item['classes'] as $item2) {
                if (isset($studentsGroups[$item2]) && $studentsGroups[$item2]['object'] instanceof StudentsGroup)
                    $subject->getStudentsGroups()->add($studentsGroups[$item2]['object']);
            }
            if (isset($users[$item['teacher']]) && $users[$item['teacher']]['object'] instanceof User)
                $subject->setUser($users[$item['teacher']]['object']);
            $manager->persist($subject);
            $subjects[$key]['object'] = $subject;
        }
        $manager->flush();

        // Create subject schedule
        $subjectSchedules = [
            [
                "class" => $studentsGroups[1]['object'],
                "schedule" => [
                    1 => [
                        1 => 1,
                    ], 2 => [
                        1 => 1,
                    ], 3 => [
                        1 => 1,
                    ], 4 => [
                        1 => 1,
                    ], 5 => [
                        1 => 1,
                    ], 6 => [
                        1 => 1,
                    ],
                ]
            ],
        ];
        $subjectScheduleGroup = new SubjectSchedulesGroup();
        $subjectScheduleGroup->setName('Sample schedule');
        $manager->persist($subjectScheduleGroup);
        $this->setActivaSubjectSchedulesGroup($subjectScheduleGroup, $manager);
        foreach ($subjectSchedules as $key => $item) {
            $subjectSchedule = new SubjectSchedule();
            $subjectSchedule->setStudentsGroup($item['class']);
            $subjectSchedule->setSubjectSchedulesGroup($subjectScheduleGroup);
            for ($i = 1; $i <= 6; $i++)
                for ($j = 1; $j <= 7; $j++)
                    if (empty($item['schedule'][$i][$j])) $item['schedule'][$i][$j] = null;
                    elseif (isset($subjects[$item['schedule'][$i][$j]]) && $subjects[$item['schedule'][$i][$j]]['object'] instanceof Subject)
                        $item['schedule'][$i][$j] = $subjects[$item['schedule'][$i][$j]]['object']->getId();
                    else
                        $item['schedule'][$i][$j] = null;

            $subjectSchedule->setSchedule($item['schedule']);
            $manager->persist($subjectSchedule);
        }
        $manager->flush();

        // Createing calendar and holidays
        $calendar = new Calendar();
        $calendar->setYear(2019);
        $calendar->setQuarterOneBegin((new \DateTime())->setDate(2019, 9, 1));
        $calendar->setQuarterOneEnd((new \DateTime())->setDate(2019, 10, 20));
        $calendar->setQuarterTwoBegin((new \DateTime())->setDate(2019, 11, 1));
        $calendar->setQuarterTwoEnd((new \DateTime())->setDate(2019, 12, 30));
        $calendar->setQuarterThreeBegin((new \DateTime())->setDate(2020, 1, 11));
        $calendar->setQuarterThreeEnd((new \DateTime())->setDate(2020, 3, 20));
        $calendar->setQuarterFourBegin((new \DateTime())->setDate(2020, 4, 1));
        $calendar->setQuarterFourEnd((new \DateTime())->setDate(2020, 5, 25));
        $manager->persist($calendar);
        $holidays = [
            [
                "name" => "Winter vacation",
                "beginDate" => "2019-12-30",
                "endDate" => "2019-01-11",
            ],
        ];
        $holidayObjects = new ArrayCollection();
        foreach ($holidays as $item) {
            $holiday = new Holiday();
            $holiday->setName($item['name']);
            $holiday->setBegin((new \DateTime())->setTimestamp(strtotime($item['beginDate'])));
            $holiday->setEnd((new \DateTime())->setTimestamp(strtotime($item['endDate'])));
            $manager->persist($holiday);
            $holidayObjects->add($holiday);
        }
        $calendar->setHolidays($holidayObjects);
        $manager->flush();

        // Setting about school
        $school = [
            'director' => $users[1]['object']->getId(),
        ];
        foreach ($school as $key => $item) {
            $setting = $manager
                ->getRepository('EduBoxBundle:Setting')
                ->findOneByOrCreate(['settingKey' => $key]);
            $setting->setSettingValue($item);
            $manager->persist($setting);
        }
        $manager->flush();
    }



    public function setParent(User $student, User $parent, ObjectManager $manager)
    {
        if ($student->hasRole('ROLE_STUDENT') && $parent->hasRole('ROLE_PARENT')) {
            $userMetaRepo = $manager->getRepository('EduBoxBundle:UserMeta');
            $userMetaRepo->findOneByOrCreate(['user' => $student, 'metaKey' => UserMeta::STUDENT_PARENT_ID])->setMetaValue($parent->getId());
            return true;
        }
        return false;
    }

    public function setStudentsGroup(User $student, ObjectManager $manager, StudentsGroup $studentsGroup = null)
    {
        if ($student->hasRole('ROLE_STUDENT')) {
            $userMetaRepo = $manager->getRepository('EduBoxBundle:UserMeta');
            if ($studentsGroup instanceof StudentsGroup) {
                return $userMetaRepo->findOneByOrCreate(['user' => $student, 'metaKey' => UserMeta::STUDENT_GROUP_ID])->setMetaValue($studentsGroup->getId());
            } else if ($studentsGroup === null)
            {
                return $userMetaRepo->findOneByOrCreate(['user' => $student, 'metaKey' => UserMeta::STUDENT_GROUP_ID])->setMetaValue(null);
            }
        }
    }

    public function setActivaSubjectSchedulesGroup(SubjectSchedulesGroup $schedulesGroup, ObjectManager $manager)
    {
        $setting = $manager
            ->getRepository('EduBoxBundle:Setting')
            ->findOneByOrCreate(['settingKey' => 'subject_schedules_group_id']);
        $setting->setSettingValue($schedulesGroup->getId());
        $manager->flush();
    }

    protected function getWebDir() {
        // the absolute directory path where uploaded
        // documents should be saved
        global $kernel;
        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        return $kernel->getProjectDir().'/web';
    }

}