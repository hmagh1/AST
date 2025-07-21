<?php

namespace App\Controller\Astreignable;

use App\Entity\ServiceFait;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ServiceFaitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ServiceFait::class;
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
