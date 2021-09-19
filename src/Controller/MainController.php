<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('main/creer_sortie.html.twig');
    }
}
