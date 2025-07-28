<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Astreignable;
use App\Entity\PlanningAstreinte;
use App\Entity\ServiceFait;
use App\Entity\MainCourante;
use App\Entity\DRH;
use App\Entity\AdministrateurUCAC;
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
        // DRH d’exemple
        $drh = (new DRH())->setNom('DRH Hôpital Général');
        $manager->persist($drh);

        // Administrateur + User
        $adminProfile = (new AdministrateurUCAC())
            ->setNom('Dupont Pierre')
            ->setEmail('admin@hopital.com');

        $adminUser = (new User())
            ->setEmail('admin@hopital.com')
            ->setRoles(['ROLE_ADMIN']);
        $adminUser->setPassword($this->hasher->hashPassword($adminUser, 'password'));

        $adminProfile->setUser($adminUser);
        $adminUser->setAdminProfile($adminProfile);

        $manager->persist($adminUser);
        $manager->persist($adminProfile);

        // Astreignables médicaux
        $medecins = [
            ['Jean',    'Martin',    'jean.martin@hopital.com',   '0612345678', 'Urgences',   'Médecin Urgentiste'],
            ['Marie',   'Dupuis',    'marie.dupuis@hopital.com',  '0623456789', 'Pédiatrie',  'Pédiatre'],
            ['Sophie',  'Durand',    'sophie.durand@hopital.com', '0634567890', 'Chirurgie',  'Chirurgienne'],
            ['Luc',     'Moreau',    'luc.moreau@hopital.com',    '0645678901', 'Anesthésie', 'Anesthésiste'],
            ['Claire',  'Girard',    'claire.girard@hopital.com', '0656789012', 'Cardiologie','Cardiologue'],
        ];

        foreach ($medecins as $i => [$prenom, $nom, $email, $tel, $direction, $specialite]) {
            $astreignable = (new Astreignable())
                ->setNom($nom)
                ->setPrenom($prenom)
                ->setEmail($email)
                ->setTelephone($tel)
                ->setSeniorite($specialite)
                ->setDirection($direction)
                ->setDisponible($i % 2 === 0);

            // User associé
            $user = (new User())
                ->setEmail($email)
                ->setRoles(['ROLE_ASTREIGNABLE']);
            $user->setPassword($this->hasher->hashPassword($user, 'password'));

            $astreignable->setUser($user);
            $user->setAstreignableProfile($astreignable);

            $manager->persist($user);
            $manager->persist($astreignable);

            // Service fait
            $service = (new ServiceFait())
                ->setDate(new DateTime())
                ->setHeuresEffectuees(8)
                ->setValide(true)
                ->setAstreignable($astreignable);
            $manager->persist($service);

            // Main courante
            $main = (new MainCourante())
                ->setDate(new DateTime())
                ->setDetails("Main courante pour Dr $prenom $nom")
                ->setAstreignable($astreignable);
            $manager->persist($main);

            // Planning avec nom affiché
            $planning = (new PlanningAstreinte())
                ->setDateDebut(new DateTime('-1 day'))
                ->setDateFin(new DateTime('+1 day'))
                ->setTheme($direction)
                ->setStatut('Actif')
                ->addBinome($astreignable)
                ->setAstreintNom("$prenom $nom"); // Nécessite le champ astreintNom côté entity/migration
            $manager->persist($planning);
        }

        $manager->flush();
    }
}
