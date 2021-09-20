<?php

namespace App\Controller;

use App\Form\SortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function creer_sortie(): Response
    {
        $sortie = new Sortie();
        $sortieform = $this->createForm(SortieType::class, $sortie);

        //TODO traiter le formulaire

        return $this->render('main/creer_sortie.html.twig', [
            'sortie' => $sortieform->createView()
        ]);
    }
}
