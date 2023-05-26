<?php

namespace App\Controller\Admin;

use App\Entity\Contrat;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ContratCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contrat::class;
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
