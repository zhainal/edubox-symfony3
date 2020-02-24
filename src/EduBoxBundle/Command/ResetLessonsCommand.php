<?php


namespace EduBoxBundle\Command;


use Doctrine\ORM\EntityManagerInterface;
use EduBoxBundle\DomainManager\LessonManager;
use EduBoxBundle\DomainManager\MarkManager;
use EduBoxBundle\DomainManager\QuarterManager;
use EduBoxBundle\Entity\Lesson;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\Subject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
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
        $this->addArgument('hasDemo', InputArgument::OPTIONAL, 'Install demo? (yes/no)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            '',
            'Deleting all lessons...',
            '=================================',
        ]);

        $repository = $this->entityManager->getRepository(Lesson::class);
        $repository->createQueryBuilder('l')->delete()->getQuery()->execute();
        $output->writeln('Deleted lessons.');

        unset($repository);

        $output->writeln([
            '',
            'Creating new lessons...',
            '=================================',
        ]);
        $lessonContents = [
            '<p style=\"text-align: justify;\">Kompýuterler – adamyň işinde, ýaşaýyş durmuşynda onuň ygtybarly kömekçisidir. Kompýuteriň kömegi bilen howa maglumaty düzülýär, uçarlaryň, maşynlaryň çyzgylary taýýarlanýar, näsaglaryň kesellerini anyklaýyş işleri ýerine ýetirilýär, multfilmler, saz kompozisiýalary we ş.m. döredilýär. Kompýuter otlularyň gatnawy baradaky informasiýany hödürleýär, internet (www.ylymly.com) dükanlardan söwda etmäge, banklarda adamlaryň töleglerini tölemäge mümkinçilik berýär. Kompýuterleriň görnüşleriniň we ýerine ýetirýän işleriniň dürlüligine garamazdan, olaryň düzümi birmeňzeşdir.</p>
<p style=\"text-align: justify;\">Her bir kompýuter <strong>sistema blogundan,</strong> <strong>monitordan, klawiaturadan </strong>we <strong>syçandan </strong>durýar. Bu düzüm bölekleriniň her biri kesgitli wezipeleri ýerine ýetirýärler.</p>
<p style=\"text-align: justify;\">Mundan başga-da kompýutere <strong>skaner</strong> we <strong>printer</strong> ýaly gurluşlar hem çatylyp bilner. Giriş we çykyş gurluşlary <em>sistema bloguna </em>çatylýar. Sistema blogunda informasiýalary işläp taýýarlamagy we saklamagy amala aşyrýan möhüm gurluşlar ýerleşendir. <em>Sistema blogy </em>dürli görnüşlerde bolup biler.</p>
<p style=\"text-align: justify;\"><strong>Monitor</strong> (oňa <em>displeý </em>hem diýilýär) – işlenip taýýarlanan informasiýalaryň netijelerini ekrana çykarýan gurluşdyr.</p>
<p style=\"text-align: justify;\"><strong>Klawiatura</strong> – kompýutere tekst informasiýalaryny we kompýuteri dolandyrýan buýruklary girizmek üçin ulanylýan gurluşdyr. <em>Klawiatura </em><strong>– </strong>kompýutere informasiýalary girizýän esasy gurluşdyr. Klawişleri şertleýin bäş topara bölmek bolar.</p>
<p style=\"text-align: justify;\">1) belgileri girizmek üçin niýetlenen elipbiý-sifrli klawişler;</p>
<p style=\"text-align: justify;\">2) klawiaturanyň iş kadasyny üýtgedýän dolandyryjy klawişler;</p>
<p style=\"text-align: justify;\">3) kursory aşak-ýokary, çepe-saga süýşürýän, ýagny kursory dolandyryjy klawişler;</p>
<p style=\"text-align: justify;\">4) sanlary we arifmetiki amallaryň belgilerini girizýän goşmaça sifr klawişler;</p>
<p style=\"text-align: justify;\">5) dürli programmalarda dürli işleri ýerine ýetirýän funksional klawişler (meselem: F1 klawişi köplenç, şol programmany ulanmakda we kömekçini çagyrmak üçin peýdalanylýar).</p>
<p style=\"text-align: justify;\">Teksti girizmek üçin niýetlenen her klawişiň ýüzünde adatça iki belgi: iňlis we rus harpy şekillendirilendir. &nbsp;Elipbiýleri birinden beýlekisine geçirmegiň usuly (<strong>Alt +Shift; Ctrl+Shift; Shift+Shift</strong>) klawiaturanyň sazlanyşyna baglydyr. Klawişleriň atlarynyň arasyndaky <strong>«+» </strong>belgisi 1-nji klawişi basyp saklap, soň 2-nji klawişi basmalydygyny, ýagny klawişleri utgaşdyryp basmalydygyny aňladýar. 2.6-njy suratda <strong>Ctrl + Shift </strong>klawişleri utgaşykly ulanmagyň amallary görkezilendir.</p>
<p style=\"text-align: justify;\">Her elipbiýiň harplary baş we setir harp görnüşinde bolup biler. Eger diňe bir baş harp gerek bolsa, ondaklawişleriň <strong>Shift+harp </strong>utgaşmasyny ulanmak amatlydyr. Eger birnäçe baş harp gerek bolsa, <strong>Caps Lock </strong>klawişi basmak maslahat berilýär. Klawiaturanyň elipbiý-sifirli böleginiň ýokarky hatarynyň klawişlerinde sifrler we simwollar şekillendirilendir. Şol klawişlere basylanda, olardaky aşaky simwollar girizilýär. Ýokarky simwollary girizmek üçin bolsa, <strong>Shift </strong>we girizilýän simwolyň klawişi utgaşykly basylýar. Tekst girizilende, sözleriň arasy boşluk klawişini basmak arkaly bölünýär. Informasiýanyň girizilendigini tassyklamak üçin <strong>Enter </strong>klawişi, buýruklary ýatyrmak üçin <strong>ESC </strong>klawişi ulanylýar. Klawiatura bilen işlemegi öwretmek üçin, klawiatura türgenleşdiriji ýörite programmalar ulanylýar. Olaryň kömegi bilen tekstleri ýazmagyň usullaryny öwrenip bolar. Bu usulda klawiaturanyň her bir klawişine çep ýada sag eliň barmaklary degişli edilýär we gerekli ýerinde barmaklar şol harplary girizýärler. Kompýuteriň ekranynda obýektleri görkezmek, surat çekmek we ş.m. işler üçin syçany ulanmak bolar.</p>
<p style=\"text-align: justify;\"><strong>Tekst kursory </strong>– bu ýanyp-sönüp duran dik kesimi ýada salýar. Ol klawiaturadan giriziljek belginiň ýazyljak ýerini görkezýär. Syçanyň <strong>kursory </strong>bolsa ýapgyt ugur görkezgiç (peýkam oky) görnüşindedir. Syçanyň süýşürilmegi bilen ekranda kursor hem süýşýär. Syçanyň bir tigirjigi we iki sany gulagy (çep we sag düwmesi) bolýar. Tigirjik adatça tekst gözden geçirilende ulanylýar. Syçanyň çep gulagy bir gezek ýa-da iki gezek çalt basylyp ulanylýar. Eger siz sag eliňiz bilen işleýän bolsaňyz, onda syçanyň çep gulagy esasydyr.</p>
<p style=\"text-align: justify;\"><strong>Printer</strong> – tekst we grafiki informasiýalary kagyza çykarýan enjamdyr. Printerleriň matrisaly, akymly we lazer görnüşleri bardyr.<strong>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></p>
<p style=\"text-align: justify;\"><strong>Çeşme:</strong>&nbsp;Türkmenistanyň Bilim ministrligi tarapyndan taýýarlanan, 6-njy synplar üçin “Informatika we informasiýa tehnologiýalary” dersi boýunça okuw kitaby</p>',
        ];
        $lessonNames = [
            "§1. Web-sahypalary döretmekde ofis programmalaryny ulanmak",
            "§2. HTML dokumentiniň Gipertekst belgilemeleriniň esaslary.",
            "§3. HTML dokumente fon reňkini we şekilini bermek",
            "§4. Tekst bilen işlemek",
            "§5. Internet üçin şekilleri taýýarlamak.",
            "§6. Tegler we gipersalgylanmalar",
            "§7. Sanawlar",
            "§8. Web-sahypalary bezemegiň elementleri.",
            "§9. Tablisalar",
            "§10. Görüş arkaly (wizual) web-konstruirlemegiň redaktory",
            "§11. Dürli dersler boýunça saýt parçalaryny işläp taýýarlamak.",
            "§12. Programmalaşdyrma dilinde faýl düşünjesi.",
            "§13. Faýllaryň üýtgeýän ululyklarda beýan edilişi. ",
            "§14. Faýldan okamak ýa-da ýazmak. Faýly ýapmak.",
            "§15. Faýllar bilen işlemeklige degişli dürli mysallar",
            "§16. Kompýuterde modelleri taýýarlamagyň we derňemegiň esasy tapgyrlary",
            "§17.Matematiki modelleri derňemek",
            "§18. Fiziki modelleri derňemek",
            "§19. Biologiki modelirleme",
            "§20.Ykdysady modelirleme",
            "§21. Maglumatlar bazasy barada düşünje",
            "§22. Maglumatlar bazasyny dolandyryş sistemasy (mbds)",
            "§23. Ms access mbds-niň interfeýsiniň esasy elementleri",
            "§24. Maglumatlar bazasynyň tablisalaryny döretmek.",
            "§25. Iki tablisany baglanyşdyrmak",
            "§26. Maglumatlary saýlamaga degişli talaplary düzmek",
            "§27. Maglumatlary tertipleşdirmek",
            "§28. Forma döretmek",
            "§29. Hasabatlary döretmek",
            "§30. Informasiýa sistemalary we tehnologiýalary barada düşünje",
            "§31. Häzirkizaman internet-tehnologiýalarynyň ösüş ugurlary.",
            "§32. Informasiýa jemgyýeti. Informasion medeniýetlilik. ",
            "§33. Informasiýalary goramak.",
        ];
        $lessonNum = 0;

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

                            if ($input->getArgument('hasDemo') == 'yes') {
                                $dates = $this->quarterManager->getDates($subject, $studentsGroup, $beginDate, $endDate);
                                foreach ($dates as $date => $hours) {
                                    for ($hour = 1; $hour <= $hours; $hour++) {
                                        $dateObj = (new \DateTime())->setTimestamp(strtotime($date));
                                        $lesson = $this->lessonManager->getLesson($subject, $studentsGroup, $dateObj, $hour);
                                        if ($lesson instanceof Lesson) {
                                            $lesson->setName($lessonNames[$lessonNum]);
                                            $lesson->setContent($lessonContents[0]);
                                            $lessonNum++;
                                            if ($lessonNum >= count($lessonNames)) $lessonNum = 0;
                                        }
                                    }
                                }
                            }
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