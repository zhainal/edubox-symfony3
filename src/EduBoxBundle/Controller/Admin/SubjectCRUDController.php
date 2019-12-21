<?php


namespace EduBoxBundle\Controller\Admin;


use EduBoxBundle\Entity\Subject;
use EduBoxBundle\Form\Type\SubjectType;
use Sonata\AdminBundle\Controller\CRUDController;

class SubjectCRUDController extends  CRUDController
{
    public function listAction()
    {
        $repository = $this->get('doctrine.orm.entity_manager')->getRepository(Subject::class);
        return $this->renderWithExtraParams('EduBoxBundle:Admin:subject/list.html.twig', [
            'subjects' => $repository->findAll(),
        ]);
    }

    public function createAction()
    {
        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject);
        $formHandle = $this->get('edubox.subject_form_handler');

        if ($formHandle->createHandle($form, $this->getRequest()))
        {
            $this->addFlash('success', 'Subject created');
            return $this->redirectToRoute('edubox.admin.subject_edit', ['id'=>$subject->getId()]);
        }
        return $this->renderWithExtraParams('EduBoxBundle:Admin:subject/create.html.twig', [
            'subject_form' => $form->createView(),
        ]);
    }

    public function editAction($id = null)
    {
        $repository = $this->get('doctrine.orm.entity_manager')->getRepository(Subject::class);
        $subject = $repository->find($id);
        if (!$subject) {
            throw $this->createNotFoundException('Subject not found');
        }
        $form = $this->createForm(SubjectType::class, $subject);
        $formHandle = $this->get('edubox.subject_form_handler');

        if ($formHandle->editHandle($form, $this->getRequest()))
        {
            $this->addFlash('success', 'Subject saved');
        }

        return $this->renderWithExtraParams('EduBoxBundle:Admin:subject/edit.html.twig', [
            'subject_form' => $form->createView(),
        ]);
    }

    public function deleteAction($id)
    {
        if ($this->getRequest()->isMethod('delete')) {
            throw new \Exception('Allows only DELETE method', 500);
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository(Subject::class);
        $subject = $repository->find($id);
        if ($subject) {
            $em->remove($subject);
            $em->flush();
            $this->addFlash('success', 'Subject removed');
        } else
        {
            $this->addFlash('warning', 'Subject not found');
        }
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }
}