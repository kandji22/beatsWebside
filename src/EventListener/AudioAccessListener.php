<?php
// src/EventListener/AudioAccessListener.php

namespace App\EventListener;

use App\Event\AudioAccessEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AudioAccessListener implements EventSubscriberInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $pathInfo = $request->getPathInfo();

        // Vérifie si le chemin commence par "/audio"
        if (strpos($pathInfo, '/audio') === 0) {
            // Bloque l'accès au dossier "audio" en redirigeant vers la page d'accueil
            $event->setResponse(new RedirectResponse($this->urlGenerator->generate('app_login')));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }
}
