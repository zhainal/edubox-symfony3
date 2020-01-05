<?php

namespace EduBoxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quarter
 *
 * @ORM\Table(name="quarter")
 * @ORM\Entity(repositoryClass="EduBoxBundle\Repository\QuarterRepository")
 */
class Quarter
{
    const NO_MARK = '-';
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
     * @var
     * @ORM\Column(name="mark", type="float", nullable=true)
     */
    private $mark;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="EduBoxBundle\Entity\Subject", inversedBy="quarters")
     */
    private $subject;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="EduBoxBundle\Entity\User", inversedBy="quarters")
     */
    private $user;


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
     * Set number
     *
     * @param integer $number
     *
     * @return Quarter
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }


    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }


    public function setSubject(Subject $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }


    public function setMark($mark)
    {
        $this->mark = $mark;

        return $this;
    }

    public function getMark()
    {
        return $this->mark;
    }
}

