<?php

namespace App\EventListener;


use App\Entity\Image;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class DeleteFileListener
{

    private $cacheManager;

    public function __construct(CacheManager $cacheManager) {      
        $this->cacheManager = $cacheManager;
    }

    
    public function postRemove(Image $image)
    {   
        $current_dir_path = getcwd();
        $current_dir_file = $current_dir_path.$image->getUrl();
        if (is_file($current_dir_file)) {
            unlink($current_dir_file);
            $this->cacheManager->remove($image->getUrl()); // on supprime l'image du cache de liip imagine 
        }        
    }

}