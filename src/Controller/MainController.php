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

class MainController extends AbstractController
{
    /**
     * @Route("/sortir", name="main_accueil")
     */
    public function accueil(SortieRepository $repository,Request $request): Response
    {
        $sorties = $repository->sortieFormNowtoDown();

        $filtre= new Sortie();
        $filtreForm = $this->createForm(SortieFilterType::class,$filtre);
        $filtreForm->handleRequest($request);

        $filtreActif = null;

        if ($filtreForm->isSubmitted()){

           $filtreActif = $repository->whereFiltre($filtreForm);

        }

        if ($filtreActif != null){
            $tabSorties = $filtreActif;
        }else{
            $tabSorties = $sorties;
        }

        return $this->render('main/accueil.html.twig',[

            'filtre' => $filtreForm->createView(),
            'sorties' => $tabSorties
        ]);
    }

}
