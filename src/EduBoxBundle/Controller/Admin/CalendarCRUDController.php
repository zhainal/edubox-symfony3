<?php


namespace EduBoxBundle\Controller\Admin;


use EduBoxBundle\Entity\Calendar;
use EduBoxBundle\Form\Type\CalendarType;
use Sonata\AdminBundle\Controller\CRUDController;

class CalendarCRUDController extends CRUDController
{
    public function editAction($id = null)
    {
        $request = $this->getRequest();
        $calendar = $this->admin->getModelManager()->find(Calendar::class, $id);
        if (!$calendar) {
            throw $this->createNotFoundException('Calendar not found');
        }
        $form = $this->createForm(CalendarType::class, $calendar);

        $formHandler = $this->get('edubox.calendar_form_handler');

        if ($formHandler->editHandle($form, $request))
        {
            $this->addFlash('success', 'Calendar saved');
        }

        return $this->renderWithExtraParams('EduBoxBundle:Admin:calendar/edit.html.twig', [
            'calendar' => $form->createView(),
        ]);
    }

    public function createAction()
    {
        $request = $this->getRequest();
        $calendar = new Calendar();
        $form = $this->createForm(CalendarType::class, $calendar, ['new' => true]);

        $formHandler = $this->get('edubox.calendar_form_handler');

        if ($formHandler->createHandle($form, $request))
        {
            $this->addFlash('success', 'Calendar created');

            return $this->redirectToRoute('edubox.admin.calendar_edit', ['id' => $calendar->getId()]);
        }

        return $this->renderWithExtraParams('EduBoxBundle:Admin:calendar/create.html.twig', [
            'calendar' => $form->createView(),
        ]);

    }
}