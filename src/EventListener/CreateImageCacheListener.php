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

    public function __construct(FilterManager $filterManager, DataManager $dataManager, CacheManager $cacheManager,string $filterName) {      
        $this->filterManager = $filterManager;
        $this->dataManager = $dataManager;
        $this->cacheManager = $cacheManager;
        $this->filterName = $filterName;
    }

    
    public function postPersist(Image $image)
    {   
        if (false == $this->cacheManager->isStored($image->getUrl(), $this->filterName)) {
            $binary = $this->dataManager->find($this->filterName, $image->getUrl());
            $this->cacheManager->store($this->filterManager->applyFilter($binary, $this->filterName), $image->getUrl(), $this->filterName);
        }      
    }

}