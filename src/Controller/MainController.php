<?php

namespace App\Controller;


use App\Form\SortieFilterType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Sortie;
use App\Entity\User;

class MainController extends AbstractController
{
    /**
     * @Route("/sortir", name="main_accueil")
     */
    public function accueil(SortieRepository $repository,UserRepository $userRepository,Request $request,EtatRepository $etatRepository): Response
    {
        $sorties = $repository->sortieFormNowtoDown();

        $filtre= new Sortie();
        $filtreForm = $this->createForm(SortieFilterType::class,$filtre);
        $filtreForm->handleRequest($request);

        $filtreActif = null;

        if ($filtreForm->isSubmitted()){
            $user = $this->getUser();
            $userId = $user->getId();
           $filtreActif = $repository->whereFiltre($filtreForm,$userId,$etatRepository);

        }

        if ($filtreActif != null){
            $tabSorties = $filtreActif;
        }else{
            $tabSorties = $sorties;
        }

        return $this->render('main/accueil.html.twig',[

            'filtre' => $filtreForm->createView(),
            'sorties' => $tabSorties,
            "controller"=>$this
        ]);
    }

    public function inscrire(int $id){

        $entityManager = $this->getDoctrine()->getManager();
        $sortieRepository = $entityManager->getRepository(Sortie::class);


        $user =  $this->getUser();
        $sortie = $sortieRepository->find($id);

        $sortie->addEstInscrit($user);

        $entityManager->persist($sortie);
        $entityManager->flush();
        $this->addFlash('sucess','Vous etes bien inscrit à la sortie');


    }

}
