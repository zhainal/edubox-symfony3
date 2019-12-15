<?php


namespace EduBoxBundle\Controller\Admin;


use EduBoxBundle\Entity\Calendar;
use EduBoxBundle\Form\CalendarType;
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

        $formHandler = $this->get('edubox.edit_calendar_form_handler');

        if ($formHandler->handle($form, $request))
        {
            $this->addFlash('success', 'Calendar saved');
        }

        return $this->renderWithExtraParams('EduBoxBundle:Admin:calendar_edit.html.twig', [
            'calendar' => $form->createView(),
        ]);
    }

    public function createAction()
    {
        $request = $this->getRequest();
        $calendar = new Calendar();
        $form = $this->createForm(CalendarType::class, $calendar, ['new' => true]);

        $formHandler = $this->get('edubox.create_calendar_form_handler');

        if ($formHandler->handle($form, $request))
        {
            $this->addFlash('success', 'Calendar created');

            return $this->redirectToRoute('calendar_edit', ['id' => $calendar->getId()]);
        }

        return $this->renderWithExtraParams('EduBoxBundle:Admin:calendar_create.html.twig', [
            'calendar' => $form->createView(),
        ]);

    }
}