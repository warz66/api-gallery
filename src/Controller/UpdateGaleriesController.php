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

            $galerie->setPathImgCover($request->getSchemeAndHttpHost().$this->getParameter('galerie_cover_path').$galerie->getCoverImage());
        
            $galerie->setPathImgCoverCache($imagineCacheManager->getBrowserPath($galerie->getCoverImage() ,'galerie_cover_thumb'));

            $images = $galerie->getImages();

            foreach($images as $image) {
                $image->setPathUrl($request->getSchemeAndHttpHost().$this->getParameter('galerie_content_path').$image->getUrl());
                $image->setPathUrlCache($imagineCacheManager->getBrowserPath($image->getUrl(), 'galerie_content_thumb'));
            }
    
            $galerie->setImages($images);

        }
        
        return $data;
    }
}
