<?php

namespace App\EventListener;

use App\Entity\Galerie;
use Vich\UploaderBundle\Event\Event;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class VichRemoveCacheListener
{
    private $cacheManager;

    public function __construct(CacheManager $cacheManager) {       
        $this->cacheManager = $cacheManager;
    }

    public function onVichUploaderPreRemove(Event $event)
    {   
        $object = $event->getObject();
        //$mapping = $event->getMapping();
        if ($object instanceof Galerie) { // à tester
            $this->cacheManager->remove($object->getcoverImage());
        }
    }

}