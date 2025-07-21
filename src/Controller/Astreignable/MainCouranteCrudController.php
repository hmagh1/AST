<?php

namespace App\Controller\Astreignable;

use App\Entity\MainCourante;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MainCouranteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MainCourante::class;
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
