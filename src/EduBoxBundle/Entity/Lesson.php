<?php

namespace EduBoxBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Lesson
 *
 * @ORM\Table(name="lesson")
 * @ORM\Entity(repositoryClass="EduBoxBundle\Repository\LessonRepository")
 */
class Lesson
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
     * @ORM\Column(name="name", type="string", length=256, nullable=true)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var Subject
     *
     * @ORM\ManyToOne(targetEntity="EduBoxBundle\Entity\Subject", inversedBy="lessons")
     */
    private $subject;

    /**
     * @var StudentsGroup
     *
     * @ORM\ManyToOne(targetEntity="EduBoxBundle\Entity\StudentsGroup", inversedBy="lessonsm")
     */
    private $studentsGroup;

    /**
     * @var int
     * @ORM\Column(name="hour", type="integer")
     */
    private $hour = 1;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EduBoxBundle\Entity\Homework", mappedBy="lesson")
     */
    private $homeworks;


    public function __construct()
    {
        $this->homeworks = new ArrayCollection();
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
     * @return Lesson
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

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Lesson
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Lesson
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set subject
     *
     * @param Subject $subject
     *
     * @return Lesson
     */
    public function setSubject(Subject $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return Subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set studentsGroup
     *
     * @param StudentsGroup $studentsGroup
     *
     * @return Lesson
     */
    public function setStudentsGroup(StudentsGroup $studentsGroup)
    {
        $this->studentsGroup = $studentsGroup;

        return $this;
    }

    /**
     * Get studentsGroup
     *
     * @return StudentsGroup
     */
    public function getStudentsGroup()
    {
        return $this->studentsGroup;
    }

    /**
     * @param $hour
     * @return $this
     */
    public function setHour($hour)
    {
        $this->hour = $hour;

        return $this;
    }

    /**
     * @return int
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * @param ArrayCollection $homeworks
     * @return $this
     */
    public function setHomeworks(ArrayCollection $homeworks)
    {
        $this->homeworks = $homeworks;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getHomeworks()
    {
        return $this->homeworks;
    }

    public function nameWithDate()
    {
        return $this->name . ' - '. $this->date->format('d.m.Y') . ' (' . $this->hour . ')';
    }

    public function __toString()
    {
        return $this->name ? $this->name : 'Lesson';
    }

}

