<?php

namespace App\Controller\Admin;

use App\Entity\Albums;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AlbumsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Albums::class;
    }


    public function configureFields(string $pageName): iterable
    {
        if ($pageName === 'index') {

            $statusChoices = [
                '1' => 'Vendu',
                'false' => 'Disponible',
            ];

            return [
                TextField::new('title')->setLabel('Titre'),
                SlugField::new('slug')->setTargetFieldName('title')->setLabel('Url titre'),
                TextEditorField::new('description')->setLabel('Description')->formatValue(function ($value) {
                    return $value;
                }),
                MoneyField::new('price')->setCurrency('EUR')->setLabel('Prix'),
                ImageField::new('image')
                    ->setBasePath('uploads/')
                    ->setUploadDir('public/uploads')
                    ->setUploadedFileNamePattern('[randomhash].[extension]')
                    ->setRequired(false)->setLabel('Illustration'),
                AssociationField::new('contrat')->setLabel('Template Contrat album'),
                TextField::new('status')->formatValue(function ($value) {
                    if ($value == "1") {
                        return '<span style="color: #5eb5e0">Vendu</span>';
                    } elseif ($value == "false") {
                        return '<span style="color: #ff564c">Disponible</span>';
                    } else {
                        return $value;
                    }
                })->setLabel('Statut'),
            ];
        } else {


            return [
                TextField::new('title')->setLabel('Titre'),
                SlugField::new('slug')->setTargetFieldName('title')->setLabel('Url titre'),
                TextEditorField::new('description')->setLabel('Description'),
                MoneyField::new('price')->setCurrency('EUR')->setLabel('Prix'),
                ImageField::new('image')
                    ->setBasePath('uploads/')
                    ->setUploadDir('public/uploads')
                    ->setUploadedFileNamePattern('[randomhash].[extension]')
                    ->setRequired(true)->setLabel('Illustration'),
                AssociationField::new('contrat')->setLabel('Template Contrat album'),

            ];
        }
    }

    public function edit(AdminContext $context)
    {
        $entity = $context->getEntity()->getInstance();
        if ($entity instanceof Albums && $entity->getStatus() == 1) {
            $this->addFlash('warning', "L'album est déjà vendu et ne peut être modifié.");
            // Accéder à la requête HTTP depuis le contexte
            $request = $context->getRequest();
            // Extraire l'URL de référence (la page précédente)
            $referer = $request->headers->get('referer');
            return new RedirectResponse($referer);
        }

        return parent::edit($context);
    }
    public function delete(AdminContext $context)
    {
        $entity = $context->getEntity()->getInstance();
        if ($entity instanceof Albums && $entity->getStatus() == 1) {
            $this->addFlash('warning', "L'album est déjà vendu et ne peut être supprimé.");            // Accéder à la requête HTTP depuis le contexte
            $request = $context->getRequest();
            // Extraire l'URL de référence (la page précédente)
            $referer = $request->headers->get('referer');
            return new RedirectResponse($referer);
        }

        return parent::delete($context);
    }

}
