<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Form\VilleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Sortie;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_accueil")
     */
    public function accueil(): Response
    {
        return $this->render('main/accueil.html.twig');
    }
    /**
     * @Route("/creer_sortie", name="main_creer_sortie")
     */
    public function creer_sortie(): Response
    {

        $sortie = new Sortie();
        $sortieform = $this->createForm(SortieType::class, $sortie);
        $lieu = new Lieu();
        $lieuform = $this->createForm(LieuType::class,$lieu);
        $ville = new Ville();
        $villeform = $this->createForm(VilleType::class,$ville);

        //TODO traiter le formulaire

        return $this->render('main/creer_sortie.html.twig', [
            'sortie' => $sortieform->createView(),
            'lieu' => $lieuform->createView(),
            'ville' => $villeform->createView(),
        ]);
    }
}
