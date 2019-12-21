<?php

namespace EduBoxBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * StudentClass
 *
 * @ORM\Table(name="students_group")
 * @ORM\Entity(repositoryClass="EduBoxBundle\Repository\StudentsGroupRepository")
 */
class StudentsGroup
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
     * @var int
     *
     * @ORM\Column(name="number", type="integer")
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="letter", type="string", length=2)
     */
    private $letter;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="EduBoxBundle\Entity\SubjectSchedule", mappedBy="studentsGroup")
     */
    private $subjectSchedules;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="EduBoxBundle\Entity\Subject", mappedBy="studentsGroups")
     */
    private $subjects;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->subjectSchedules = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->number . $this->letter;
    }

    /**
     * @param $number
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param $letter
     * @return $this
     */
    public function setLetter($letter)
    {
        $this->letter = $letter;

        return $this;
    }

    /**
     * @return string
     */
    public function getLetter()
    {
        return $this->letter;
    }

    /**
     * @return ArrayCollection
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * @return ArrayCollection
     */
    public function getSubjectSchedules()
    {
        return $this->subjectSchedules;
    }
}

