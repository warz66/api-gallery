<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Galerie;
use App\Form\GalerieType;
use App\Service\ImagesOrderBy;
use App\Repository\ImageRepository;
use App\Repository\GalerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminGalerieController extends AbstractController
{
    /**
     * 
     * @Route("/admin/galerie/{page<\d+>?1}", name="admin_galerie_index")
     */
    public function index($page, GalerieRepository $repo, Request $request, PaginatorInterface $paginator)
    {   
        // on crée la pagination
        $nbPage = 30;
        $data = $repo->findGaleries(0);
        $galeries = $paginator->paginate($data, $request->query->getInt('page',$page), $nbPage);
        $galeries->setCustomParameters([
            'align' => 'center',
        ]);

        return $this->render('admin/galerie/index.html.twig', [
            'galeries' => $galeries
        ]);
    }

    /**
     * @Route("/admin/galerie/new", name="admin_galerie_new")
     */
    public function new(EntityManagerInterface $manager, Request $request)
    {
        $galerie = new galerie();
        $form = $this->createForm(GalerieType::class, $galerie);
        $form->handleRequest($request);
        
        if ( $form->isSubmitted() && $form->isValid() ) {

            //on enregistre sur le server les images aprés le post persist
            if(!empty($_FILES['uploadFile']['tmp_name'][0])) {
                $errorFile='';
                for($i = 0; $i < count($_FILES['uploadFile']['tmp_name']); ++$i) {
                    if(is_uploaded_file($_FILES['uploadFile']['tmp_name'][$i]) && $_FILES['uploadFile']['size'][$i] < 1000000 && $_FILES['uploadFile']['error'][$i] === 0 && ($_FILES['uploadFile']['type'][$i] === 'image/jpeg' || $_FILES['uploadFile']['type'][$i] === 'image/png')) {
                        $source_path = $_FILES['uploadFile']['tmp_name'][$i];
                        $file = uniqid() . '_' . $_FILES['uploadFile']['name'][$i];
                        $img = new Image;
                        $img->setSource_path($source_path);
                        $img->setGalerie_content_path($this->getParameter('galerie_content_path'));
                        $img->setUrl($file);
                        $img->setGalerie($galerie);
                        $manager->persist($img);
                    } else {
                        unlink($_FILES['uploadFile']['tmp_name'][$i]); // a tester
                        $errorFile = $errorFile . $_FILES['uploadFile']['name'][$i].', ';
                    }
                }
            }

            $manager->persist($galerie);
            $manager->flush();

            if (empty($errorFile)) {
                $this->addFlash('success', "La galerie <strong>{$galerie->getTitle()}</strong> a bien été enregistrée !");
            } else {
                $this->addFlash('warning', "La galerie <strong>{$galerie->getTitle()}</strong> a bien été enregistrée !<br>
                Cependant la ou les images <strong>{$errorFile}</strong> n'ont pas pu être enregistrées");
            }

            return $this->redirectToRoute('admin_galerie_edit', ['id' => $galerie->getId()]);
        }

        return $this->render('admin/galerie/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/galerie/{id}/{page<\d+>?1}/edit", name="admin_galerie_edit")
     */
    public function edit(Galerie $galerie, Request $request, EntityManagerInterface $manager, $page, PaginatorInterface $paginator, ImageRepository $repo, ImagesOrderBy $imagesOrderBy)
    {   

        // si on affiche une galerie que partiellement avec une page de départ différente de 1 cela peut creer des effets indésirables
        if ($page>1) {
            return $this->redirectToRoute('admin_galerie_edit', ['id' => $galerie->getId(), 'page' => '1']);    
        }

        $nbImgPage = 30;

        /*switch ($galerie->getParOrdre()) {
            case 'OrdreTableauAsc': 
                $images = $repo->getImagesByOrdreTableauAsc($galerie->getId());
                //dump($images);
                break;
            case 'OrdreTableauDesc': 
                $images = $repo->getImagesByOrdreTableauDesc($galerie->getId());
                //dump($images);
                break;
            default:
                $images = $repo->getImagesByOrdreTableauAsc($galerie->getId());
                //dump($images);
                break;
        }*/
        $images = $imagesOrderBy->get($galerie, $repo);

        if ($request->isMethod('post')) { // si on ne voit pas qu'on rentre dans cette condition c a cause du redirect a la fin
            $data = $request->request->all();
            $pagination = $paginator->paginate($images, $request->query->getInt('page',1), $nbImgPage * (int)$data["galerie"]["up_to_page"]);
        } else {
            $pagination = $paginator->paginate($images, $request->query->getInt('page',$page), $nbImgPage);
        }
        $form = $this->createForm(GalerieType::class, $galerie,[ "pagination" => $pagination ]);
        
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {

            $galerieRequest=$request->request->get('galerie');
            if(isset($galerieRequest['images'])){
                $imageRequest=$galerieRequest['images'];
                foreach($imageRequest as $image) {
                    $captionStatus=explode("-",$image['captionStatus'],3);
                    if($captionStatus[0] === '1') {
                        $imageOrigin = $repo->findOneBy(['id' => $captionStatus[1]]);
                        $manager->remove($imageOrigin);
                    }
                }
            }

            //on enregistre sur le server les images aprés le post persist
            if(!empty($_FILES['uploadFile']['tmp_name'][0])) {
                $errorFile='';
                for($i = 0; $i < count($_FILES['uploadFile']['tmp_name']); ++$i) {
                    if(is_uploaded_file($_FILES['uploadFile']['tmp_name'][$i]) && $_FILES['uploadFile']['size'][$i] < 1000000 && $_FILES['uploadFile']['error'][$i] === 0 && ($_FILES['uploadFile']['type'][$i] === 'image/jpeg' || $_FILES['uploadFile']['type'][$i] === 'image/png')) {
                        $source_path = $_FILES['uploadFile']['tmp_name'][$i];
                        $file = uniqid() . '_' . $_FILES['uploadFile']['name'][$i];
                        $img = new Image;
                        $img->setSource_path($source_path);
                        $img->setGalerie_content_path($this->getParameter('galerie_content_path'));
                        $img->setUrl($file);
                        $img->setGalerie($galerie);
                        $manager->persist($img);
                    } else {
                        unlink($_FILES['uploadFile']['tmp_name'][$i]); // a tester
                        $errorFile = $errorFile . $_FILES['uploadFile']['name'][$i].', ';
                    }
                }
            }

            $manager->flush();

            //dd($pagination);

            if (empty($errorFile)) {
                $this->addFlash('success', "La galerie <strong>{$galerie->getTitle()}</strong> a bien été modifiée !");
            } else {
                $this->addFlash('warning', "La galerie <strong>{$galerie->getTitle()}</strong> a bien été modifiée !<br>
                Cependant la ou les images <strong>{$errorFile}</strong> n'ont pas pu être enregistrées");
            }

            return $this->redirectToRoute('admin_galerie_edit', ['id' => $galerie->getId()]);                                              
        }

        return $this->render('admin/galerie/edit.html.twig', [
            'trash' => ($request->get('trash')) ? true : false,
            'form' => $form->createView(), // attention si pas de redirection form plus le meme
            'galerieId' => $galerie->getId(),
            'pageMax' => ceil(count($galerie->getImages()->getValues())/$nbImgPage),
        ]);
    }

    /**
     * Index des galeries dans la corbeille
     * 
     * @Route("/admin/galerie/trash/{page<\d+>?1}", name="admin_galerie_trash_index")
     *
     */
    public function trashIndex($page, GalerieRepository $repo, PaginatorInterface $paginator, Request $request) {

        // on crée la pagination
        $nbPage = 30;
        $data = $repo->findGaleries(1);
        $galeries = $paginator->paginate($data, $request->query->getInt('page',$page), $nbPage);
        $galeries->setCustomParameters([
            'align' => 'center',
        ]);

        return $this->render('admin/galerie/trash.html.twig', [
            'galeries' => $galeries,
        ]);
    }

    /**
     * Permet d'envoyer une galerie à la corbeille
     * 
     * @Route("/admin/galerie/{id}/trash", name="admin_galerie_trash")
     *
     */
    public function trash(Galerie $galerie, EntityManagerInterface $manager) {

        $galerie->setTrash(1);
        $manager->persist($galerie);
        $manager->flush();

        $this->addFlash(
            'success',
            "La galerie <strong>{$galerie->getTitle()}</strong> a bien été envoyée à la corbeille !"
        );

        return $this->redirectToRoute("admin_galerie_index");
    }

    /**
     * Permet de restaurer une galerie
     * 
     * @Route("/admin/galerie/{id}/restore", name="admin_galerie_restore")
     *
     */
    public function restore(Galerie $galerie, EntityManagerInterface $manager) {

        $galerie->setTrash(0);
        $galerie->setStatut(0);
        $manager->persist($galerie);
        $manager->flush();

        $this->addFlash(
            'success',
            "La galerie <strong>{$galerie->getTitle()}</strong> a bien été restaurée"
        );

        return $this->redirectToRoute("admin_galerie_trash_index");
    }

    /**
     * Permet de supprimer une page
     * 
     * @Route("/admin/galerie/{id}/delete", name="admin_galerie_delete")
     *
     */
    public function delete(Galerie $galerie, EntityManagerInterface $manager) {

        $manager->remove($galerie);
        $manager->flush();

        $this->addFlash(
            'success',
            "La galerie <strong>{$galerie->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("admin_galerie_trash_index");
    }

    /**
     * Permet de vider la corbeille
     * 
     * @Route("/admin/galerie/trash/empty", name="admin_galerie_trash_empty")
     *
     */
    public function trashEmpty(EntityManagerInterface $manager, GalerieRepository $repo) {

        $galeries = $repo->findGaleries(1);

        foreach ($galeries as $galerie) {
            $manager->remove($galerie);
            $manager->flush();
        }
        
        $this->addFlash(
            'success',
            "La corbeille a bien été vidée !"
        );

        return $this->redirectToRoute("admin_galerie_trash_index");
    }

    /**
     * Permet d'envoyer la page suivante d'une galerie via l'appel ajax d'infinite scroll
     * 
     * @Route("/admin/galerie/{id}/{page<\d+>?1}/edit/next", name="admin_galerie_next")
     */
    public function next(Galerie $galerie, Request $request, $page, PaginatorInterface $paginator, ImageRepository $repo)
    {   

        $nbImgPage = 30;
        switch ($galerie->getParOrdre()) {
            case 'OrdreTableauAsc': 
                $images = $repo->getImagesByOrdreTableauAsc($galerie->getId());
                dump($images);
                break;
            case 'OrdreTableauDesc': 
                $images = $repo->getImagesByOrdreTableauDesc($galerie->getId());
                dump($images);
                break;
            default:
                $images = $repo->getImagesByOrdreTableauAsc($galerie->getId());
                dump($images);
                break;
        }
        $pagination = $paginator->paginate($images, $request->query->getInt('page',$page), $nbImgPage);

        $form = $this->createForm(GalerieType::class, $galerie,[ "pagination" => $pagination ]);

        return $this->render('admin/galerie/edit.html.twig', [
            'form' => $form->createView(),
            'galerieId' => $galerie->getId(),
            'pageMax' => ceil(count($galerie->getImages()->getValues())/$nbImgPage),
        ]);
    }

    /**
     * Permet de changer le statut d'une galerie (requête Ajax)
     * 
     * @Route("/admin/galerie/{id}/statut", name="admin_galerie_statut")
     */
    public function statut(Galerie $galerie, EntityManagerInterface $manager) {
        $statut=$_POST['statut'];
        if($statut=='true') {
            $galerie->setStatut(true);
            $manager->flush();
            return $this->json(true); 
        } else {
            $galerie->setStatut(false);
            $manager->flush();
            return $this->json(false);
        }
    }
}
