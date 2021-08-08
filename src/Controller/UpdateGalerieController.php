<?php

namespace App\Controller;

use App\Entity\Galerie;
use Symfony\Component\HttpFoundation\Request;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UpdateGalerieController extends AbstractController
{
    public function __invoke(Galerie $galerie, CacheManager $imagineCacheManager, Request $request)
    {   

        $galerie->setPathImg($request->getSchemeAndHttpHost().$this->getParameter('galerie_content_path'));
        $galerie->setPathImgCache($imagineCacheManager->resolve('/','galerie_content_thumb','galerie_images_cache'));
        $galerie->setPathImgCover($request->getSchemeAndHttpHost().$this->getParameter('galerie_cover_path'));
        $galerie->setPathImgCoverCache($imagineCacheManager->resolve('/','galerie_cover_thumb','galerie_images_cache'));

        return $galerie;
    }
}
