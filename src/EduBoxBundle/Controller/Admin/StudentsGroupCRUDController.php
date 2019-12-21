<?php


namespace EduBoxBundle\Controller\Admin;


use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Form\Type\StudentsGroupType;
use Sonata\AdminBundle\Controller\CRUDController;

class StudentsGroupCRUDController extends CRUDController
{
    public function listAction()
    {
        $repository = $this->get('doctrine.orm.entity_manager')->getRepository(StudentsGroup::class);
        $studentsGroups = $repository->createQueryBuilder('u')
            ->orderBy('u.number', 'ASC')
            ->AddOrderBy('u.letter', 'ASC')
            ->getQuery()->getResult();
        return $this->renderWithExtraParams('EduBoxBundle:Admin:class/list.html.twig', [
            'students_groups' => $studentsGroups,
        ]);
    }

    public function createAction()
    {
        $students_group = new StudentsGroup();
        $form = $this->createForm(StudentsGroupType::class, $students_group);
        $formHandle = $this->get('edubox.students_group_form_handler');

        if ($formHandle->createHandle($form, $this->getRequest()))
        {
            $this->addFlash('success', 'Students group created');
            return $this->redirectToRoute('edubox.admin.students_group_edit', ['id'=>$students_group->getId()]);
        }
        return $this->renderWithExtraParams('EduBoxBundle:Admin:class/create.html.twig', [
            'students_group_form' => $form->createView(),
        ]);
    }

    public function editAction($id = null)
    {
        $repository = $this->get('doctrine.orm.entity_manager')->getRepository(StudentsGroup::class);
        $students_group = $repository->find($id);
        if (!$students_group) {
            throw $this->createNotFoundException('Students group not found');
        }
        $form = $this->createForm(StudentsGroupType::class, $students_group);
        $formHandle = $this->get('edubox.students_group_form_handler');

        if ($formHandle->editHandle($form, $this->getRequest()))
        {
            $this->addFlash('success', 'Students group saved');
        }

        return $this->renderWithExtraParams('EduBoxBundle:Admin:class/edit.html.twig', [
            'students_group_form' => $form->createView(),
        ]);
    }

    public function deleteAction($id)
    {
        if ($this->getRequest()->isMethod('delete')) {
            throw new \Exception('Allows only delete method', 500);
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository(StudentsGroup::class);
        $students_group = $repository->find($id);
        if ($students_group) {
            $em->remove($students_group);
            $em->flush();
            $this->addFlash('success', 'Students group removed');
        } else
        {
            $this->addFlash('warning', 'Students group not found');
        }
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }
}