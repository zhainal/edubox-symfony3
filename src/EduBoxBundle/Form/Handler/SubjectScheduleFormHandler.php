<?php


namespace EduBoxBundle\Form\Handler;


use EduBoxBundle\DomainManager\SubjectScheduleManager;
use EduBoxBundle\DomainManager\TeacherManager;
use EduBoxBundle\Entity\SubjectSchedule;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class SubjectScheduleFormHandler
{
    private $subjectScheduleManager;
    private $teacherManager;

    public function __construct(SubjectScheduleManager $subjectScheduleManager, TeacherManager $teacherManager)
    {
        $this->subjectScheduleManager = $subjectScheduleManager;
        $this->teacherManager = $teacherManager;
    }


    public function createHandle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $valid_subject_schedule = $form->getData();
            $this->subjectScheduleManager->create($valid_subject_schedule);
            return true;
        }
        return false;
    }

    public function editHandle(FormInterface &$form, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $valid_subject_schedule = $form->getData();

            foreach ($this->subjectScheduleManager->getDays($valid_subject_schedule) as $day)
            {
                foreach ($this->subjectScheduleManager->getHours($valid_subject_schedule, $day) as $hour) {
                    $subject = $this->subjectScheduleManager->getSubject($valid_subject_schedule, $day, $hour);
                    if ($subject)
                    {
                        $user = $subject->getUser();
                        if ($user)
                        {
                            $lessons = $this->teacherManager->getLessonsBy(
                                $user,
                                $subject,
                                $day,
                                $hour,
                                $valid_subject_schedule->getSubjectSchedulesGroup()
                            );
                            if (count($lessons) > 1) {
                                $form->get('schedule')
                                    ->get($day)
                                    ->get($hour)
                                    ->addError(new FormError('This teacher already has his lesson for this hour'));
                            }
                        }
                    }
                }
            }
            if (!$form->isValid())
            {
                return false;
            }

            $this->subjectScheduleManager->store($valid_subject_schedule);
            return true;
        }
        return false;
    }

}