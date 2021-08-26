<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UpdateImagesController extends AbstractController
{
    public function __invoke($data, CacheManager $imagineCacheManager, Request $request) // si pagination active Paginator $data
    {   
        foreach($data as $image) {
            if(strpos($image->getUrl(), 'picsum')) { // à virer en prod
                $image->setPathUrl($image->getUrl());
                $image->setPathUrlCache($image->getUrl());
                $image->setPathUrlWebpCache($image->getUrl());
            } else {
                $image->setPathUrl($request->getSchemeAndHttpHost().$this->getParameter('galerie_content_path').$image->getUrl());
                $image->setPathUrlCache($imagineCacheManager->resolve('/','galerie_content_thumb','galerie_images_cache').$image->getUrl());
                $image->setPathUrlWebpCache($imagineCacheManager->resolve('/','galerie_content_thumb_webp','galerie_images_cache').$image->getUrl());
            }
        }
        return $data;
    }
}