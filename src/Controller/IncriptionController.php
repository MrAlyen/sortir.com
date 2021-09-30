<?php

namespace App\Controller;


use App\Form\SortieFilterType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use App\Utilities\Tools;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Sortie;


/**
 * @Route("/sortir", name="sortie")
 */
class MainController extends AbstractController
{
     /**
     * @Route("/incrire", name="_incrire")
     */
    public function incrire(Request $request,SortieRepository $sortieRepository,int $id,EntityManagerInterface $entityManager):Response{

        $user =  $this->getUser();
        $sortie = $sortieRepository->find($id);

        $sortie->addEstInscrit($user);

        $entityManager->persist($sortie);
        $entityManager->flush();
        $this->addFlash('sucess','Vous etes bien inscrit à la sortie');

        return $this->render('main/accueil.html.twig',[]);
    }

     /**
     * @Route("/desincrire", name="_desincrire")
     */
    public function desincrire(Request $request,SortieRepository $sortieRepository,int $id,EntityManagerInterface $entityManager):Response{

        $user =  $this->getUser();
        $sortie = $sortieRepository->find($id);

        $sortie->removeEstInscrit($user);

        $entityManager->persist($sortie);
        $entityManager->flush();
        $this->addFlash('sucess','Vous etes bien désinscrit à la sortie');

        return $this->render('main/accueil.html.twig',[]);
    }

}
