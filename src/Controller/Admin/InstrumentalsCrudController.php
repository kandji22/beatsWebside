<?php

namespace App\Controller\Admin;

use App\Entity\Albums;
use App\Entity\Instrumentals;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\DomCrawler\Field\FileFormField;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
                TextField::new('titre')->setLabel('Titre'),
                TextEditorField::new('description')->setLabel('Description')->formatValue(function ($value) {
                    return $value;
                }),
                AssociationField::new('album_id')->formatValue(function ($value) {
                    $resultat = explode(',', $value)[0];
                    return $resultat;
                })->setLabel('Album de liaison'),
                TextField::new('album_id')->formatValue(function ($value) {
                    $resultat = explode(',', $value)[1];

                    if ($resultat == "Vendu") {
                        return '<span style="color: #5eb5e0">'.$resultat.'</span>';
                    } elseif ($resultat == "Disponible") {
                        return '<span style="color: #ff564c">'.$resultat.'</span>';
                    } else {
                        return $resultat;
                    }
                })->setLabel('Statut')
            ];
        } else {
            // Configuration des autres champs pour les autres actions (show, edit, etc.)
        return [
             TextField::new('titre')->setLabel('Titre'),
             TextEditorField::new('description')->setLabel('Description'),
             ImageField::new('fichier_audio')
                ->setUploadDir('public/audio') // Chemin d'upload des images
                ->setBasePath('audio/') // Chemin d'accès aux images
                ->setUploadedFileNamePattern('[randomhash].[extension]')->setLabel('Fichier audio de présentation'),
            ImageField::new('originalfile')
                ->setUploadDir('public/original') // Chemin d'upload des images
                ->setBasePath('original/') // Chemin d'accès aux images
                ->setUploadedFileNamePattern('audio_' . time() . '.[extension]')->setLabel('Fichier audio original à vendre'),
            AssociationField::new('album_id')
                ->setLabel('Choisir un album')

                ->setFormTypeOption('query_builder', function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->andWhere('a.status = false')
                        ->orderBy('a.id', 'ASC');
                }),

            ]; // Pattern de nommage des fichiers

        }
    }

    public function edit(AdminContext $context)
    {
        $entity = $context->getEntity()->getInstance();

        if ($entity instanceof Instrumentals) {
            $album = $entity->getAlbumId();
            if($album->getStatus() == 1) {
                $this->addFlash('warning', "L'album lié à cet instrumental est déjà vendu, il ne peut donc pas être modifié.");
                // Accéder à la requête HTTP depuis le contexte
                $request = $context->getRequest();
                // Extraire l'URL de référence (la page précédente)
                $referer = $request->headers->get('referer');
                return new RedirectResponse($referer);
            }
        }

        return parent::edit($context);
    }

    public function delete(AdminContext $context)
    {
        $entity = $context->getEntity()->getInstance();

        if ($entity instanceof Instrumentals) {
            $album = $entity->getAlbumId();
            if($album->getStatus() == 1) {
                $this->addFlash('warning', "L'album lié à cet instrumental est déjà vendu, il ne peut donc pas être supprimé.");
                // Accéder à la requête HTTP depuis le contexte
                $request = $context->getRequest();
                // Extraire l'URL de référence (la page précédente)
                $referer = $request->headers->get('referer');
                return new RedirectResponse($referer);
            }
        }

        return parent::delete($context);
    }

}
