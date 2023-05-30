<?php

namespace App\Controller\Admin;

use App\Entity\Albums;
use App\Entity\OrderDetail;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderDetailCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderDetail::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('fullnameuser')->setLabel('Nom complet client'),
            TextField::new('email')->setLabel('Email de l\'utilisateur effectuant la transaction'),
            MoneyField::new('price')->setCurrency('EUR')->setLabel('Prix de vente'),
            IntegerField::new('album')
            ->setLabel('Album')
            ->formatValue(function ($value,$entity){
                $album = $this->getDoctrine()->getRepository(Albums::class)->find($value);
                if($album) {
                    return $album->getTitle();
                }
                return '';
            }),
            BooleanField::new('status')->setLabel('Vendu'),

        ];
    }

    public function  configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable('new','edit');
    }
}
