<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UpdateGaleriesController extends AbstractController
{
    public function __invoke(Paginator $data, CacheManager $imagineCacheManager, Request $request)
    {   
        foreach($data as $galerie) {
            $galerie->setPathImg($request->getSchemeAndHttpHost().$this->getParameter('galerie_content_path'));
            $galerie->setPathImgCache($imagineCacheManager->resolve('/','galerie_content_thumb','galerie_images_cache'));
            $galerie->setPathImgCover($request->getSchemeAndHttpHost().$this->getParameter('galerie_cover_path'));
            $galerie->setPathImgCoverCache($imagineCacheManager->resolve('/','galerie_cover_thumb','galerie_images_cache'));
        }
        return $data;
    }
}
