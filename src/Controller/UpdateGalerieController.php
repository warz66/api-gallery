<?php

namespace App\Controller;

use App\Entity\Galerie;
use Symfony\Component\HttpFoundation\Request;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateGalerieController extends AbstractController
{
    public function __invoke(Galerie $galerie, CacheManager $imagineCacheManager, Request $request)
    {   

        $galerie->setPathImgCover($request->getSchemeAndHttpHost().$this->getParameter('galerie_cover_path').$galerie->getCoverImage());

        $galerie->setPathImgCoverCache($imagineCacheManager->getBrowserPath($galerie->getCoverImage() ,'galerie_cover_thumb'));

        $images = $galerie->getImages();

        foreach($images as $image) {
            $image->setPathUrl($request->getSchemeAndHttpHost().$this->getParameter('galerie_content_path').$image->getUrl());
            $image->setPathUrlCache($imagineCacheManager->getBrowserPath($image->getUrl(), 'galerie_content_thumb'));
        }

        $galerie->setImages($images);

        return $galerie;
    }
}
