<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Form\VilleType;
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
    public function accueil(): Response
    {
        return $this->render('main/accueil.html.twig');
    }
    /**
     * @Route("/sortir/creer_sortie", name="main_creer_sortie")
     */
    public function creer_sortie(Request $request, EntityManagerInterface $entityManager, Tools $tools): Response
    {

        $sortie = new Sortie();
        $sortieform = $this->createForm(SortieType::class, $sortie);

        $sortieform->handleRequest($request);
        if ($sortieform->isSubmitted() && $sortieform->isValid()){

            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('sucess','Votre sortie est bien enregistré');
            return $this->redirectToRoute('main_accueil');
        }


        return $this->render('main/creer_sortie.html.twig', [
            'sortie' => $sortieform->createView(),

        ]);
    }
}
