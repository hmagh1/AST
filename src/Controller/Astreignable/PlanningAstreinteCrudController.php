<?php

namespace App\Controller\Astreignable;

use App\Entity\PlanningAstreinte;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PlanningAstreinteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PlanningAstreinte::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
