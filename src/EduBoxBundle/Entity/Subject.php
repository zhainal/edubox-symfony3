<?php

namespace EduBoxBundle\Entity;

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
     * @ORM\ManyToMany(targetEntity="EduBoxBundle\Entity\SubjectArea")
     * @ORM\JoinTable(name="subject_subject_area")
     */
    private $subjectArea;


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
}

