<?php

namespace EduBoxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mark
 *
 * @ORM\Table(name="mark")
 * @ORM\Entity(repositoryClass="EduBoxBundle\Repository\MarkRepository")
 */
class Mark
{
    const MARK_DC = 'DC';
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="EduBoxBundle\Entity\User")
     */
    private $user;

    /**
     * @var Subject
     *
     * @ORM\ManyToOne(targetEntity="EduBoxBundle\Entity\Subject")
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="mark", type="string", length=2, nullable=true)
     */
    private $mark;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var
     * @ORM\Column(name="hour", type="integer")
     */
    private $hour = 1;



    public function __construct()
    {
        $this->createdAt= new \DateTime();
        $this->updatedAt= new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt= new \DateTime();
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
     * Set userId
     *
     * @param integer $userId
     *
     * @return Mark
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get User
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set Subject
     *
     * @param Subject $subjectId
     *
     * @return Mark
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
     * Set mark
     *
     * @param string $mark
     *
     * @return Mark
     */
    public function setMark($mark)
    {
        $this->mark = $mark;

        return $this;
    }

    /**
     * Get mark
     *
     * @return string
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Mark
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getDateText()
    {
        return $this->date instanceof \DateTime ? $this->date->format('Y-m-d') : null;
    }

    public function setHour($hour)
    {
        $this->hour = $hour;

        return $this;
    }

    public function getHour()
    {
        return $this->hour;
    }


    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }




}

