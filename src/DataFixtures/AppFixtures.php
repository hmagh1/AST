<?php
// src/DataFixtures/AppFixtures.php

namespace App\DataFixtures;

use App\Entity\{User, Astreignable, PlanningAstreinte, ServiceFait, MainCourante, DRH, AdministrateurUCAC};
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTime;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        /* ---------- DRH d’exemple ---------- */
        $drh = (new DRH())->setNom('DRH Central');
        $manager->persist($drh);

        /* ---------- Administrateur + User ---------- */
        $adminProfile = (new AdministrateurUCAC())
            ->setNom('Admin UCAC')
            ->setEmail('admin@ucac.com');

        $adminUser = (new User())
            ->setEmail('admin@ucac.com')
            ->setRoles(['ROLE_ADMIN']);
        $adminUser->setPassword($this->hasher->hashPassword($adminUser, 'password'));

        // liaison 1‑1
        $adminProfile->setUser($adminUser);
        $adminUser->setAdminProfile($adminProfile);

        $manager->persist($adminUser);
        $manager->persist($adminProfile);

        /* ---------- Astreignables + Users ---------- */
        for ($i = 1; $i <= 5; $i++) {
            $astreignable = (new Astreignable())
                ->setNom("Nom{$i}")
                ->setPrenom("Prenom{$i}")
                ->setEmail("user{$i}@example.com")
                ->setTelephone("060000000{$i}")
                ->setSeniorite($i % 2 ? 'Junior' : 'Senior')
                ->setDirection('Informatique')
                ->setDisponible($i % 2 === 0);

            // compte User associé
            $user = (new User())
                ->setEmail("user{$i}@example.com")
                ->setRoles(['ROLE_ASTREIGNABLE']);
            $user->setPassword($this->hasher->hashPassword($user, 'password'));

            $astreignable->setUser($user);
            $user->setAstreignableProfile($astreignable);

            $manager->persist($user);
            $manager->persist($astreignable);

            /* -------- Service fait -------- */
            $service = (new ServiceFait())
                ->setDate(new DateTime())
                ->setHeuresEffectuees(4)
                ->setValide(true)
                ->setAstreignable($astreignable);
            $manager->persist($service);

            /* -------- Main courante -------- */
            $main = (new MainCourante())
                ->setDate(new DateTime())
                ->setDetails("Main courante pour utilisateur {$i}")
                ->setAstreignable($astreignable);
            $manager->persist($main);

            /* -------- Planning -------- */
            $planning = (new PlanningAstreinte())
                ->setDateDebut(new DateTime('-1 day'))
                ->setDateFin(new DateTime('+1 day'))
                ->setTheme('Maintenance')
                ->setStatut('Actif')
                ->addBinome($astreignable);   // méthode côté Planning
            $manager->persist($planning);
        }

        $manager->flush();
    }
}
