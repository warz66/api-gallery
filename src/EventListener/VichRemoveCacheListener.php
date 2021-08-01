<?php

namespace App\EventListener;

use App\Entity\Galerie;
use App\Entity\Publication;
use Vich\UploaderBundle\Event\Event;
use Symfony\Component\Filesystem\Filesystem;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class VichRemoveCacheListener
{
    private $cacheManager;
    private $filesystem;
    private $parameterBag;

    public function __construct(CacheManager $cacheManager, Filesystem $filesystem, ParameterBagInterface $parameterBag) {       
        $this->cacheManager = $cacheManager;
        $this->filesystem = $filesystem;
        $this->parameterBag = $parameterBag;
    }

    public function onVichUploaderPreRemove(Event $event)
    {   
        $object = $event->getObject();
        //$mapping = $event->getMapping();
        if ($object instanceof Galerie) { // à tester
            $this->cacheManager->remove($this->parameterBag->get('galerie_cover_path') . $object->getcoverImage());
        }
        if ($object instanceof Publication) { // à tester
            $this->cacheManager->remove($this->parameterBag->get('publication_cover_path') . $object->getcoverImage());
        }
    }

}