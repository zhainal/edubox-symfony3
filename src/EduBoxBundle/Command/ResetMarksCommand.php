<?php


namespace EduBoxBundle\Command;


use Doctrine\ORM\EntityManagerInterface;
use EduBoxBundle\DomainManager\LessonManager;
use EduBoxBundle\DomainManager\MarkManager;
use EduBoxBundle\DomainManager\QuarterManager;
use EduBoxBundle\DomainManager\StudentsGroupManager;
use EduBoxBundle\Entity\Mark;
use EduBoxBundle\Entity\Quarter;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\Subject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetMarksCommand extends Command
{
    protected static $defaultName = 'edubox:reset-marks';
    private $entityManager;
    private $lessonManager;
    private $markManager;
    private $quarterManager;
    private $studentsGroupManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        LessonManager $lessonManager,
        MarkManager $markManager,
        QuarterManager $quarterManager,
        StudentsGroupManager $studentsGroupManager
    ) {
        $this->entityManager = $entityManager;
        $this->lessonManager = $lessonManager;
        $this->markManager = $markManager;
        $this->quarterManager = $quarterManager;
        $this->studentsGroupManager = $studentsGroupManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Delete all marks, if demo is true creates marks with current schedule');
        $this->addArgument('hasDemo', InputArgument::OPTIONAL, 'Install demo? (yes/no)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            '',
            'Deleting all marks...',
            '=================================',
        ]);


        $repository = $this->entityManager->getRepository(Mark::class);
        $repository->createQueryBuilder('m')->delete()->getQuery()->execute();
        $output->writeln('Deleted  marks.');

        $repository = $this->entityManager->getRepository(Quarter::class);
        $repository->createQueryBuilder('m')->delete()->getQuery()->execute();
        $output->writeln('Deleted quarters.');

        unset($repository);

        if ($input->getArgument('hasDemo') == 'yes') {
            $output->writeln([
                '',
                'Creating new demo marks...',
                '=================================',
            ]);

            $repository = $this->entityManager->getRepository(StudentsGroup::class);
            $studentsGroups = $repository->findAll();
            foreach ($studentsGroups as $studentsGroup) {
                $output->writeln('===== '.$studentsGroup->getName().' =====');
                foreach ($studentsGroup->getSubjects() as $subject) {
                    if ($subject instanceof Subject) {
                        $output->writeln('== '.$subject->getName().' ==');
                        foreach (range(1,4) as $quarter) {
                            if ($this->quarterManager->hasQuarter($quarter)) {
                                $beginDate = $this->quarterManager->getBeginDate($quarter);
                                $endDate = $this->quarterManager->getEndDate($quarter);
                                $dates = $this->quarterManager->getDates($subject, $studentsGroup, $beginDate, $endDate);
                                $students = $this->studentsGroupManager->getStudents($studentsGroup);
                                foreach ($dates as $date => $hours) {
                                    for ($hour = 1; $hour <= $hours; $hour++) {
                                        foreach ($students as $student) {
                                            if (($mark = rand(1,60)) < 25) {
                                                if ($mark > 0 && $mark < 3) {
                                                    $mark = 'dc';
                                                } else if ($mark > 2 && $mark < 6) {
                                                    $mark = 2;
                                                } else if ($mark > 5 && $mark < 10) {
                                                    $mark = 3;
                                                } else if ($mark > 9 && $mark < 15) {
                                                    $mark = 4;
                                                } else if ($mark > 14 && $mark < 25) {
                                                    $mark = 5;
                                                } else {
                                                    $mark = null;
                                                    continue;
                                                }
                                                $dateObj = (new \DateTime())->setTimestamp(strtotime($date));
                                                $this->markManager->createMark($subject, $student, $mark, $dateObj, $hour, '');
                                            }
                                        }
                                    }
                                }
                            } else {
                                $output->writeln('Quarter '.$quarter.' not found');
                            }
                        }
                    }
                }
            }
        }

        $output->writeln('Done.');
    }

}