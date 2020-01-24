<?php


namespace EduBoxBundle\Controller\Admin;


use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\Subject;
use Sonata\AdminBundle\Controller\CRUDController;

class StatisticCRUDController extends CRUDController
{
    public function listAction($studentsGroupId = null, $subjectId = null)
    {
        $quarter = $this->get('edubox.quarter_manager')->getQuarter((int)@$_GET['quarter']);
        $studentsGroups = $this->getDoctrine()->getRepository(StudentsGroup::class)->findAll();
        $studentsGroup = $this->get('edubox.students_group_manager')->getObject($studentsGroupId);
        $subject = $this->get('edubox.subject_manager')->getObject($subjectId);
        if ($studentsGroup instanceof StudentsGroup) {
            $subjects = $studentsGroup->getSubjects();
            $settingManager = $this->get('edubox.setting_manager');
            if ($subject instanceof Subject && $subject->hasStudentsGroup($studentsGroup)) {
                $students = $this->get('edubox.students_group_manager')->getStudents($studentsGroup);
                foreach ($this->get('edubox.quarter_manager')->getQuarters() as $_quarter) {
                    $attendance[$_quarter] = $settingManager->getPercent($settingManager->getAttendance($_quarter, $studentsGroup, $subject), true);
                }
                $subAttendance = [];
                foreach ($students as $student) {
                    $student->attendance = $settingManager->getPercent($settingManager->getAttendance($quarter, $studentsGroup, $subject, $student), true);
                    $subAttendance[] = $student;
                }
            }
            else {
                $subject = null;
                foreach ($this->get('edubox.quarter_manager')->getQuarters() as $_quarter) {
                    $attendance[$_quarter] = $settingManager->getPercent($settingManager->getAttendance($_quarter, $studentsGroup), true);
                }
                $subAttendance = null;
            }
        }
        else {
            $attendance = null;
            $studentsGroup = null;
            $subjects = null;
            $attendance = null;
            $subAttendance = null;
        }

        return $this->renderWithExtraParams('EduBoxBundle:Admin:attendance/list.html.twig', [
            'studentsGroups' => $studentsGroups,
            'studentsGroup' => $studentsGroup,
            'subjects' => $subjects,
            'subject' => $subject,
            'attendance' => $attendance,
            'subAttendance' => $subAttendance,
            'quarter' => $quarter,
        ]);

    }

}