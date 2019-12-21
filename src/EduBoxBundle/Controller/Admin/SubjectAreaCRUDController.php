<?php


namespace EduBoxBundle\Controller\Admin;


use EduBoxBundle\Entity\SubjectArea;
use EduBoxBundle\Form\Type\SubjectAreaType;
use Sonata\AdminBundle\Controller\CRUDController;

class SubjectAreaCRUDController extends  CRUDController
{
    public function listAction()
    {
        $repository = $this->get('doctrine.orm.entity_manager')->getRepository(SubjectArea::class);
        return $this->renderWithExtraParams('EduBoxBundle:Admin:subject_area/list.html.twig', [
            'subject_areas' => $repository->findAll(),
        ]);
    }

    public function createAction()
    {
        $subject = new SubjectArea();
        $form = $this->createForm(SubjectAreaType::class, $subject);
        $formHandle = $this->get('edubox.subject_area_form_handler');

        if ($formHandle->createHandle($form, $this->getRequest()))
        {
            $this->addFlash('success', 'Subject area created');
            return $this->redirectToRoute('edubox.admin.subject_area_edit', ['id'=>$subject->getId()]);
        }
        return $this->renderWithExtraParams('EduBoxBundle:Admin:subject_area/create.html.twig', [
            'subject_area_form' => $form->createView(),
        ]);
    }

    public function editAction($id = null)
    {
        $repository = $this->get('doctrine.orm.entity_manager')->getRepository(SubjectArea::class);
        $subject_area = $repository->find($id);
        if (!$subject_area) {
            throw $this->createNotFoundException('Subject area not found');
        }
        $form = $this->createForm(SubjectAreaType::class, $subject_area);
        $formHandle = $this->get('edubox.subject_area_form_handler');

        if ($formHandle->editHandle($form, $this->getRequest()))
        {
            $this->addFlash('success', 'Subject area saved');
        }

        return $this->renderWithExtraParams('EduBoxBundle:Admin:subject_area/edit.html.twig', [
            'subject_area_form' => $form->createView(),
        ]);
    }

    public function deleteAction($id)
    {
        if ($this->getRequest()->isMethod('delete')) {
            throw new \Exception('Allows only DELETE method', 500);
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository(SubjectArea::class);
        $subject_area = $repository->find($id);
        if ($subject_area) {
            $em->remove($subject_area);
            $em->flush();
            $this->addFlash('success', 'Subject area removed');
        } else
        {
            $this->addFlash('warning', 'Subject area not found');
        }
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }
}