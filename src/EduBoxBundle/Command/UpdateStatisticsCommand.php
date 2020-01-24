<?php


namespace EduBoxBundle\Command;


use Doctrine\ORM\EntityManager;
use EduBoxBundle\DomainManager\QuarterManager;
use EduBoxBundle\DomainManager\SettingManager;
use EduBoxBundle\DomainManager\StatisticManager;
use EduBoxBundle\Entity\StudentsGroup;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateStatisticsCommand extends Command
{
    protected static $defaultName = 'edubox:statistic:update';

    private $enityManager;
    private $statisticManager;
    private $quarterManager;

    public function __construct(EntityManager $entityManager, StatisticManager $statisticManager, QuarterManager $quarterManager)
    {
        $this->enityManager = $entityManager;
        $this->statisticManager = $statisticManager;
        $this->quarterManager = $quarterManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Update or create all statistics');
        $this->addArgument('quarter', InputArgument::OPTIONAL, 'Which quarter?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $quarter = $input->getArgument('quarter');
        $output->writeln([
            'Updating records...',
            '===================',
        ]);
        $studentsGroups = $this->enityManager->getRepository(StudentsGroup::class)->findAll();
        foreach ($studentsGroups as $studentsGroup) {
            if (!$this->quarterManager->hasQuarter($quarter)) {
                $quarters = $this->quarterManager->getQuarters();
                foreach ($quarters as $_quarter) {
                    $output->writeln([
                        '',
                        'Updating records for '.$studentsGroup->getName().', quarter: '.$_quarter,
                    ]);
                    $this->statisticManager->updateAttendance($studentsGroup, $_quarter);
                    $this->statisticManager->updatePerformance($studentsGroup, $_quarter);
                }
            }
            else {
                $output->writeln([
                    '',
                    'Updating records for '.$studentsGroup->getName(),
                ]);
                $this->statisticManager->updateAttendance($studentsGroup, $quarter);
                $this->statisticManager->updatePerformance($studentsGroup, $quarter);
            }
        }


    }
}