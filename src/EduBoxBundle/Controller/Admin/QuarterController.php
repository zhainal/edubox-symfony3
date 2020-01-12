<?php


namespace EduBoxBundle\Controller\Admin;


use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\User;
use EduBoxBundle\Repository\StudentsGroupRepository;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class QuarterController extends CRUDController
{
    public function listAction($studentsGroupId = null)
    {
        $request = $this->getRequest();
        $this->admin->checkAccess('list');
        $user = $this->getUser();
        if ($user instanceof User) {
            if ($user->hasRole('ROLE_ADMIN') || $user->hasRole('ROLE_TEACHER')) {
                return $this->quartersList($studentsGroupId != null ? $studentsGroupId : null );
            }
            elseif ($user->hasRole('ROLE_PARENT')) {
                return $this->parentQuarter((int)$request->get('student'));
            }
            elseif ($user->hasRole('ROLE_STUDENT')) {
                return $this->show($user);
            }
            else {
                throw new \Exception('Access is denied');
            }
        }
        else {
            throw new \Exception('Access is denied');
        }
    }

    public function showAction($studentId = null) {
        if ($studentId != null) {
            $student = $this->getDoctrine()->getRepository(User::class)->find($studentId);
            if (!$student) {
                throw new \Exception('Selected student not found');
            }
        } else {
            throw new \Exception('No student selected');
        }
        $user = $this->getUser();
        if (!$user instanceof User || !$student instanceof User) {
            throw new \Exception('You or the specified user is not authorized');
        }
        if ($user->hasRole('ROLE_ADMIN') || $user->hasRole('ROLE_TEACHER')) {
            return $this->show($student);
        }
        else {
            throw new \Exception('Access denied');
        }
    }

    public function show(User $student)
    {
        if (!$student->hasRole('ROLE_STUDENT')) {
            throw new \Exception('The specified user is not a student');
        }
        $quarterManager = $this->get('edubox.quarter_manager');
        $subjectsWithQuarter = $quarterManager->getQuartersByUser($student);
        return $this->renderWithExtraParams('@EduBox/Admin/quarter/show.html.twig', [
            'subjectsWithQuarter' => $subjectsWithQuarter,
            'student' => $student,
        ]);
    }

    public function quartersList($studentsGroupId = null)
    {
        $studentsGroupRepository = $this->getDoctrine()->getRepository(StudentsGroup::class);
        $studentsGroups = $studentsGroupRepository->findAll();
        if ($studentsGroupId != null) {
            $studentsGroup = $studentsGroupRepository->find($studentsGroupId);
            if ($studentsGroup) {
                $studentsGroupManager = $this->get('edubox.students_group_manager');
                $students = $studentsGroupManager->getStudents($studentsGroup);
            }
            else {
                $studentsGroup = null;
                $students = [];
            }
        }
        else {
            $studentsGroup = null;
            $students = [];
        }
        return $this->renderWithExtraParams('@EduBox/Admin/quarter/list.html.twig', [
            'studentsGroups' => $studentsGroups,
            'studentsGroupId' => $studentsGroupId,
            'students' => $students,
        ]);
    }

    public function parentQuarter($student = 0)
    {
        $parentManager = $this->get('edubox.parent_manager');
        $parent = $this->getUser();

        $oneStudent = $parentManager->hasOneStudent($parent);
        if ($oneStudent instanceof  User) {
            $student = $oneStudent;
        }
        else {
            $student = $this->getDoctrine()->getRepository(User::class)->find($student);
        }
        if ($student instanceof User) {
            if ($parentManager->hasStudent($parent, $student)) {
                return $this->show($student);
            }
        }
        return $this->renderWithExtraParams('EduBoxBundle:Admin:quarter/parent/list.html.twig', [
            'students' => $parentManager->getStudents($parent),
        ]);
    }

}