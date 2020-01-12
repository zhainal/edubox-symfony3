<?php


namespace EduBoxBundle\DomainManager;


class JournalManager
{
    public function daysCount (array $days) {
        return count($days, COUNT_RECURSIVE);
    }
}