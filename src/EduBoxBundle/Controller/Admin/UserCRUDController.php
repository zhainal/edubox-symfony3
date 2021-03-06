<?php


namespace EduBoxBundle\Controller\Admin;


use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\User;
use EduBoxBundle\Form\Type\UserType;
use Sonata\AdminBundle\Controller\CRUDController;

class UserCRUDController extends CRUDController
{

    public function listAction($studentsGroupId = null)
    {
        $this->admin->checkAccess('list');
        if ($this->isGranted('ROLE_ADMIN')) {
            return parent::listAction(); // TODO: Change the autogenerated stub
        }
        elseif ($this->isGranted('ROLE_TEACHER')) {
            $studentsGroups = $this->getDoctrine()->getRepository(StudentsGroup::class)->findAll();
            $studentsGroup = $this->get('edubox.students_group_manager')->getObject($studentsGroupId);
            if ($studentsGroup instanceof  StudentsGroup) {
                $students = $this->get('edubox.students_group_manager')->getStudents($studentsGroup);
            }
            else {
                $studentsGroup = null;
                $students = null;
            }
            return $this->renderWithExtraParams('EduBoxBundle:Admin:user/student_list.html.twig', [
                'studentsGroups' => $studentsGroups,
                'students' => $students,
                'studentsGroup' => $studentsGroup,
            ]);
        }
        throw $this->createAccessDeniedException();
    }

    public function showAction($id = null)
    {
        $this->admin->checkAccess('show');
        $user =  $this->get('edubox.user_manager')->getObject($id);
        if (!$user instanceof User) {
            throw $this->createNotFoundException('User not found');
        }
        if (!$user->hasRole('ROLE_STUDENT')) {
            throw $this->createAccessDeniedException();
        }
        $user = $this->get('edubox.student_manager')->withParent($user);
        return $this->renderWithExtraParams('EduBoxBundle:Admin:user/show.html.twig', ['user' => $user]);
    }

    public function editAction($id = null)
    {
        if ($id == null) {
            throw $this->createNotFoundException();
        }
        $this->admin->checkAccess('edit');
        $request = $this->getRequest();

        $formHandler = $this->get('edubox.user_form_handler');

        $form = $this->createForm(UserType::class);

        $formHandler->postCreateForm($form, $id);

        if ($formHandler->editHandle($form, $request)) {
            $form = $this->createForm(UserType::class);
            $formHandler->postCreateForm($form, $id);
            $this->addFlash('success', 'Saved');
        }

        return $this->renderWithExtraParams('EduBoxBundle:Admin:user/edit.html.twig', [
            'user' => $form->createView(),
        ]);
    }

    public function createAction()
    {
        $request = $this->getRequest();
        $formHandler = $this->get('edubox.user_form_handler');
        $userManager = $this->get('edubox.user_manager');
        $user = $userManager->createUser();
        $form = $this->createForm(UserType::class, $user, ['new' => true]);

        if ($user = $formHandler->createHandle($form, $request)) {
            return $this->redirectToRoute('edubox.admin.user_edit', ['id'=>$user->getId()]);
        }

        return $this->renderWithExtraParams('EduBoxBundle:Admin:user/create.html.twig', [
            'new_user_form' => $form->createView(),
        ]);
    }
}