<?php

namespace App\Controller\Admin;

use App\Entity\AdministrateurUCAC;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AdministrateurUCACCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AdministrateurUCAC::class;
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
