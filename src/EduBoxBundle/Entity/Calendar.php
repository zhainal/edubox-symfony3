<?php

namespace EduBoxBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Calendar
 *
 * @ORM\Table(name="calendar")
 * @ORM\Entity(repositoryClass="EduBoxBundle\Repository\CalendarRepository")
 */
class Calendar
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
     *
     * @ORM\Column(name="year", type="integer",length=4, unique=true)
     */
    private $year;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="quarter_one_begin", type="date", nullable=true)
     */
    private $quarterOneBegin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="quarter_one_end", type="date", nullable=true)
     */
    private $quarterOneEnd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="quarter_two_begin", type="date", nullable=true)
     */
    private $quarterTwoBegin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="quarter_two_end", type="date", nullable=true)
     */
    private $quarterTwoEnd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="quarter_three_begin", type="date", nullable=true)
     */
    private $quarterThreeBegin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="quarter_three_end", type="date", nullable=true)
     */
    private $quarterThreeEnd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="quarter_four_begin", type="date", nullable=true)
     */
    private $quarterFourBegin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="quarter_four_end", type="date", nullable=true)
     */
    private $quarterFourEnd;

    /**
     * @var \DateTime
     *
     * @ORM\ManyToMany(targetEntity="EduBoxBundle\Entity\Holiday")
     * @ORM\JoinTable(name="calendar_holiday")
     */
    private $holidays;

    public function __construct()
    {
        $this->holidays = new ArrayCollection();
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
        return $this->name;
    }

    /**
     * @return Calendar
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param $year
     * @return $this
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    public function getBeginDate($quarter) {
        switch ($quarter) {
            case 1: return $this->getQuarterOneBegin();
            case 2: return $this->getQuarterTwoBegin();
            case 3: return $this->getQuarterThreeBegin();
            case 4: return $this->getQuarterFourBegin();
            default: return null;
        }
    }
    public function getEndDate($quarter) {
        switch ($quarter) {
            case 1: return $this->getQuarterOneEnd();
            case 2: return $this->getQuarterTwoEnd();
            case 3: return $this->getQuarterThreeEnd();
            case 4: return $this->getQuarterFourEnd();
            default: return null;
        }
    }


    /**
     * @return \DateTime
     */
    public function getQuarterOneBegin()
    {
        return $this->quarterOneBegin;
    }

    /**
     * @param \DateTime $quarterOneBegin
     */
    public function setQuarterOneBegin($quarterOneBegin)
    {
        $this->quarterOneBegin = $quarterOneBegin;
    }

    /**
     * @return \DateTime
     */
    public function getQuarterOneEnd()
    {
        return $this->quarterOneEnd;
    }

    /**
     * @param \DateTime $quarterOneEnd
     */
    public function setQuarterOneEnd($quarterOneEnd)
    {
        $this->quarterOneEnd = $quarterOneEnd;
    }

    /**
     * @return \DateTime
     */
    public function getQuarterTwoBegin()
    {
        return $this->quarterTwoBegin;
    }

    /**
     * @param \DateTime $quarterTwoBegin
     */
    public function setQuarterTwoBegin($quarterTwoBegin)
    {
        $this->quarterTwoBegin = $quarterTwoBegin;
    }

    /**
     * @return \DateTime
     */
    public function getQuarterTwoEnd()
    {
        return $this->quarterTwoEnd;
    }

    /**
     * @param \DateTime $quarterTwoEnd
     */
    public function setQuarterTwoEnd($quarterTwoEnd)
    {
        $this->quarterTwoEnd = $quarterTwoEnd;
    }

    /**
     * @return \DateTime
     */
    public function getQuarterThreeBegin()
    {
        return $this->quarterThreeBegin;
    }

    /**
     * @param \DateTime $quarterThreeBegin
     */
    public function setQuarterThreeBegin($quarterThreeBegin)
    {
        $this->quarterThreeBegin = $quarterThreeBegin;
    }

    /**
     * @return \DateTime
     */
    public function getQuarterThreeEnd()
    {
        return $this->quarterThreeEnd;
    }

    /**
     * @param \DateTime $quarterThreeEnd
     */
    public function setQuarterThreeEnd($quarterThreeEnd)
    {
        $this->quarterThreeEnd = $quarterThreeEnd;
    }

    /**
     * @return \DateTime
     */
    public function getQuarterFourBegin()
    {
        return $this->quarterFourBegin;
    }

    /**
     * @param \DateTime $quarterFourBegin
     */
    public function setQuarterFourBegin($quarterFourBegin)
    {
        $this->quarterFourBegin = $quarterFourBegin;
    }

    /**
     * @return \DateTime
     */
    public function getQuarterFourEnd()
    {
        return $this->quarterFourEnd;
    }

    /**
     * @param \DateTime $quarterFourEnd
     */
    public function setQuarterFourEnd($quarterFourEnd)
    {
        $this->quarterFourEnd = $quarterFourEnd;
    }

    /**
     * @return \DateTime
     */
    public function getHolidays()
    {
        return $this->holidays;
    }

    /**
     * @param \DateTime $holidays
     */
    public function setHolidays($holidays)
    {
        $this->holidays = $holidays;
    }


}

