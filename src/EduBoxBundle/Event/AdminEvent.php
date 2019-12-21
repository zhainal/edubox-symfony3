<?php


namespace EduBoxBundle\Event;


use Symfony\Component\EventDispatcher\Event;

class AdminEvent extends Event
{
    const STUDENT_ADMIN_PRE_CREATE = 'edubox.admin.student.pre_create';

    public function __construct(array $args)
    {
        foreach ($args as $key => &$arg)
        {
            $this->$key = &$arg;
        }
    }
}