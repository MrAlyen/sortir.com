<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Ville;
use App\Form\VilleFormType;
use App\Form\GestionVilleType;
use App\Repository\VilleRepository;



class GestionVilleController extends AbstractController
{
    /**
     * @Route("/admin/gestion-ville", name="app_admin_gestion_ville")
     */
    public function index(Request $request, VilleRepository $villerepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $villes = $villerepository->findAll();
        $ville = new Ville();   

        $form = $this->createForm(VilleFormType::class);
        $form->handleRequest($request);

        $gestionForm = $this->createForm(GestionVilleType::class);
        $gestionForm->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if ($villeId = $request->request->get('modifier')) {
                $villeUp = $request->request->get('ville_form');
                $ville = $villerepository->find($villeId);

                $ville->setNom($villeUp['nom']);
                $ville->setCodePostal($villeUp['codePostal']);

                $entityManager->persist($ville);
                $entityManager->flush();

            } else {
                $nom = $form->get('nom')->getData();
                $postal = $form->get('codePostal')->getData();

                $ville->setNom($nom);
                $ville->setCodePostal($postal);

                $entityManager->persist($ville);
                $entityManager->flush();
                array_push($villes, $ville);
            }
        }

        if($gestionForm->isSubmitted() && $gestionForm->isValid()){
                $villeId = $request->request->get('supprimer');

                $ville = $villerepository->find($villeId);

                $entityManager->remove($ville);
                $entityManager->flush();
                $villes = $villerepository->findAll();
            
        }
        
        return $this->render('admin/gestionVille.html.twig', [
            'controller_name' => 'GestionVilleController',
            'ajoutVilleForm' => $form->createView(),
            'modifierVilleForm' => $form->createView(),
            'gestionVilleForm' => $gestionForm->createView(),
            'villes' => $villes,
            'gestionForm' => $gestionForm,
            'request' => $request,
            'modal' => $this,
        ]);
    }
}
