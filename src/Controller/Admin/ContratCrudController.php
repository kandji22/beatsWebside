<?php

namespace App\Controller\Admin;

use App\Entity\Contrat;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

class ContratCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contrat::class;
    }


    public function configureFields(string $pageName): iterable
    {

        return [
            TextField::new('name'),
            ImageField::new('fileContrat')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
        ];
    }

}
