<?php

namespace EduBoxBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * SubjectArea
 *
 * @ORM\Table(name="subject_area")
 * @ORM\Entity(repositoryClass="EduBoxBundle\Repository\SubjectAreaRepository")
 */
class SubjectArea
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
     * @ORM\OneToMany(targetEntity="EduBoxBundle\Entity\Subject", mappedBy="subjectArea")
     */
    private $subjects;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
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
     * @return SubjectArea
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
     * @return ArrayCollection
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * @param ArrayCollection $subjects
     * @return $this
     */
    public function setSubjects(ArrayCollection $subjects)
    {
        $this->subjects = $subjects;

        return $this;
    }
}

