<?php

namespace App\EventListener;


use App\Entity\Image;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DeleteFileListener
{

    private $cacheManager;
    private $parameterBag;

    public function __construct(CacheManager $cacheManager, ParameterBagInterface $parameterBag) {      
        $this->cacheManager = $cacheManager;
        $this->parameterBag = $parameterBag;
    }

    
    public function postRemove(Image $image)
    {   
        $current_dir_path = getcwd() . $this->parameterBag->get('galerie_content_path');
        $current_dir_file = $current_dir_path.$image->getUrl();
        if (is_file($current_dir_file)) {
            unlink($current_dir_file);
            $this->cacheManager->remove($image->getUrl()); // on supprime l'image du cache de liip imagine 
        }        
    }

}