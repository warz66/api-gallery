<?php

namespace App\EventListener;

use App\Entity\Galerie;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;

class CreateCoverImageCacheListener
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

    private function makeImgCache(Galerie $galerie) {
        if (false == $this->cacheManager->isStored($galerie->getCoverImage(), $this->filterName)) {
            $binary = $this->dataManager->find($this->filterName, $galerie->getCoverImage());
            $this->cacheManager->store($this->filterManager->applyFilter($binary, $this->filterName), $galerie->getCoverImage(), $this->filterName);
        } 
    }

    public function postPersist(Galerie $galerie)
    {   
        $this->makeImgCache($galerie);        
    }
    
    public function postUpdate(Galerie $galerie)
    {   
        $this->makeImgCache($galerie);     
    }

}