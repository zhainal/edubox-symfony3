<?php

namespace EduBoxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubjectSchedule
 *
 * @ORM\Table(name="subject_schedules_group")
 * @ORM\Entity(repositoryClass="EduBoxBundle\Repository\SubjectSchedulesGroupRepository")
 */
class SubjectSchedulesGroup
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=32)
     */
    private $name;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="EduBoxBundle\Entity\SubjectSchedule", mappedBy="subjectSchedulesGroup")
     */
    private $subjectSchedules;

    public function __construct()
    {
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return SubjectSchedule
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getSubjectSchedules()
    {
        return $this->subjectSchedules;
    }

    public function setSubjectSchedules($subjectSchedules)
    {
        $this->subjectSchedules = $subjectSchedules;

        return $this;
    }
}

