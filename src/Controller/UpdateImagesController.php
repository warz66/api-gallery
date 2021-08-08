<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UpdateImagesController extends AbstractController
{
    public function __invoke(Paginator $data, CacheManager $imagineCacheManager, Request $request)
    {   
        foreach($data as $image) {
            $image->setPathUrl($request->getSchemeAndHttpHost().$this->getParameter('galerie_content_path').$image->getUrl());
            $image->setPathUrlCache($imagineCacheManager->resolve('/','galerie_content_thumb','galerie_images_cache').$image->getUrl());
        }
        return $data;
    }
}