<?php
namespace App\DataFixtures;

use App\Entity\Astreignable;
use App\Entity\ServiceFait;
use App\Entity\MainCourante;
use App\Entity\PlanningAstreinte;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Astreignable
        $astreignable = new Astreignable();
        $astreignable->setNom('Test');
        $astreignable->setPrenom('User');
        $astreignable->setEmail('test.user@example.com');
        $astreignable->setTelephone('0600000000');
        $astreignable->setSeniorite('Junior');
        $astreignable->setDirection('Informatique');
        $astreignable->setDisponible(true);
        $manager->persist($astreignable);

        // ServiceFait
        $service = new ServiceFait();
        $service->setDate(new \DateTime('2024-07-01'));
        $service->setHeuresEffectuees(5);
        $service->setValide(true);
        $service->setAstreignable($astreignable);
        $manager->persist($service);

        // MainCourante
        $mainCourante = new MainCourante();
        $mainCourante->setDate(new \DateTime('2024-07-01'));
        $mainCourante->setDetails('Incident réseau majeur résolu.');
        $mainCourante->setAstreignable($astreignable);
        $manager->persist($mainCourante);

        // PlanningAstreinte
        $planning = new PlanningAstreinte();
        $planning->setDateDebut(new \DateTime('2024-07-10'));
        $planning->setDateFin(new \DateTime('2024-07-20'));
        $planning->setTheme('Supervision Réseau');
        $planning->setStatut('Prévu');
        $planning->addBinome($astreignable);
        $manager->persist($planning);

        $manager->flush();
    }
}
