<?php

namespace EduBoxBundle\Event;

use EduBoxBundle\Entity\Mark;
use Symfony\Component\EventDispatcher\Event;

class MarkCreatedEvent extends Event
{
    const MARK_CREATED = 'edubox.mark.created';
    private $mark;

    public function __construct(Mark $mark)
    {
        $this->mark = $mark;
    }

    /**
     * @return Mark
     */
    public function getMark()
    {
        return $this->mark;
    }
}