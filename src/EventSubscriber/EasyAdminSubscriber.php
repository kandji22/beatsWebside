<?php

namespace App\EventSubscriber;

use App\Entity\Albums;
use App\Entity\Instrumentals;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use App\Repository\AlbumsRepository;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Security;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;
use function PHPUnit\Framework\fileExists;
use Symfony\Contracts\Cache\CacheInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $flashBag;
    private $albumRepo;
    private $kernel;
    private $oldFile;
    private $security;
    private $cache;

    public function __construct(FlashBagInterface $flashBag, AlbumsRepository $albumRepo,KernelInterface $kernel,Security $security,CacheInterface $cache)
    {
        $this->flashBag = $flashBag;
        $this->albumRepo = $albumRepo;
        $this->kernel = $kernel;
        $this->security = $security;
        $this->cache = $cache;

    }

    public static function getSubscribedEvents()
    {
        return [
            /*BeforeEntityPersistedEvent::class => [['checkImageOrAudio', 2],['checkContrat',3]],
            BeforeEntityUpdatedEvent::class => ['updateImageOrAudio',10],
            BeforeCrudActionEvent::class => ['onFormSubmit',20],*/
            BeforeEntityPersistedEvent::class => [['checkContrat',3]],
            BeforeEntityDeletedEvent::class => [['onEntityDeleted', 3]],
            BeforeEntityPersistedEvent::class => [['checkCache',4]],
            BeforeEntityDeletedEvent::class => [['checkCachedelete', 4]],
            BeforeEntityUpdatedEvent::class => [['checkCacheUpdate', 4]],

        ];
    }

    public function onFormSubmit(BeforeCrudActionEvent $event)
    {
        $request = $event->getAdminContext()->getRequest();
        $formData = $request->request->all();
        if(array_key_exists('oldNameFile',$formData)) {
            $this->oldFile = $formData["oldNameFile"];
        }
    }
    public function checkImageOrAudio(BeforeEntityPersistedEvent $event) {
        $entity = $event->getEntityInstance();

        if(!($entity instanceof Albums || $entity instanceof Instrumentals)) {
            return;
        }
        if($entity instanceof Albums) {
            if($entity->getImage() == null) {
                $entity->setImage('default.jpg');
            }
        }

        if($entity instanceof Instrumentals) {


            if($entity->getFichierAudio() == null) {
                $entity->setFichierAudio('default.mp3');
            }
        }

    }
    public function updateImageOrAudio(BeforeEntityUpdatedEvent $event) {

        /*
                $request = Request::createFromGlobals();
                $formData = $request->request->all();*/

        $oldNameFile = $this->oldFile;

        $entity = $event->getEntityInstance();
        $project_dir = $this->kernel->getProjectDir();


        $oldFilePathAlbum = $project_dir.'/public/uploads/'.$oldNameFile;
        $oldFilePathInstru = $project_dir.'/public/audio/'.$oldNameFile;
        //si l'image de update ou l'instrumental de update est different de l'ancien
        if(!($entity instanceof Albums || $entity instanceof Instrumentals)) {
            return;
        }
        if($entity instanceof Albums) {

            if($entity->getImage() == null) {
                $entity->setImage('default.jpg');
            }

            if($oldNameFile != $entity->getImage() ) {
                if (file_exists($oldFilePathAlbum)) {
                    unlink($oldFilePathAlbum);
                }
            }

        }

        if($entity instanceof Instrumentals) {
            if($entity->getFichierAudio() == null) {
                $entity->setFichierAudio('default.mp3');
            }
            if($oldNameFile != $entity->getFichier_audio() ) {
                if (file_exists($oldFilePathInstru)) {
                    unlink($oldFilePathInstru);
                }
            }
        }
    }
    function checkContrat(BeforeEntityPersistedEvent $event) {
        $entity = $event->getEntityInstance();
        if($entity instanceof Albums) {
            $user = $this->security->getUser();
            $entity->setUser($user);
        }

    }

    public function onEntityDeleted(BeforeEntityDeletedEvent $event) {
        $entity = $event->getEntityInstance();
        if($entity instanceof Instrumentals) {
            $project_dir = $this->kernel->getProjectDir();
            $oldAudio = $project_dir . '/public/audio/' . $entity->getFichierAudio();
            $oldOrigin = $project_dir . '/public/original/' . $entity->getOriginalfile();
            if (file_exists($oldAudio)) {
                unlink($oldOrigin);
            }
            if(file_exists($oldAudio)) {
                unlink($oldAudio);
            }
        }
        //album

        if($entity instanceof Albums) {
            $project_dir = $this->kernel->getProjectDir();
            $allAudios  = $entity->getInstrumentals();
            $imageAlbum = $project_dir . '/public/uploads/' . $entity->getImage();
            if (file_exists($imageAlbum)) {
                unlink($imageAlbum);
            }
            foreach ($allAudios as $audio) {
                $oldAudio = $project_dir . '/public/audio/' . $audio->getFichierAudio();
                $oldOrigin = $project_dir . '/public/original/' . $audio->getOriginalfile();
                if (file_exists($oldAudio)) {
                    unlink($oldOrigin);
                }
                if (file_exists($oldAudio)) {
                    unlink($oldAudio);
                }
            }
        }
    }

    public function updateImage(Event $event) {
        $entity = $event->getEntityInstance();
        if($entity instanceof Albums) {
            $project_dir = $this->kernel->getProjectDir();
            $oldimage = $project_dir . '/public/audio/' . $entity->getImage();

            if (file_exists($oldimage)) {
                unlink($oldimage);
            }
        }
    }
    public function checkCache(BeforeEntityPersistedEvent $event) {
        $entity = $event->getEntityInstance();
        if($entity instanceof Albums || $entity instanceof Instrumentals) {
            $this->cache->delete('filter_album');
        }
    }

    public function checkCachedelete(BeforeEntityDeletedEvent $event) {
        $entity = $event->getEntityInstance();
        if($entity instanceof Albums || $entity instanceof Instrumentals) {
            $this->cache->delete('filter_album');
        }
    }
    public function checkCacheUpdate(BeforeEntityUpdatedEvent $event) {
        $entity = $event->getEntityInstance();
        if($entity instanceof Albums || $entity instanceof Instrumentals) {
            $this->cache->delete('filter_album');
        }
    }
}
