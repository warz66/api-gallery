<?php

namespace App\Service;

use App\Entity\Galerie;
use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;

class ImagesOrderBy
{

    public function get(Galerie $galerie, ImageRepository $imageRepo): ArrayCollection
    {
        switch ($galerie->getParOrdre()) {
            case 'OrdreTableauAsc': 
                $images = $imageRepo->getImagesByOrdreTableauAsc($galerie->getId());
                break;
            case 'OrdreTableauDesc': 
                $images = $imageRepo->getImagesByOrdreTableauDesc($galerie->getId());
                break;
            default:
                $images = $imageRepo->getImagesByOrdreTableauAsc($galerie->getId());
                break;
        }
        return $images;
    }
    
}