<?php
// src/Controller/Admin/PlanningAstreinteCrudController.php
namespace App\Controller\Admin;

use App\Entity\PlanningAstreinte;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    AssociationField,
    DateField,
    IdField,
    TextField
};

class PlanningAstreinteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PlanningAstreinte::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Planning')
            ->setEntityLabelInPlural('Plannings')
            ->setDefaultSort(['dateDebut' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        // la colonne ID : seulement en liste
        yield IdField::new('id')->onlyOnIndex();

        yield DateField::new('dateDebut', 'Date début');
        yield DateField::new('dateFin',   'Date fin');
        yield TextField::new('theme',      'Thème');
        yield TextField::new('statut',     'Statut');

        // --- affichage en liste : on lit la propriété virtuelle getBinomeNames() ---
        if (Crud::PAGE_INDEX === $pageName) {
            yield TextField::new('binomeNames', 'Astreignables');
        }

        // --- champ de formulaire pour lier 1+ astreignables au planning ---
        yield AssociationField::new('binome', 'Astreignables')
            ->onlyOnForms()
            ->setFormTypeOption('by_reference', false)
            ->setHelp('Choisissez un ou plusieurs astreignables à affecter');
    }
}
