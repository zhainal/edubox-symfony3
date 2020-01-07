<?php

namespace EduBoxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Homework
 *
 * @ORM\Table(name="homework")
 * @ORM\Entity(repositoryClass="EduBoxBundle\Repository\HomeworkRepository")
 */
class Homework
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
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var Lesson
     *
     * @ORM\ManyToOne(targetEntity="EduBoxBundle\Entity\Lesson", inversedBy="homeworks")
     */
    private $lesson;


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
     * @return Homework
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
     * Set content
     *
     * @param string $content
     *
     * @return Homework
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
     * Set lesson
     *
     * @param Lesson $lesson
     *
     * @return Homework
     */
    public function setLesson(Lesson $lesson)
    {
        $this->lesson = $lesson;

        return $this;
    }

    /**
     * Get lesson
     *
     * @return Lesson
     */
    public function getLesson()
    {
        return $this->lesson;
    }

    public function __toString()
    {
        return $this->name ? $this->name : 'Homework';
    }
}

