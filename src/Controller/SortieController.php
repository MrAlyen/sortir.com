<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Form\SortieModifType;
use App\Form\SortieType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;




/**
 * @Route("/sortir", name="sortie")
 */
class SortieController extends AbstractController
{

    /**
     * @Route("/add", name="_add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager,CampusRepository $campusRepository, EtatRepository $etatRepository): Response
    {

        $sortie = new Sortie();
        $sortieform = $this->createForm(SortieType::class, $sortie);

        $sortieform->handleRequest($request);

        if ($sortieform->isSubmitted() && $sortieform->isValid()){

            if ($sortieform->get('saveAndAdd')->isClicked()){
                $etats = $etatRepository->findEtat('En création');
            }else{
                $etats = $etatRepository->findEtat('Ouverte');
            }

            $user =  $this->getUser();

            foreach ( $etats as $etat ){
                $sortie->setEtats($etat);
            }

            $sortie->setOrganisateur($user);
            $idCampus = $sortie->getOrganisateur()->getCampus()->getId();
            $campus = $campusRepository->findCampus($idCampus);

            foreach ( $campus as $campu ) {
                $sortie->setSiteOrganisateur($campu);
            }

            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('sucess','Votre sortie est bien enregistré');

            return $this->redirectToRoute('main_accueil');
        }

        return $this->render('sortie/addSortie.html.twig', [
            'sortie' => $sortieform->createView(),
        ]);
    }

    /**
     * @Route("/details/{id}", name="_details")
     */
    public function details(SortieRepository $sortieRepository,int $id):Response{

        $sortie = $sortieRepository->detailsSortie($id);

        return $this->render('sortie/details.html.twig',[
            "sortie" => $sortie
        ]);
    }

    /**
     * @Route("/modif/{id}", name="_modif")
     */
    public function modif(Request $request,SortieRepository $sortieRepository,int $id,EntityManagerInterface $entityManager):Response{

        $sortie = $sortieRepository->find($id);
        $modifSortie = $this->createForm(SortieModifType::class, $sortie);

        $modifSortie->handleRequest($request);
        if ($modifSortie->isSubmitted() ){

            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('sucess','Votre sortie est bien modifier');
            return $this->redirectToRoute('main_accueil');
        }


        return $this->render('sortie/modif.html.twig',[
            'modifSortie' => $modifSortie->createView(),
        ]);
    }

    /**
     * @Route("/publish/{id}", name="_publish")
     */
    public function publish(Request $request,EtatRepository $etatRepository,SortieRepository $sortieRepository,int $id,EntityManagerInterface $entityManager):Response{

        $sortie = $sortieRepository->find($id);
        $sortieform = $this->createForm(SortieModifType::class, $sortie);


        $sortieform->handleRequest($request);
        if ($sortieform->isSubmitted() && $sortieform->isValid()){

            $etats = $etatRepository->findEtat("Ouverte");

            foreach ($etats as $etat){
                $sortie->setEtats($etat);
            }

            $entityManager->persist($sortie);

            $entityManager->flush();
            $this->addFlash('sucess','Votre sortie est maintenant Ouverte');
            return $this->redirectToRoute('main_accueil');
        }

        return $this->render('sortie/publish.html.twig',[
            'sortie' => $sortieform->createView(),
        ]);
    }

    /**
     * @Route("/cancel/{id}", name="_cancel")
     *
     */
    public function cancel(Request $request,SortieRepository $sortieRepository,int $id,EntityManagerInterface $entityManager):Response{

        $sortie = $sortieRepository->find($id);
        $sortieform = $this->createForm(SortieModifType::class, $sortie);

        $sortieform->handleRequest($request);
        if ($sortieform->isSubmitted() && $sortieform->isValid()){

            $entityManager->remove($sortie);
            $entityManager->flush();
            $this->addFlash('sucess','Votre sortie a été supprimée');
            return $this->redirectToRoute('main_accueil');
        }


        return $this->render('sortie/cancel.html.twig',[
            'sortie' => $sortieform->createView(),
        ]);
    }

    /**
     * @Route("/inscrire/{id}", name="_inscrire")
     */
    public function inscrire(SortieRepository $sortieRepository,int $id,EntityManagerInterface $entityManager):Response{

        dump($id);
        $user =  $this->getUser();
        $sortie = $sortieRepository->find($id);
        dump($user);
        $sortie->addEstInscrit($user);

        $entityManager->persist($sortie);
        $entityManager->flush();
        $this->addFlash('sucess','Vous etes bien inscrit à la sortie');

        return $this->render('main/accueil.html.twig');
    }
}