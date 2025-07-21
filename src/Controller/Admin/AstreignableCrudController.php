<?php

namespace App\Controller\Admin;

use App\Entity\Astreignable;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AstreignableCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Astreignable::class;
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
