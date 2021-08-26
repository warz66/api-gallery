<?php

namespace App\EventListener;

use App\Entity\Image;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;

class CreateImageCacheListener
{

    private $filterManager;
    private $dataManager;
    private $cacheManager;
    private $filterName;

    public function __construct(FilterManager $filterManager, DataManager $dataManager, CacheManager $cacheManager,array $filterName) {      
        $this->filterManager = $filterManager;
        $this->dataManager = $dataManager;
        $this->cacheManager = $cacheManager;
        $this->filterName = $filterName;
    }
    
    public function postPersist(Image $image)
    {   
        if(!strpos($image->getUrl(), 'picsum')) { // à virer une fois en prod et remplacer file_exists sur l'image originale.
            foreach($this->filterName as $filter) {
                if (false == $this->cacheManager->isStored($image->getUrl(), $filter)) {
                    $binary = $this->dataManager->find($filter, $image->getUrl());
                    $this->cacheManager->store($this->filterManager->applyFilter($binary, $filter), $image->getUrl(), $filter);
                }
            }
        }
    }

}