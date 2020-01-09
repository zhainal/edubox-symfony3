<?php

namespace EduBoxBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Subject
 *
 * @ORM\Table(name="subject")
 * @ORM\Entity(repositoryClass="EduBoxBundle\Repository\SubjectRepository")
 */
class Subject
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
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="EduBoxBundle\Entity\SubjectArea", inversedBy="subjects")
     */
    private $subjectArea;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="EduBoxBundle\Entity\StudentsGroup", inversedBy="subjects")
     * @ORM\JoinTable(name="subject_students_group")
     */
    private $studentsGroups;

    /**
     * Teacher
     * @var User
     * @ORM\ManyToOne(targetEntity="EduBoxBundle\Entity\User", inversedBy="subjects")
     */
    private $user;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="EduBoxBundle\Entity\Quarter", mappedBy="subject")
     */
    private $quarters;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="EduBoxBundle\Entity\Lesson", mappedBy="subject")
     */
    private $lessons;

    public function __construct()
    {
        $this->studentsGroups = new ArrayCollection();
        $this->lessons = new ArrayCollection();
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
     * @return Subject
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
     * Set subjectArea
     *
     * @param \stdClass $subjectArea
     *
     * @return Subject
     */
    public function setSubjectArea($subjectArea)
    {
        $this->subjectArea = $subjectArea;

        return $this;
    }

    /**
     * Get subjectArea
     *
     * @return \stdClass
     */
    public function getSubjectArea()
    {
        return $this->subjectArea;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getStudentsGroups()
    {
        return $this->studentsGroups;
    }

    public function hasStudentsGroup(StudentsGroup $studentsGroup)
    {
        return $this->studentsGroups->contains($studentsGroup);
    }

    /**
     * @param ArrayCollection $studentsGroups
     * @return $this
     */
    public function setStudentsGroups(ArrayCollection $studentsGroups)
    {
        $this->studentsGroups = $studentsGroups;

        return $this;
    }

    public function __toString()
    {
        return $this->name ? $this->name : 'Subject';
    }
}

