<?php


namespace EduBoxBundle\Command;


use Doctrine\ORM\EntityManagerInterface;
use EduBoxBundle\DomainManager\LessonManager;
use EduBoxBundle\DomainManager\MarkManager;
use EduBoxBundle\DomainManager\QuarterManager;
use EduBoxBundle\Entity\Lesson;
use EduBoxBundle\Entity\Mark;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\Subject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetLessonsCommand extends Command
{
    protected static $defaultName = 'edubox:reset-lessons';
    private $entityManager;
    private $lessonManager;
    private $markManager;
    private $quarterManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        LessonManager $lessonManager,
        MarkManager $markManager,
        QuarterManager $quarterManager
    ) {
        $this->entityManager = $entityManager;
        $this->lessonManager = $lessonManager;
        $this->markManager = $markManager;
        $this->quarterManager = $quarterManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Delete all lessons and marks, creates lessons and marks with current schedule');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            '',
            'Deleting all lessons and marks...',
            '=================================',
        ]);

        $repository = $this->entityManager->getRepository(Lesson::class);
        $lessonsCount = count($repository->findAll());
        $repository->createQueryBuilder('l')->delete()->getQuery()->execute();
        $output->writeln('Deleted '.$lessonsCount.' lessons.');


        $repository = $this->entityManager->getRepository(Mark::class);
        $marksCount = count($repository->findAll());
        $repository->createQueryBuilder('m')->delete()->getQuery()->execute();
        $output->writeln('Deleted '.$marksCount.' marks.');

        unset($repository);
        unset($marksCount, $lessonsCount);

        $output->writeln([
            '',
            'Creating new lessons and marks...',
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
                            $this->lessonManager->createNotExistsLessons($subject, $studentsGroup, $beginDate, $endDate);
                            //$output->writeln('Marks for '.$quarter.' quarter created.');
                            //$this->markManager->createNotExistsMarks($subject, $studentsGroup, $beginDate, $endDate);
                            $output->writeln('Lessons for '.$quarter.' quarter created.');
                        } else {
                            $output->writeln('Quarter '.$quarter.' not found');
                        }
                    }
                }
            }
        }


        $output->writeln('Done.');
    }

}