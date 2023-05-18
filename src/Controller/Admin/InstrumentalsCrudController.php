<?php

namespace App\Controller\Admin;

use App\Entity\Instrumentals;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\DomCrawler\Field\FileFormField;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;

class InstrumentalsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Instrumentals::class;
    }


    public function configureFields(string $pageName): iterable
    {
        if ($pageName === 'index') {
            return [
                TextField::new('titre'),
                TextEditorField::new('description'),
                AssociationField::new('album_id')
            ];
        } else {
            // Configuration des autres champs pour les autres actions (show, edit, etc.)

            yield TextField::new('titre');
            yield TextEditorField::new('description');
            yield ImageField::new('fichier_audio')
                ->setUploadDir('public/audio') // Chemin d'upload des images
                ->setBasePath('/audio') // Chemin d'accès aux images
                ->setUploadedFileNamePattern('[randomhash].[extension]') // Pattern de nommage des fichiers
                ->setFormTypeOptions([
                    'constraints' => [
                        new File([
                            'maxSize' => '10M', // Augmentez cette valeur selon vos besoins
                            'maxSizeMessage' => 'La taille maximale autorisée est de 10 Mo.', // Message d'erreur personnalisé
                        ]),
                    ],
                ]);
            yield AssociationField::new('album_id');
        }
    }


}
