<?php
namespace App\DataFixtures;

use App\Entity\Astreignable;
use App\Entity\PlanningAstreinte;
use App\Entity\ServiceFait;
use App\Entity\MainCourante;
use App\Entity\DRH;
use App\Entity\AdministrateurUCAC;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $drh = new DRH();
        $drh->setNom("DRH Central");
        $manager->persist($drh);

        $admin = new AdministrateurUCAC();
        $admin->setNom("Admin UCAC");
        $admin->setEmail("admin@ucac.com");
        $manager->persist($admin);

        for ($i = 1; $i <= 5; $i++) {
            $astreignable = new Astreignable();
            $astreignable->setNom("Nom{$i}");
            $astreignable->setPrenom("Prenom{$i}");
            $astreignable->setEmail("user{$i}@example.com");
            $astreignable->setTelephone("060000000{$i}");
            $astreignable->setSeniorite("Senior");
            $astreignable->setDirection("Informatique");
            $astreignable->setDisponible($i % 2 === 0);
            $manager->persist($astreignable);

            $service = new ServiceFait();
            $service->setDate(new \DateTime());
            $service->setHeuresEffectuees(4);
            $service->setValide(true);
            $service->setAstreignable($astreignable);
            $manager->persist($service);

            $main = new MainCourante();
            $main->setDate(new \DateTime());
            $main->setDetails("Main courante pour utilisateur {$i}");
            $main->setAstreignable($astreignable);
            $manager->persist($main);

            $planning = new PlanningAstreinte();
            $planning->setDateDebut(new \DateTime("-1 day"));
            $planning->setDateFin(new \DateTime("+1 day"));
            $planning->setTheme("Maintenance");
            $planning->setStatut("Actif");
            $planning->getBinome()->add($astreignable);
            $manager->persist($planning);
        }

        $manager->flush();
    }
}
