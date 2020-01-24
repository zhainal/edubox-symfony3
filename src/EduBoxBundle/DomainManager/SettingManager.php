<?php


namespace EduBoxBundle\DomainManager;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\Entity\Calendar;
use EduBoxBundle\Entity\Setting;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\Subject;
use EduBoxBundle\Entity\SubjectSchedulesGroup;
use EduBoxBundle\Entity\User;

class SettingManager
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(Setting $setting)
    {
        $this->entityManager->persist($setting);
        $this->entityManager->flush();
    }

    public function store(Setting $setting)
    {
        $this->entityManager->flush();
    }

    public function getKeys()
    {
        return array(
            'shortName' => 'short_name',
            'fullName' => 'full_name',
            'address' => 'address',
            'phone' => 'phone',
            'email' => 'email',
            'director' => 'director',
            'calendar' => 'calendar_id',
            'subjectSchedulesGroup' => 'subject_schedules_group_id',
            'smsEnabled' => 'sms_enabled',
            'smsApiId' => 'sms_api_id',
            'smsBalance' => 'sms_balance',
        );
    }


    public function getSetting($name)
    {
        $keys = $this->getKeys();
        if (in_array($name, array_keys($keys))) {
            $repository = $this->entityManager->getRepository(Setting::class);
            return $repository->findOneByOrCreate(['settingKey'=>$keys[$name]]);
        }
        return null;
    }

    public function getPerformance($quarter, StudentsGroup $studentsGroup, Subject $subject = null, User $student = null)
    {
        $repository = $this->entityManager->getRepository(Setting::class);
        if ($studentsGroup instanceof StudentsGroup && $subject instanceof Subject && $student instanceof User) {
            return $repository->findOneByOrCreate([
                'settingKey' => 'performance_Q'.$quarter.'_CL'.$studentsGroup->getId().'_SU'.$subject->getId().'_ST'.$student->getId()
            ]);
        }
        elseif ($studentsGroup instanceof StudentsGroup && $subject instanceof Subject) {
            return $repository->findOneByOrCreate([
                'settingKey' => 'performance_Q'.$quarter.'_CL'.$studentsGroup->getId().'_SU'.$subject->getId()
            ]);
        }
        elseif ($studentsGroup instanceof StudentsGroup && $student instanceof User) {
            return $repository->findOneByOrCreate([
                'settingKey' => 'performance_Q'.$quarter.'_CL'.$studentsGroup->getId().'_ST'.$student->getId()
            ]);
        }
        elseif ($studentsGroup instanceof StudentsGroup) {
            return $repository->findOneByOrCreate([
                'settingKey' => 'performance_Q'.$quarter.'_CL'.$studentsGroup->getId()
            ]);
        }
        return null;
    }

    public function getAttendance($quarter, StudentsGroup $studentsGroup, Subject $subject = null, User $student = null)
    {
        $repository = $this->entityManager->getRepository(Setting::class);
        if ($studentsGroup instanceof StudentsGroup && $subject instanceof Subject && $student instanceof User) {
            return $repository->findOneByOrCreate([
                'settingKey' => 'attendance_Q'.$quarter.'_CL'.$studentsGroup->getId().'_SU'.$subject->getId().'_ST'.$student->getId()
            ]);
        }
        elseif ($studentsGroup instanceof StudentsGroup && $subject instanceof Subject) {
            return $repository->findOneByOrCreate([
                'settingKey' => 'attendance_Q'.$quarter.'_CL'.$studentsGroup->getId().'_SU'.$subject->getId()
            ]);
        }
        elseif ($studentsGroup instanceof StudentsGroup && $student instanceof User) {
            return $repository->findOneByOrCreate([
                'settingKey' => 'attendance_Q'.$quarter.'_CL'.$studentsGroup->getId().'_ST'.$student->getId()
            ]);
        }
        elseif ($studentsGroup instanceof StudentsGroup) {
            return $repository->findOneByOrCreate([
                'settingKey' => 'attendance_Q'.$quarter.'_CL'.$studentsGroup->getId()
            ]);
        }
        return null;
    }

    public function getPercent(Setting $row, $reverse = false) {
        $row = json_decode($row->getSettingValue());
        if (is_object($row)) {
            $max = (int)$row->max;
            $current = (int)$row->current;
        }
        else {
            return $reverse ? 100 :0;
        }
        if ($max < 1 || $current < 1) {
            return $reverse ? 100 : 0;
        }
        $percent = round($current / $max * 100);
        if ($percent > 100) $percent = 100;
        return $reverse ? 100-$percent : $percent;
    }

    /**
     * @return Calendar|null
     * @throws \Exception
     */
    public function getCalendar()
    {
        $calendar_id = $this->getSetting('calendar')->getSettingValue();
        $calendarRepository = $this->entityManager->getRepository(Calendar::class);
        if ($calendar_id) {
            $calendar = $calendarRepository->find($calendar_id);
            if ($calendar) {
                return $calendar;
            }
        }
        else {
            $qb = $calendarRepository->createQueryBuilder('c');
            $qb
                ->where('c.year >= :year-1')
                ->andWhere('c.year <= :year+1')
                ->setParameter('year', date('Y'))
                ->orderBy('c.year', 'DESC');
            $calendars = $qb->getQuery()->getResult();
            foreach ($calendars as $calendar) {
                if ($calendar instanceof Calendar) {
                    $endDate = $calendar->getQuarterFourEnd();
                    if ($endDate instanceof \DateTime) {
                        if ($endDate->getTimestamp() > time()) {
                            return $calendar;
                        }
                    }
                }
            }
        }
        throw new \Exception('The organization has not yet selected a calendar, please contact the administration.');
    }

    public function getSubjectSchedulesGroup()
    {
        $group_id = $this->getSetting('subjectSchedulesGroup')->getSettingValue();
        if ($group_id) {
            $groupRepository = $this->entityManager->getRepository(SubjectSchedulesGroup::class);
            $group = $groupRepository->find($group_id);
            if ($group) {
                return $group;
            }
        }
        throw new \Exception('The organization has not yet selected a subject schedules group, please contact the administration.');
    }




}