<?php

namespace EduBoxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SubjectSchedule
 *
 * @ORM\Table(name="subject_schedule")
 * @ORM\Entity(repositoryClass="EduBoxBundle\Repository\SubjectScheduleRepository")
 */
class SubjectSchedule
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="EduBoxBundle\Entity\StudentsGroup", inversedBy="subjectSchedules")
     * @Assert\NotBlank()
     */
    private $studentsGroup;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="EduBoxBundle\Entity\SubjectSchedulesGroup", inversedBy="subjectSchedules")
     */
    private $subjectSchedulesGroup;

    /**
     * @var
     * @ORM\Column(name="schedule", type="json_array", nullable=true)
     */
    private $schedule;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getStudentsGroup()
    {
        return $this->studentsGroup;
    }

    /**
     * @param StudentsGroup $studentsGroup
     * @return $this
     */
    public function setStudentsGroup(StudentsGroup $studentsGroup)
    {
        $this->studentsGroup = $studentsGroup;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * @param $schedule
     * @return $this
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubjectSchedulesGroup()
    {
        return $this->subjectSchedulesGroup;
    }

    /**
     * @param SubjectSchedulesGroup $schedulesGroup
     * @return $this
     */
    public function setSubjectSchedulesGroup(SubjectSchedulesGroup $schedulesGroup)
    {
        $this->subjectSchedulesGroup = $schedulesGroup;

        return $this;
    }

    public function getStatus()
    {
        return '-';
    }
}

