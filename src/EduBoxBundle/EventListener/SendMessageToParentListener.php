<?php


namespace EduBoxBundle\EventListener;


use EduBoxBundle\DomainManager\QuarterManager;
use EduBoxBundle\DomainManager\SMSManager;
use EduBoxBundle\DomainManager\StudentManager;
use EduBoxBundle\Entity\Mark;
use EduBoxBundle\Entity\User;
use EduBoxBundle\Event\MarkCreatedEvent;
use Symfony\Component\Translation\TranslatorInterface;

class SendMessageToParentListener
{
    private $studentManager;
    private $SMSManager;
    private $translator;

    /**
     * SendMessageToParentListener constructor.
     * @param StudentManager $studentManager
     * @param SMSManager $SMSManager
     * @param TranslatorInterface $translator
     */
    public function __construct(StudentManager $studentManager, SMSManager $SMSManager, TranslatorInterface $translator)
    {
        $this->studentManager = $studentManager;
        $this->SMSManager = $SMSManager;
        $this->translator = $translator;
    }

    public function onMarkCreated(MarkCreatedEvent $markCreatedEvent)
    {
        $mark = $markCreatedEvent->getMark();
        $student = $mark->getUser();
        $parent = $this->studentManager->getParent($student);
        if ($parent instanceof User) {
            $this->SMSManager->sendMsg($parent, $this->translator->trans(
                $mark->getMark() == 'dc' ? 'sms.student_marked_n' : 'sms.student_marked', [
                '%student_name%' => $student->getFirstName(),
                '%parent_name%' => $parent->getFullName(),
                '%mark%' => $mark->getMark(),
                '%subject%' => $mark->getSubject()->getName(),
                '%date%' => $mark->getDate()->format('Y.m.d'),
            ], 'EduBoxBundle'));
        }
    }
}