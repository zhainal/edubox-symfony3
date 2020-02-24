<?php


namespace EduBoxBundle\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use EduBoxBundle\DomainManager\ParentManager;
use EduBoxBundle\DomainManager\StudentManager;
use EduBoxBundle\DomainManager\UserManager;
use EduBoxBundle\Entity\Calendar;
use EduBoxBundle\Entity\Holiday;
use EduBoxBundle\Entity\StudentsGroup;
use EduBoxBundle\Entity\Subject;
use EduBoxBundle\Entity\SubjectArea;
use EduBoxBundle\Entity\SubjectSchedule;
use EduBoxBundle\Entity\SubjectSchedulesGroup;
use EduBoxBundle\Entity\User;
use EduBoxBundle\Entity\UserMeta;
use Sonata\Doctrine\Model\ManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EduBoxFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $studentsGroups = [
            1 => [
                'number' => 11,
                'letter' => 'A',
            ],
            2 => [
                'number' => 11,
                'letter' => 'B',
            ],
            3 => [
                'number' => 11,
                'letter' => 'Ç',
            ],
            4 => [
                'number' => 11,
                'letter' => 'D',
            ],
        ];
        foreach ($studentsGroups as $key => $item) {
            $studentsGroup = new StudentsGroup();
            $studentsGroup->setLetter($item['letter']);
            $studentsGroup->setNumber($item['number']);
            $manager->persist($studentsGroup);
            $studentsGroups[$key]['object'] = $studentsGroup;
        }
        $manager->flush();

        // Creating subject areas
        $subjectAres = [
            1 => [
                "name" => "Takyk"
            ],
            2 => [
                "name" => "Ynsanperwer"
            ],
            3 => [
                "name" => "Tebigy"
            ],
        ];
        foreach ($subjectAres as $key => $item) {
            $subjectArea = new SubjectArea();
            $subjectArea->setName($item['name']);
            $manager->persist($subjectArea);
            $subjectAres[$key]['object'] = $subjectArea;
        }
        $manager->flush();


        // Create users
        $users = [
            1 => ["username" => "gurbantach", "email" => "gurbantach@mail.com", "phone" => "phone", "lastname" => "Amanowa", "firstname" => "Gurbantaç", "gender" => "2", "birthday" => "20.08.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            2 => ["username" => "jahan", "email" => "jahan@mail.com", "phone" => "", "lastname" => "Arazowa", "firstname" => "Jahan", "gender" => "2", "birthday" => "14.11.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            3 => ["username" => "dursuntagan", "email" => "dursuntagan@mail.com", "phone" => "", "lastname" => "Altyyewa", "firstname" => "Dursuntagan", "gender" => "2", "birthday" => "12.09.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            4 => ["username" => "nazsoltan", "email" => "nazsoltan@mail.com", "phone" => "", "lastname" => "Annadurdyýewa", "firstname" => "Nazsoltan", "gender" => "2", "birthday" => "05.09.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            5 => ["username" => "dawut", "email" => "dawut@mail.com", "phone" => "", "lastname" => "Eminow", "firstname" => "Dawut", "gender" => "1", "birthday" => "30.04.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            6 => ["username" => "gulperi", "email" => "gulperi@mail.com", "phone" => "", "lastname" => "Geldiyewa", "firstname" => "Gülperi", "gender" => "2", "birthday" => "01.12.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            7 => ["username" => "ayjahan", "email" => "ayjahan@mail.com", "phone" => "", "lastname" => "Geldimammedowa", "firstname" => "Aýjahan", "gender" => "2", "birthday" => "02.12.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            8 => ["username" => "bibimeryem", "email" => "bibimeryem@mail.com", "phone" => "", "lastname" => "Guljagazowa", "firstname" => "Bibimeyrem", "gender" => "2", "birthday" => "27.04.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            9 => ["username" => "yunus", "email" => "yunus@mail.com", "phone" => "", "lastname" => "Gulyyew", "firstname" => "Ýunus", "gender" => "1", "birthday" => "15.09.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            10 => ["username" => "arslan", "email" => "arslan@mail.com", "phone" => "", "lastname" => "Hallyyew", "firstname" => "Arslan", "gender" => "1", "birthday" => "25.05.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            11 => ["username" => "arazdurdy", "email" => "arazdurdy@mail.com", "phone" => "", "lastname" => "Halymow", "firstname" => "Arazdurdy", "gender" => "1", "birthday" => "13.11.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            12 => ["username" => "oguljan", "email" => "oguljan@mail.com", "phone" => "", "lastname" => "Halmammedowa", "firstname" => "Oguljahan", "gender" => "2", "birthday" => "27.07.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            13 => ["username" => "ogulgerek", "email" => "ogulgerek@mail.com", "phone" => "", "lastname" => "Pudakowa", "firstname" => "Ogulgerek", "gender" => "2", "birthday" => "30.01.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            14 => ["username" => "arzuwmyrat", "email" => "arzuwmyrat@mail.com", "phone" => "", "lastname" => "Ilyasow", "firstname" => "Arzuwmyrat", "gender" => "1", "birthday" => "25.10.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            15 => ["username" => "gulsenem", "email" => "gulsenem@mail.com", "phone" => "", "lastname" => "Işangulyýewa", "firstname" => "Gülsenem", "gender" => "2", "birthday" => "04.05.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            16 => ["username" => "okuwcy", "parent" => 123, "email" => "durdygylych@mail.com", "phone" => "", "lastname" => "Mergenow", "firstname" => "Durdygylyç", "gender" => "1", "birthday" => "26.08.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            17 => ["username" => "meylis", "email" => "meylis@mail.com", "phone" => "", "lastname" => "Mammedow", "firstname" => "Meylis", "gender" => "1", "birthday" => "01.06.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            18 => ["username" => "sapargul", "email" => "sapargul@mail.com", "phone" => "", "lastname" => "Meredowa", "firstname" => "Sapargül", "gender" => "2", "birthday" => "20.07.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            19 => ["username" => "nurtach", "email" => "nurtach@mail.com", "phone" => "", "lastname" => "Mämmedowa", "firstname" => "Nurtäç", "gender" => "2", "birthday" => "28.02.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            20 => ["username" => "rahymguly", "email" => "rahymguly@mail.com", "phone" => "", "lastname" => "Omarow", "firstname" => "Rahymguly", "gender" => "1", "birthday" => "09.05.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            21 => ["username" => "bayramgul", "email" => "bayramgul@mail.com", "phone" => "", "lastname" => "Orazowa", "firstname" => "Bayramgül", "gender" => "2", "birthday" => "24.03.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            22 => ["username" => "annabagt", "email" => "annabagt@mail.com", "phone" => "", "lastname" => "Oraztaganowa", "firstname" => "Annabagt", "gender" => "2", "birthday" => "23.12.2001", "role" => "ROLE_STUDENT", "class" => "1"],
            23 => ["username" => "aygul2", "email" => "aygul2@mail.com", "phone" => "", "lastname" => "Paşakowa", "firstname" => "Aýgül", "gender" => "2", "birthday" => "14.12.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            24 => ["username" => "bibimeryem2", "email" => "bibimeryem2@mail.com", "phone" => "", "lastname" => "Sarybekowa", "firstname" => "Bibimeyrem", "gender" => "2", "birthday" => "22.01.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            25 => ["username" => "ogulgurban", "email" => "ogulgurban@mail.com", "phone" => "", "lastname" => "Seyitjanowa", "firstname" => "Ogulgurban", "gender" => "2", "birthday" => "23.02.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            26 => ["username" => "guljahan", "email" => "guljahan@mail.com", "phone" => "", "lastname" => "Seyitgeldiyewa", "firstname" => "Güljahan", "gender" => "2", "birthday" => "10.06.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            27 => ["username" => "begdurdy", "email" => "begdurdy@mail.com", "phone" => "", "lastname" => "Şadurdyýew", "firstname" => "Begdurdy", "gender" => "1", "birthday" => "23.06.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            28 => ["username" => "ogulsenem", "email" => "ogulsenem@mail.com", "phone" => "", "lastname" => "Nedirowa", "firstname" => "Ogulsenem", "gender" => "2", "birthday" => "23.10.2002", "role" => "ROLE_STUDENT", "class" => "1"],
            29 => ["username" => "sulgun", "email" => "sulgun@mail.com", "phone" => "", "lastname" => "Annaberdiýewa", "firstname" => "Sülgün", "gender" => "1", "birthday" => "27.12.2001", "role" => "ROLE_STUDENT", "class" => "2"],
            30 => ["username" => "azatgul", "email" => "azatgul@mail.com", "phone" => "", "lastname" => "Atamämmedowa", "firstname" => "Azatgül", "gender" => "1", "birthday" => "29.10.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            31 => ["username" => "oguldursun", "email" => "oguldursun@mail.com", "phone" => "", "lastname" => "Annameredowa", "firstname" => "Oguldursun", "gender" => "1", "birthday" => "01.07.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            32 => ["username" => "hydyrtagan", "email" => "hydyrtagan@mail.com", "phone" => "", "lastname" => "Bayramdurdyyew", "firstname" => "Hydyrtagan", "gender" => "2", "birthday" => "06.06.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            33 => ["username" => "omar", "email" => "omar@mail.com", "phone" => "", "lastname" => "Durdyyew", "firstname" => "Omar", "gender" => "2", "birthday" => "01.06.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            34 => ["username" => "jennet", "email" => "jennet@mail.com", "phone" => "", "lastname" => "Derýaýewa", "firstname" => "Jennet", "gender" => "1", "birthday" => "01.09.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            35 => ["username" => "osman", "email" => "osman@mail.com", "phone" => "", "lastname" => "Durdyýew", "firstname" => "Osman", "gender" => "1", "birthday" => "01.06.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            36 => ["username" => "ogulbabek", "email" => "ogulbabek@mail.com", "phone" => "", "lastname" => "Durdyýewa", "firstname" => "Ogulbäbek", "gender" => "2", "birthday" => "19.08.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            37 => ["username" => "guncha", "email" => "guncha@mail.com", "phone" => "", "lastname" => "Eminowa", "firstname" => "Gunça", "gender" => "2", "birthday" => "07.07.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            38 => ["username" => "kuwwat", "email" => "kuwwat@mail.com", "phone" => "", "lastname" => "Guwançberdiýew", "firstname" => "Kuwwat", "gender" => "1", "birthday" => "03.04.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            39 => ["username" => "rahman", "email" => "rahman@mail.com", "phone" => "", "lastname" => "Gurbanow", "firstname" => "Rahman", "gender" => "1", "birthday" => "23.03.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            40 => ["username" => "batyr", "email" => "batyr@mail.com", "phone" => "", "lastname" => "Geldiýew", "firstname" => "Batyr", "gender" => "1", "birthday" => "21.12.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            41 => ["username" => "remezan", "email" => "remezan@mail.com", "phone" => "", "lastname" => "Gündogdyýew", "firstname" => "Remezan", "gender" => "1", "birthday" => "09.11.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            42 => ["username" => "guwanch", "email" => "guwanch@mail.com", "phone" => "", "lastname" => "Habylow", "firstname" => "Guwanç", "gender" => "1", "birthday" => "06.06.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            43 => ["username" => "sonagul", "email" => "sonagul@mail.com", "phone" => "", "lastname" => "Hojanazarowa", "firstname" => "Sonagül", "gender" => "2", "birthday" => "18.02.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            44 => ["username" => "guwanch2", "email" => "2@mail.com", "phone" => "", "lastname" => "Hojagulyýew", "firstname" => "Guwanç", "gender" => "1", "birthday" => "28.04.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            45 => ["username" => "tawus", "email" => "tawus@mail.com", "phone" => "", "lastname" => "Mammetberdiyewa", "firstname" => "Tawus", "gender" => "2", "birthday" => "29.10.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            46 => ["username" => "gulnara", "email" => "gulnara@mail.com", "phone" => "", "lastname" => "Kişşikowa", "firstname" => "Gülnara", "gender" => "2", "birthday" => "08.08.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            47 => ["username" => "eziznazar", "email" => "eziznazar@mail.com", "phone" => "", "lastname" => "Nurnazarow", "firstname" => "Eziznazar", "gender" => "1", "birthday" => "29.01.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            48 => ["username" => "sary", "email" => "sary@mail.com", "phone" => "", "lastname" => "Saryýew", "firstname" => "Sary", "gender" => "1", "birthday" => "01.02.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            49 => ["username" => "sagul", "email" => "sagul@mail.com", "phone" => "", "lastname" => "Şyhyýewa", "firstname" => "Şagül", "gender" => "2", "birthday" => "20.12.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            50 => ["username" => "owadanperi", "email" => "owadanperi@mail.com", "phone" => "", "lastname" => "Tarhanowa", "firstname" => "Owadanperi", "gender" => "2", "birthday" => "02.08.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            51 => ["username" => "yazmyrat", "email" => "yazmyrat@mail.com", "phone" => "", "lastname" => "Mämmetjanow", "firstname" => "Ýazmyrat", "gender" => "1", "birthday" => "25.03.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            52 => ["username" => "mammetweli", "email" => "mammetweli@mail.com", "phone" => "", "lastname" => "Seýitgulyýew", "firstname" => "Mämmetweli", "gender" => "1", "birthday" => "03.05.2002", "role" => "ROLE_STUDENT", "class" => "2"],
            53 => ["username" => "orazgul", "email" => "orazgul@mail.com", "phone" => "", "lastname" => "Şanurowa", "firstname" => "Orazgül", "gender" => "2", "birthday" => "25.05.2006", "role" => "ROLE_STUDENT", "class" => "2"],
            54 => ["username" => "jennet2", "email" => "jennet2@mail.com", "phone" => "", "lastname" => "Altyyewa", "firstname" => "Jennet", "gender" => "2", "birthday" => "28.02.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            55 => ["username" => "dayanch", "email" => "dayanch@mail.com", "phone" => "", "lastname" => "Altyýew", "firstname" => "Daýanç", "gender" => "1", "birthday" => "25.03.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            56 => ["username" => "nartach", "email" => "nartach@mail.com", "phone" => "", "lastname" => "Agöýliýewa", "firstname" => "Nartaç", "gender" => "2", "birthday" => "12.08.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            57 => ["username" => "aysoltan", "email" => "aysoltan@mail.com", "phone" => "", "lastname" => "Aşyrowa", "firstname" => "Aýsoltan", "gender" => "2", "birthday" => "06.09.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            58 => ["username" => "almagul", "email" => "almagul@mail.com", "phone" => "", "lastname" => "Aşyrowa", "firstname" => "Almagül", "gender" => "2", "birthday" => "16.09.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            59 => ["username" => "hatyja", "email" => "hatyja@mail.com", "phone" => "", "lastname" => "Annagylyjowa", "firstname" => "Hatyja", "gender" => "2", "birthday" => "11.09.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            60 => ["username" => "gurbanberdi", "email" => "gurbanberdi@mail.com", "phone" => "", "lastname" => "Annamenliyew", "firstname" => "Gurbanberdi", "gender" => "1", "birthday" => "01.04.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            61 => ["username" => "gultach", "email" => "gultach@mail.com", "phone" => "", "lastname" => "Begniyazowa", "firstname" => "Gültaç", "gender" => "2", "birthday" => "30.12.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            62 => ["username" => "bayramguly", "email" => "bayramguly@mail.com", "phone" => "", "lastname" => "Babagulyýew", "firstname" => "Baýramguly", "gender" => "1", "birthday" => "25.10.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            63 => ["username" => "emine", "email" => "emine@mail.com", "phone" => "", "lastname" => "Bugrayewa", "firstname" => "Emine", "gender" => "2", "birthday" => "06.06.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            64 => ["username" => "dilegmammet", "email" => "dilegmammet@mail.com", "phone" => "", "lastname" => "Baýramow", "firstname" => "Dilegmämmet", "gender" => "1", "birthday" => "08.11.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            65 => ["username" => "gulistan", "email" => "gulistan@mail.com", "phone" => "", "lastname" => "Duşmuhammedowa", "firstname" => "Gülistan", "gender" => "2", "birthday" => "01.04.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            66 => ["username" => "tuwakgul", "email" => "tuwakgul@mail.com", "phone" => "", "lastname" => "Goçmammedowa", "firstname" => "Tuwakgül", "gender" => "2", "birthday" => "04.08.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            67 => ["username" => "kadyr", "email" => "kadyr@mail.com", "phone" => "", "lastname" => "Gurdow", "firstname" => "Kadyr", "gender" => "1", "birthday" => "25.10.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            68 => ["username" => "nazar", "email" => "nazar@mail.com", "phone" => "", "lastname" => "Hojanazarowa", "firstname" => "Nazar", "gender" => "2", "birthday" => "11.07.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            69 => ["username" => "aygul", "email" => "aygul@mail.com", "phone" => "", "lastname" => "Hojaýewa", "firstname" => "Aygül", "gender" => "2", "birthday" => "17.09.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            70 => ["username" => "hajybibi", "email" => "hajybibi@mail.com", "phone" => "", "lastname" => "Muhyyewa", "firstname" => "Hajybibi", "gender" => "2", "birthday" => "04.08.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            71 => ["username" => "nazargojak", "email" => "nazargojak@mail.com", "phone" => "", "lastname" => "Niyazberdiyew", "firstname" => "Nazargojak", "gender" => "1", "birthday" => "21.07.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            72 => ["username" => "serdar", "email" => "serdar@mail.com", "phone" => "", "lastname" => "Ödemammedow", "firstname" => "Serdar", "gender" => "1", "birthday" => "13.03.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            73 => ["username" => "ogulhally", "email" => "ogulhally@mail.com", "phone" => "", "lastname" => "Poladowa", "firstname" => "Ogulhally", "gender" => "2", "birthday" => "27.08.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            74 => ["username" => "alymyrat", "email" => "alymyrat@mail.com", "phone" => "", "lastname" => "Sarygulow", "firstname" => "Alymyrat", "gender" => "1", "birthday" => "21.07.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            75 => ["username" => "welimyrat", "email" => "welimyrat@mail.com", "phone" => "", "lastname" => "Sarygulow", "firstname" => "Welimyrat", "gender" => "1", "birthday" => "21.07.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            76 => ["username" => "chekir", "email" => "chekir@mail.com", "phone" => "", "lastname" => "Şanazarow", "firstname" => "Çekir", "gender" => "1", "birthday" => "23.10.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            77 => ["username" => "gozel", "email" => "gozel@mail.com", "phone" => "", "lastname" => "Taşliýewa", "firstname" => "Gözel", "gender" => "2", "birthday" => "19.03.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            78 => ["username" => "nazarmammet", "email" => "nazarmammet@mail.com", "phone" => "", "lastname" => "Tuwakdurdyyew", "firstname" => "Nazarmammet", "gender" => "1", "birthday" => "15.10.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            79 => ["username" => "arzygul", "email" => "arzygul@mail.com", "phone" => "", "lastname" => "Berdiýewa", "firstname" => "Arzygül", "gender" => "2", "birthday" => "21.06.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            80 => ["username" => "agagul", "email" => "agagul@mail.com", "phone" => "", "lastname" => "Tuwakowa", "firstname" => "Agagül", "gender" => "2", "birthday" => "09.11.2002", "role" => "ROLE_STUDENT", "class" => "3"],
            81 => ["username" => "ahmet", "email" => "ahmet@mail.com", "phone" => "", "lastname" => "Atayew", "firstname" => "Ahmet", "gender" => "1", "birthday" => "30.12.2001", "role" => "ROLE_STUDENT", "class" => "4"],
            82 => ["username" => "gulzada", "email" => "gulzada@mail.com", "phone" => "", "lastname" => "Aşyrowa", "firstname" => "Gülzada", "gender" => "2", "birthday" => "09.06.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            83 => ["username" => "jennetgul", "email" => "jennetgul@mail.com", "phone" => "", "lastname" => "Amanowa", "firstname" => "Jennetgül", "gender" => "2", "birthday" => "10.10.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            84 => ["username" => "zuleyha", "email" => "zuleyha@mail.com", "phone" => "", "lastname" => "Amanaliýewa", "firstname" => "Züleyha", "gender" => "2", "birthday" => "28.05.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            85 => ["username" => "remezan2", "email" => "remezan2@mail.com", "phone" => "", "lastname" => "Arazgeldiýew", "firstname" => "Remezan", "gender" => "1", "birthday" => "05.12.2001", "role" => "ROLE_STUDENT", "class" => "4"],
            86 => ["username" => "arnazar", "email" => "arnazar@mail.com", "phone" => "", "lastname" => "Durdyýew", "firstname" => "Ärnazar", "gender" => "1", "birthday" => "10.08.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            87 => ["username" => "mansur", "email" => "mansur@mail.com", "phone" => "", "lastname" => "Durdygylyjow", "firstname" => "Mansur", "gender" => "1", "birthday" => "17.05.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            88 => ["username" => "rahymguly2", "email" => "rahymguly2@mail.com", "phone" => "", "lastname" => "Elýasow", "firstname" => "Rahym", "gender" => "1", "birthday" => "10.01.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            89 => ["username" => "ayjahan2", "email" => "ayjahan2@mail.com", "phone" => "", "lastname" => "Gurbanowa", "firstname" => "Ayjahan", "gender" => "2", "birthday" => "19.07.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            90 => ["username" => "baibiaysha", "email" => "baibiaysha@mail.com", "phone" => "", "lastname" => "Gurbanowa", "firstname" => "Bibiaýşa", "gender" => "2", "birthday" => "24.02.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            91 => ["username" => "gulalek", "email" => "gulalek@mail.com", "phone" => "", "lastname" => "Hudayberdiýewa", "firstname" => "Gülalek", "gender" => "2", "birthday" => "21.06.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            92 => ["username" => "hally", "email" => "hally@mail.com", "phone" => "", "lastname" => "Hojanyyazowa", "firstname" => "Hally", "gender" => "2", "birthday" => "15.04.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            93 => ["username" => "serdar2", "email" => "serdar2@mail.com", "phone" => "", "lastname" => "Hojamow", "firstname" => "Serdar", "gender" => "1", "birthday" => "17.09.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            94 => ["username" => "amanbike", "email" => "amanbike@mail.com", "phone" => "", "lastname" => "Öwezowa", "firstname" => "Amanbike", "gender" => "2", "birthday" => "05.02.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            95 => ["username" => "umyt", "email" => "umyt@mail.com", "phone" => "", "lastname" => "Pyhyýew", "firstname" => "Umyt", "gender" => "1", "birthday" => "10.07.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            96 => ["username" => "mukam", "email" => "mukam@mail.com", "phone" => "", "lastname" => "Sahedow", "firstname" => "Mukam", "gender" => "1", "birthday" => "26.08.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            97 => ["username" => "guljemal", "email" => "guljemal@mail.com", "phone" => "", "lastname" => "Saryyewa", "firstname" => "Güljemal", "gender" => "2", "birthday" => "11.11.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            98 => ["username" => "shamyrat", "email" => "shamyrat@mail.com", "phone" => "", "lastname" => "Şyhyýew", "firstname" => "Şamyrat", "gender" => "1", "birthday" => "11.11.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            99 => ["username" => "gurbanbibi", "email" => "gurbanbibi@mail.com", "phone" => "", "lastname" => "Ýarjanowa", "firstname" => "Gurbanbibi", "gender" => "2", "birthday" => "26.02.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            100 => ["username" => "ogulbolsun", "email" => "ogulbolsun@mail.com", "phone" => "", "lastname" => "Ýolamanowa", "firstname" => "Ogulbolsun", "gender" => "2", "birthday" => "14.06.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            101 => ["username" => "resul", "email" => "resul@mail.com", "phone" => "", "lastname" => "Jumaýew", "firstname" => "Resul", "gender" => "1", "birthday" => "23.07.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            102 => ["username" => "ybraym", "email" => "ybraym@mail.com", "phone" => "", "lastname" => "Hajymuhammedow", "firstname" => "Ybraýym", "gender" => "1", "birthday" => "25.03.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            103 => ["username" => "azym", "email" => "azym@mail.com", "phone" => "", "lastname" => "Nurullaýew", "firstname" => "Azym", "gender" => "1", "birthday" => "28.11.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            104 => ["username" => "meryem", "email" => "meryem@mail.com", "phone" => "", "lastname" => "Durdyýewa", "firstname" => "Merýem", "gender" => "2", "birthday" => "08.12.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            105 => ["username" => "sahragul", "email" => "sahragul@mail.com", "phone" => "", "lastname" => "Durdyýewa", "firstname" => "Sähragül", "gender" => "2", "birthday" => "29.01.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            106 => ["username" => "omar2", "email" => "omar2@mail.com", "phone" => "", "lastname" => "Halnepesow", "firstname" => "Omar", "gender" => "1", "birthday" => "22.10.2002", "role" => "ROLE_STUDENT", "class" => "4"],
            107 => ["username" => "mugallym", "email" => "mahriban@mail.com", "phone" => "", "lastname" => "Temirawa", "firstname" => "Mähriban", "gender" => "2", "birthday" => "", "role" => "ROLE_TEACHER", "class" => ""],
            108 => ["username" => "shyhy", "email" => "shyhy@mail.com", "phone" => "", "lastname" => "Şyhyýew", "firstname" => "Şyhy", "gender" => "1", "birthday" => "", "role" => "ROLE_TEACHER", "class" => ""],
            109 => ["username" => "maya", "email" => "maya@mail.com", "phone" => "", "lastname" => "Töre", "firstname" => "Maýa", "gender" => "2", "birthday" => "", "role" => "ROLE_TEACHER", "class" => ""],
            110 => ["username" => "amangul", "email" => "amangul@mail.com", "phone" => "", "lastname" => "Hudaýberdiýewa", "firstname" => "Amangül", "gender" => "2", "birthday" => "", "role" => "ROLE_TEACHER", "class" => ""],
            111 => ["username" => "walentina", "email" => "walentina@mail.com", "phone" => "", "lastname" => "Strunkowa", "firstname" => "Walentina", "gender" => "2", "birthday" => "", "role" => "ROLE_TEACHER", "class" => ""],
            112 => ["username" => "myrat", "email" => "myrat@mail.com", "phone" => "", "lastname" => "Weliýew", "firstname" => "Myrat", "gender" => "1", "birthday" => "", "role" => "ROLE_TEACHER", "class" => ""],
            113 => ["username" => "maya2", "email" => "maya2@mail.com", "phone" => "", "lastname" => "Esenowa", "firstname" => "Maýa", "gender" => "2", "birthday" => "", "role" => "ROLE_TEACHER", "class" => ""],
            114 => ["username" => "meret", "email" => "meret@mail.com", "phone" => "", "lastname" => "Gurbanmämmedow", "firstname" => "Meret", "gender" => "1", "birthday" => "", "role" => "ROLE_TEACHER", "class" => ""],
            115 => ["username" => "rowshen", "email" => "rowshen@mail.com", "phone" => "", "lastname" => "Orazweliýew", "firstname" => "Röwşen", "gender" => "1", "birthday" => "", "role" => "ROLE_TEACHER", "class" => ""],
            116 => ["username" => "aman", "email" => "aman@mail.com", "phone" => "", "lastname" => "Nurmämmedow", "firstname" => "Aman ", "gender" => "1", "birthday" => "", "role" => "ROLE_TEACHER", "class" => ""],
            117 => ["username" => "myratguly", "email" => "myratguly@mail.com", "phone" => "", "lastname" => "Setdalow", "firstname" => "Myratguly", "gender" => "1", "birthday" => "", "role" => "ROLE_TEACHER", "class" => ""],
            118 => ["username" => "dowran", "email" => "dowran@mail.com", "phone" => "", "lastname" => "Gaýypgulyýew", "firstname" => "Döwran", "gender" => "1", "birthday" => "", "role" => "ROLE_TEACHER", "class" => ""],
            119 => ["username" => "gurbantach2", "email" => "gurbantach2@mail.com", "phone" => "", "lastname" => "Bagyýewa", "firstname" => "Gurbantäç", "gender" => "2", "birthday" => "", "role" => "ROLE_TEACHER", "class" => ""],
            120 => ["username" => "seyitmyrat", "email" => "seyitmyrat@mail.com", "phone" => "", "lastname" => "Atabalow", "firstname" => "Seýitmyrat", "gender" => "1", "birthday" => "", "role" => "ROLE_TEACHER", "class" => ""],
            121 => ["username" => "saparnepes", "email" => "saparnepes@mail.com", "phone" => "", "lastname" => "Atabaýew", "firstname" => "Saparnepes", "gender" => "1", "birthday" => "", "role" => "ROLE_TEACHER", "class" => ""],
            122 => ["username" => "admin", "email" => "admin@mail.com", "phone" => "", "lastname" => "Sopyýew", "firstname" => "Sopy", "gender" => "1", "birthday" => "", "role" => "ROLE_ADMIN", "class" => ""],
            123 => ["username" => "eneata", "email" => "eneata@mail.com", "phone" => "", "lastname" => "Mergenow", "firstname" => "Mergen", "gender" => "1", "birthday" => "", "role" => "ROLE_PARENT", "class" => ""],
        ];
        foreach ($users as $key => $item) {
            $user = new User();
            $user->setEnabled(true);
            $user->setUsername($item['username']);
            $user->setPlainPassword($item['username']);
            $user->setRoles([$item['role']]);
            $user->setEmail($item['email']);
            $user->setGender($item['gender']);
            if ($item['birthday'] != "") $user->setBirthday((new \DateTime())->setTimestamp(strtotime($item['birthday'])));
            $user->setLastName($item['lastname']);
            $user->setFirstName($item['firstname']);
            $manager->persist($user);
            $users[$key]['object'] = $user;
        }
        $manager->flush();

        foreach ($users as $key => $item) {
            if ($item['object'] instanceof User) {
                if (isset($item['parent']) && $item['parent'] > 0) {
                    if (
                        $users[$item['parent']] &&
                        $users[$item['parent']]['object'] instanceof User
                    )
                        $this->setParent($item['object'], $users[$item['parent']]['object'], $manager);
                }
                if (isset($item['class']) && $item['class'] > 0) {
                    if (
                        $studentsGroups[$item['class']] &&
                        $studentsGroups[$item['class']]['object'] instanceof StudentsGroup

                )
                    $this->setStudentsGroup($item['object'], $manager, $studentsGroups[$item['class']]['object']);
                }
            }
        }


        // Create subjects
        $subjects = [
            1 => ["name" => "Algebra", "subjectArea" => 1, "classes" => [1,2,3,4], "teacher" => 107],
            2 => ["name" => "Türkmen-dili", "subjectArea" => 2, "classes" => [1,2,3,4], "teacher" => 108],
            3 => ["name" => "Dünýä-taryhy", "subjectArea" => 2, "classes" => [1,2,3,4], "teacher" => 109],
            4 => ["name" => "Iňlis-dili", "subjectArea" => 2, "classes" => [1,2,3,4], "teacher" => 110],
            5 => ["name" => "Iňlis-dili", "subjectArea" => 2, "classes" => [1,2,3,4], "teacher" => 121],
            6 => ["name" => "Rus-dili", "subjectArea" => 2, "classes" => [1,2,3,4], "teacher" => 119],
            7 => ["name" => "Rus-dili", "subjectArea" => 2, "classes" => [1,2,3,4], "teacher" => 111],
            8 => ["name" => "Ekologiýa", "subjectArea" => 3, "classes" => [1,2,3,4], "teacher" => 112],
            9 => ["name" => "Jemgyýet", "subjectArea" => 2, "classes" => [1,2,3,4], "teacher" => 109],
            10 => ["name" => "Türkmenistanyň-taryhy", "subjectArea" => 2, "classes" => [1,2,3,4], "teacher" => 109],
            11 => ["name" => "Dünýä medeniýeti", "subjectArea" => 2, "classes" => [1,2,3,4], "teacher" => 109],
            12 => ["name" => "Fizika", "subjectArea" => 1, "classes" => [1,2,3,4], "teacher" => 114],
            13 => ["name" => "Ykdysadyýet", "subjectArea" => 1, "classes" => [1,2,3,4], "teacher" => 114],
            14 => ["name" => "Edebiýat", "subjectArea" => 2, "classes" => [1,2,3,4], "teacher" => 108],
            15 => ["name" => "Informatika", "subjectArea" => 1, "classes" => [1,2,3,4], "teacher" => 115],
            16 => ["name" => "Bedenterbiýe", "subjectArea" => 2, "classes" => [1,2,3,4], "teacher" => 116],
            17 => ["name" => "Himiýa", "subjectArea" => 3, "classes" => [1,2,3,4], "teacher" => 117],
            18 => ["name" => "Özüňi al. bar. med.", "subjectArea" => 2, "classes" => [1,2,3,4], "teacher" => 118],
            19 => ["name" => "Biologiýa", "subjectArea" => 3, "classes" => [1,2,3,4], "teacher" => 120],
            20 => ["name" => "Geometriýa", "subjectArea" => 1, "classes" => [1,2,3,4], "teacher" => 107],
            21 => ["name" => "Modelirleme", "subjectArea" => 1, "classes" => [1], "teacher" => 115],
        ];
        foreach ($subjects as $key => $item) {
            $subject = new Subject();
            $subject->setName($item['name']);
            $subject->setSubjectArea($subjectAres[$item['subjectArea']]['object']);
            foreach ($item['classes'] as $item2) {
                if (isset($studentsGroups[$item2]) && $studentsGroups[$item2]['object'] instanceof StudentsGroup)
                    $subject->getStudentsGroups()->add($studentsGroups[$item2]['object']);
            }
            if (isset($users[$item['teacher']]) && $users[$item['teacher']]['object'] instanceof User)
                $subject->setUser($users[$item['teacher']]['object']);
            $manager->persist($subject);
            $subjects[$key]['object'] = $subject;
        }
        $manager->flush();

        // Create subject schedule
        $subjectSchedules = [
            [
                "class" => $studentsGroups[1]['object'],
                "schedule" => [
                    1 => [
                        1 => 16,
                        2 => 12,
                        3 => 18,
                        4 => 2,
                        5 => 17,
                        6 => 9,
                    ], 2 => [
                        1 => 20,
                        2 => 1,
                        3 => 7,
                        4 => 10,
                        5 => 12,

                    ], 3 => [
                        1 => 17,
                        2 => 1,
                        3 => 8,
                        4 => 19,
                        5 => 13,
                        6 => 5,
                    ], 4 => [
                        1 => 11,
                        2 => 14,
                        3 => 4,
                        4 => 1,
                        5 => 12,
                        6 => 15,
                    ], 5 => [
                        1 => 20,
                        2 => 12,
                        3 => 14,
                        4 => 4,
                        5 => 15,

                    ], 6 => [
                        1 => 6,
                        2 => 21,
                        3 => 1,
                        4 => 3,
                        5 => 22,

                    ]
                ]
            ],
            [
                "class" => $studentsGroups[2]['object'],
                "schedule" => [
                    1 => [
                        1 => 20,
                        2 => 19,
                        3 => 12,
                        4 => 17,
                        5 => 15,

                    ], 2 => [
                        1 => 17,
                        2 => 5,
                        3 => 16,
                        4 => 14,
                        5 => 15,
                        6 => 1,
                    ], 3 => [
                        1 => 19,
                        2 => 4,
                        3 => 18,
                        4 => 17,
                        5 => 2,

                    ], 4 => [
                        1 => 9,
                        2 => 17,
                        3 => 3,
                        4 => 7,
                        5 => 1,
                        6 => 4,
                    ], 5 => [
                        1 => 10,
                        2 => 11,
                        3 => 8,
                        4 => 17,
                        5 => 22,
                        6 => 6,
                    ], 6 => [
                        1 => 12,
                        2 => 14,
                        3 => 19,
                        4 => 1,
                        5 => 13,

                    ]
                ]
            ],
            [
                "class" => $studentsGroups[3]['object'],
                "schedule" => [
                    1 => [
                        1 => 3,
                        2 => 4,
                        3 => 8,
                        4 => 10,
                        5 => 6,

                    ], 2 => [
                        1 => 18,
                        2 => 17,
                        3 => 1,
                        4 => 6,
                        5 => 4,
                        6 => 12,
                    ], 3 => [
                        1 => 13,
                        2 => 17,
                        3 => 16,
                        4 => 20,
                        5 => 7,

                    ], 4 => [
                        1 => 14,
                        2 => 11,
                        3 => 15,
                        4 => 10,
                        5 => 5,

                    ], 5 => [
                        1 => 22,
                        2 => 2,
                        3 => 19,
                        4 => 14,
                        5 => 1,
                        6 => 4,
                    ], 6 => [
                        1 => 9,
                        2 => 12,
                        3 => 5,
                        4 => 2,
                        5 => 15,
                        6 => 1,
                    ]
                ]
            ],
            [
                "class" => $studentsGroups[4]['object'],
                "schedule" => [
                    1 => [
                        1 => 17,
                        2 => 16,
                        3 => 10,
                        4 => 12,
                        5 => 4,

                    ], 2 => [
                        1 => 6,
                        2 => 19,
                        3 => 4,
                        4 => 1,
                        5 => 15,

                    ], 3 => [
                        1 => 9,
                        2 => 10,
                        3 => 13,
                        4 => 18,
                        5 => 4,
                        6 => 14,
                    ], 4 => [
                        1 => 1,
                        2 => 3,
                        3 => 6,
                        4 => 5,
                        5 => 2,

                    ], 5 => [
                        1 => 11,
                        2 => 1,
                        3 => 12,
                        4 => 7,
                        5 => 14,
                        6 => 2,
                    ], 6 => [
                        1 => 20,
                        2 => 17,
                        3 => 15,
                        4 => 8,
                        5 => 4,
                        6 => 22,
                    ]
                ]
            ],
        ];
        $subjectScheduleGroup = new SubjectSchedulesGroup();
        $subjectScheduleGroup->setName('Nusga reje');
        $manager->persist($subjectScheduleGroup);
        $this->setActivaSubjectSchedulesGroup($subjectScheduleGroup, $manager);
        foreach ($subjectSchedules as $key => $item) {
            $subjectSchedule = new SubjectSchedule();
            $subjectSchedule->setStudentsGroup($item['class']);
            $subjectSchedule->setSubjectSchedulesGroup($subjectScheduleGroup);
            for ($i = 1; $i <= 6; $i++)
                for ($j = 1; $j <= 7; $j++)
                    if (empty($item['schedule'][$i][$j])) $item['schedule'][$i][$j] = null;
                    elseif (isset($subjects[$item['schedule'][$i][$j]]) && $subjects[$item['schedule'][$i][$j]]['object'] instanceof Subject)
                        $item['schedule'][$i][$j] = $subjects[$item['schedule'][$i][$j]]['object']->getId();
                    else
                        $item['schedule'][$i][$j] = null;

            $subjectSchedule->setSchedule($item['schedule']);
            $manager->persist($subjectSchedule);
        }
        $manager->flush();

        // Createing calendar and holidays
        $calendar = new Calendar();
        $calendar->setYear(2019);
        $calendar->setQuarterOneBegin((new \DateTime())->setDate(2019, 9, 1));
        $calendar->setQuarterOneEnd((new \DateTime())->setDate(2019, 10, 20));
        $calendar->setQuarterTwoBegin((new \DateTime())->setDate(2019, 11, 1));
        $calendar->setQuarterTwoEnd((new \DateTime())->setDate(2019, 12, 30));
        $calendar->setQuarterThreeBegin((new \DateTime())->setDate(2020, 1, 11));
        $calendar->setQuarterThreeEnd((new \DateTime())->setDate(2020, 3, 20));
        $calendar->setQuarterFourBegin((new \DateTime())->setDate(2020, 4, 1));
        $calendar->setQuarterFourEnd((new \DateTime())->setDate(2020, 5, 25));
        $manager->persist($calendar);
        $holidays = [
            [
                "name" => "Täze ýyl",
                "beginDate" => "2019-12-31",
                "endDate" => "2019-01-01",
            ],
            [
                "name" => "Halkara zenanlar güni",
                "beginDate" => "2019-03-08",
                "endDate" => "2019-03-08",
            ],
            [
                "name" => "Milli bahar baýramy",
                "beginDate" => "2019-03-21",
                "endDate" => "2019-03-22",
            ],
            [
                "name" => "Türkmenistanyň Konstitusiýasynyň we Türkmenistanyň Döwlet baýdagynyň güni",
                "beginDate" => "2019-05-18",
                "endDate" => "2019-05-18",
            ],
            [
                "name" => "Türkmenistanyň Garaşsyzlyk güni",
                "beginDate" => "2019-09-27",
                "endDate" => "2019-09-27",
            ],
            [
                "name" => "Hatyra güni",
                "beginDate" => "2019-10-06",
                "endDate" => "2019-10-06",
            ],
            [
                "name" => "Halkara Bitaraplyk güni",
                "beginDate" => "2019-12-12",
                "endDate" => "2019-12-12",
            ],
        ];
        $holidayObjects = new ArrayCollection();
        foreach ($holidays as $item) {
            $holiday = new Holiday();
            $holiday->setName($item['name']);
            $holiday->setBegin((new \DateTime())->setTimestamp(strtotime($item['beginDate'])));
            $holiday->setEnd((new \DateTime())->setTimestamp(strtotime($item['endDate'])));
            $manager->persist($holiday);
            $holidayObjects->add($holiday);
        }
        $calendar->setHolidays($holidayObjects);
        $manager->flush();

        // Setting about school
        $school = [
            'shortName' => '2-nji orta mekdebi',
            'fullName' => 'Daşary ýurt dillerinden ýöriteleşdirilen 2-nji orta mekdebi',
            'address' => 'Balkanabat, 126-njy ýaşaýyş jaýlar toplumy',
            'phone' => '+99361234567',
            'email' => 'mekdep.n2@gmail.com',
            'director' => $users[122]['object']->getId(),
        ];
        foreach ($school as $key => $item) {
            $setting = $manager
                ->getRepository('EduBoxBundle:Setting')
                ->findOneByOrCreate(['settingKey' => $key]);
            $setting->setSettingValue($item);
            $manager->persist($setting);
        }
        $manager->flush();
    }



    public function setParent(User $student, User $parent, ObjectManager $manager)
    {
        if ($student->hasRole('ROLE_STUDENT') && $parent->hasRole('ROLE_PARENT')) {
            $userMetaRepo = $manager->getRepository('EduBoxBundle:UserMeta');
            $userMetaRepo->findOneByOrCreate(['user' => $student, 'metaKey' => UserMeta::STUDENT_PARENT_ID])->setMetaValue($parent->getId());
            return true;
        }
        return false;
    }

    public function setStudentsGroup(User $student, ObjectManager $manager, StudentsGroup $studentsGroup = null)
    {
        if ($student->hasRole('ROLE_STUDENT')) {
            $userMetaRepo = $manager->getRepository('EduBoxBundle:UserMeta');
            if ($studentsGroup instanceof StudentsGroup) {
                return $userMetaRepo->findOneByOrCreate(['user' => $student, 'metaKey' => UserMeta::STUDENT_GROUP_ID])->setMetaValue($studentsGroup->getId());
            } else if ($studentsGroup === null)
            {
                return $userMetaRepo->findOneByOrCreate(['user' => $student, 'metaKey' => UserMeta::STUDENT_GROUP_ID])->setMetaValue(null);
            }
        }
    }

    public function setActivaSubjectSchedulesGroup(SubjectSchedulesGroup $schedulesGroup, ObjectManager $manager)
    {
        $setting = $manager
            ->getRepository('EduBoxBundle:Setting')
            ->findOneByOrCreate(['settingKey' => 'subjectSchedulesGroup']);
        $setting->setSettingValue($schedulesGroup->getId());
        $manager->flush();
    }

}