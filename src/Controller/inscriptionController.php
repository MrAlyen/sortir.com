<?php

namespace App\Controller;


use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortir", name="sortie")
 */
class inscriptionController extends AbstractController
{
    /**
     * @Route("/inscrire/{id}", name="_inscrire")
     */
    public function inscrire(SortieRepository $sortieRepository,int $id,EntityManagerInterface $entityManager){


        $user =  $this->getUser();
        $sortie = $sortieRepository->find($id);

        $sortie->addEstInscrit($user);

        $entityManager->persist($sortie);
        $entityManager->flush();
        $this->addFlash('sucess','Vous etes bien inscrit à la sortie');

        return $this->redirectToRoute('main_accueil');
    }

    /**
     * @Route("/desincrire/{id}", name="_desincrire")
     */
    public function desincrire(Request $request,SortieRepository $sortieRepository,int $id,EntityManagerInterface $entityManager):Response{

        $user =  $this->getUser();
        $sortie = $sortieRepository->find($id);

        $sortie->removeEstInscrit($user);

        $entityManager->persist($sortie);
        $entityManager->flush();
        $this->addFlash('sucess','Vous etes bien désinscrit à la sortie');

        return $this->redirectToRoute('main_accueil');
    }
}