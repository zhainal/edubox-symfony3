<?php


namespace EduBoxBundle\Controller\Admin;

use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\Subject;
use EduBoxBundle\Entity\SubjectSchedule;
use EduBoxBundle\Entity\SubjectSchedulesGroup;
use EduBoxBundle\Form\Type\SubjectSchedulesGroupType;
use EduBoxBundle\Form\Type\SubjectScheduleType;
use Sonata\AdminBundle\Controller\CRUDController;

class SubjectScheduleCRUDController extends  CRUDController
{
    public function listAction()
    {
        $this->admin->checkAccess('list');
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->listSubjectSchedulesGroup();
        }
        elseif ($this->isGranted('ROLE_TEACHER')) {
            $this->admin->setLabel('Subject schedule');
            return $this->teacherSubjectSchedule();
        }
    }

    public function teacherSubjectSchedule()
    {
        $user = $this->getUser();
        $schedule = $this->get('edubox.teacher_manager')->getSubjectSchedule($user);
        return $this->renderWithExtraParams('EduBoxBundle:Admin:subject_schedule/teacher/show.html.twig', [
            'schedule' => $schedule
        ]);
    }

    public function listSubjectSchedulesGroup()
    {
        $repository = $this->get('doctrine.orm.entity_manager')->getRepository(SubjectSchedulesGroup::class);
        return $this->renderWithExtraParams('EduBoxBundle:Admin:subject_schedule/list.html.twig', [
            'subject_schedules_groups' => $repository->findAll(),
            'subject_schedules_group_manager' => $this->get('edubox.subject_schedules_group_manager'),
        ]);
    }

    public function createAction()
    {
        $subject_schedules_group = new SubjectSchedulesGroup();
        $form = $this->createForm(SubjectSchedulesGroupType::class, $subject_schedules_group);
        $formHandle = $this->get('edubox.subject_schedules_group_form_handler');

        if ($formHandle->createHandle($form, $this->getRequest())) {
            $this->addFlash('success', 'Subject schedule created');
            return $this->redirectToRoute('edubox.admin.subject_schedule_edit', ['id'=>$subject_schedules_group->getId()]);
        }
        return $this->renderWithExtraParams('EduBoxBundle:Admin:subject_schedule/create.html.twig', [
            'subject_schedule_form' => $form->createView(),
        ]);
    }

    public function editAction($id = null)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository(SubjectSchedulesGroup::class);
        $subject_schedules_group = $repository->find($id);
        if (!$subject_schedules_group) {
            throw $this->createNotFoundException('Subject schedules group not found');
        }
        $settingManager = $this->get('edubox.setting_manager');
        $groupActive = $settingManager->getSetting('subjectSchedulesGroup')->getSettingValue() == $subject_schedules_group->getId();
        $form = $this->createForm(SubjectSchedulesGroupType::class, $subject_schedules_group, ['active' => $groupActive]);
        $formHandle = $this->get('edubox.subject_schedules_group_form_handler');

        if ($formHandle->editHandle($form, $this->getRequest()))
        {
            $this->addFlash('success', 'Subject schedules group saved');
        }

        return $this->renderWithExtraParams('EduBoxBundle:Admin:subject_schedule/edit.html.twig', [
            'subject_schedules_group_form' => $form->createView(),
        ]);
    }

    public function deleteAction($id)
    {
        if ($this->getRequest()->isMethod('delete')) {
            throw new \Exception('Allows only DELETE method', 500);
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository(SubjectSchedulesGroup::class);
        $subject_schedules_group = $repository->find($id);
        if ($subject_schedules_group) {
            $em->remove($subject_schedules_group);
            $em->flush();
            $this->addFlash('success', 'Subject schedules group removed');
        } else
        {
            $this->addFlash('warning', 'Subject schedules group not found');
        }
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }



    public function listScheduleAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $subjectSchedulesGroupRepo = $em->getRepository(SubjectSchedulesGroup::class);
        $subjectSchedulesGroup = $subjectSchedulesGroupRepo->find($id);
        if (!$subjectSchedulesGroup)
        {
            throw $this->createNotFoundException('Subject schedules group not found');
        }
        $studentsGroupRepository = $em->getRepository(StudentsGroup::class);
        $students_groups = $studentsGroupRepository->findAll();
        return $this->renderWithExtraParams('EduBoxBundle:Admin:subject_schedule/listSchedule.html.twig', [
            'students_groups' => $students_groups,
            'subject_schedules_group' => $subjectSchedulesGroup,
            'students_group_manager' => $this->get('edubox.students_group_manager')
        ]);
    }

    public function createScheduleAction($id = null)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository(SubjectSchedulesGroup::class);
        $subject_schedules_group = $repository->find($id);
        if (!$subject_schedules_group)
        {
            throw $this->createNotFoundException('Subject schedules group not found');
        }
        $subject_schedule = new SubjectSchedule();
        $form = $this->createForm(SubjectScheduleType::class, $subject_schedule, ['new' => true]);
        $formHandle = $this->get('edubox.subject_schedule_form_handler');

        if ($formHandle->createHandle($form, $this->getRequest()))
        {
            $subject_schedules_group->addSubjectSchedule($subject_schedule);
            $em->flush();
            $this->addFlash('success', 'Subject schedule created');
            return $this->redirectToRoute('edubox.admin.subject_schedule_editSchedule', ['id'=>$subject_schedule->getId()]);
        }
        return $this->renderWithExtraParams('EduBoxBundle:Admin:subject_schedule/createSchedule.html.twig', [
            'subject_schedule_form' => $form->createView(),
        ]);
    }

    public function editScheduleAction($studentsGroupId, $schedulesGroupId)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $hours = 7;
        $repository = $em->getRepository(StudentsGroup::class);
        $studentsGroup = $repository->find($studentsGroupId);
        if (!$studentsGroup)
        {
            throw $this->createNotFoundException('Students group not found');
        }
        $repository = $em->getRepository(SubjectSchedulesGroup::class);
        $subjectSchedulesGroup = $repository->find($schedulesGroupId);
        if (!$subjectSchedulesGroup)
        {
            throw $this->createNotFoundException('Subject schedules group not found');
        }
        $repository = $em->getRepository(SubjectSchedule::class);
        $subject_schedule = $repository->findOrCreateOneBy([
            'studentsGroup' => $studentsGroup,
            'subjectSchedulesGroup' => $subjectSchedulesGroup,
        ]);

        $form = $this->createForm(SubjectScheduleType::class, $subject_schedule, [
            'hours' => $hours,
            'subjectRepository' => $em->getRepository(Subject::class),
            'studentsGroupId' => $studentsGroupId,
        ]);
        $formHandle = $this->get('edubox.subject_schedule_form_handler');

        if ($formHandle->editHandle($form, $this->getRequest()))
        {
            $this->addFlash('success', 'Subject schedule saved');
        }

        return $this->renderWithExtraParams('EduBoxBundle:Admin:subject_schedule/editSchedule.html.twig', [
            'subject_schedule_form' => $form->createView(),
            'schedules_group_id' => $schedulesGroupId,
            'hours' => $hours,
        ]);
    }

    public function deleteScheduleAction($id)
    {
        if ($this->getRequest()->isMethod('delete')) {
            throw new \Exception('Allows only DELETE method', 500);
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository(SubjectSchedulesGroup::class);
        $subject_schedules_group = $repository->find($id);
        if ($subject_schedules_group) {
            $em->remove($subject_schedules_group);
            $em->flush();
            $this->addFlash('success', 'Subject schedules group removed');
        } else
        {
            $this->addFlash('warning', 'Subject schedules group not found');
        }
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }
}