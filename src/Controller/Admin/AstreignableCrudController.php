<?php
// src/Controller/Admin/AstreignableCrudController.php
namespace App\Controller\Admin;

use App\Entity\Astreignable;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AstreignableCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public static function getEntityFqcn(): string
    {
        return Astreignable::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('nom', 'Nom');
        yield TextField::new('prenom', 'Prénom');
        yield EmailField::new('email', 'Email');
        yield TextField::new('telephone', 'Téléphone');
        yield TextField::new('seniorite', 'Séniorité');
        yield TextField::new('direction', 'Direction');
        yield BooleanField::new('disponible', 'Disponible');

        // À la place de PasswordField, on utilise TextField + formType PasswordType
        yield TextField::new('plainPassword', 'Mot de passe')
            ->onlyOnForms()
            ->setFormType(PasswordType::class)
            ->setHelp('Saisissez un mot de passe pour l’astreignable.');
    }

    public function persistEntity(EntityManagerInterface $em, $astreignable): void
    {
        if (!$astreignable instanceof Astreignable) {
            parent::persistEntity($em, $astreignable);
            return;
        }

        // Créer et hasher le User
        $user = new User();
        $user->setEmail($astreignable->getEmail());
        $plain = $astreignable->getPlainPassword() ?? bin2hex(random_bytes(4));
        $hashed = $this->hasher->hashPassword($user, $plain);
        $user->setPassword($hashed);
        $user->setRoles(['ROLE_USER']);

        // Lier User ↔ Astreignable
        $astreignable->setUser($user);
        $user->setAstreignableProfile($astreignable);

        $em->persist($user);
        $em->persist($astreignable);
        $em->flush();
    }

    public function updateEntity(EntityManagerInterface $em, $astreignable): void
    {
        if ($astreignable instanceof Astreignable && $astreignable->getPlainPassword()) {
            $user = $astreignable->getUser();
            $hashed = $this->hasher->hashPassword($user, $astreignable->getPlainPassword());
            $user->setPassword($hashed);
            $em->persist($user);
        }

        parent::updateEntity($em, $astreignable);
    }
}
