<?php
namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class MyCustomAdminController extends EasyAdminController
{
    public function editAction()
    {
        $response = parent::createAction();

        // Récupérer le formulaire
        $form = $this->createForm(/* ... */);

        // Gérer l'événement de soumission du formulaire
        $form->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Déclencher l'événement de formulaire approprié
            $event = new FormEvent($form, $form->getData());
            $this->get('event_dispatcher')->dispatch($event, FormEvents::POST_SUBMIT);

            // Ajouter votre logique personnalisée ici
        }

        return $response;
    }
}
