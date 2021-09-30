<?php

namespace App\Controller;


use App\Form\ModifierProfilType;
use App\Form\SortieFilterType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Utilities\Tools;
use ContainerX2QsL9A\getModifierProfilTypeService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    /**
     * @Route("/sortir/profil", name="main_modifierProfil")
     */
    public function ModifierProfil(Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $user = $this->getUser();
        $profil = $this->createForm(ModifierProfilType::class, $user);

        $profil->handleRequest($request);
        if ($profil->isSubmitted() && $profil->isValid()){

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            if($request->isMethod('POST')){
                $entityManager = $this->getDoctrine()->getManager();
                $user = $this->getUser();

                // verification de le correspondance des deux mot de passe
                if ($request->request->get('passe') == $request->request->get('passe2')){
                    $user->setPassword($passwordHasher->hashPassword($user, $request->request->get('passe2')));
                    $entityManager->flush();
                    $this->addFlash('message','Votre profil est bien modifier');
                    return $this->redirectToRoute('main_modifierProfil');
                }else{
                    $this->addFlash('erreur', 'Les deux mot de passe ne correspondent pas!');
                }
            }
        }

        return $this->render('main/modifierProfil.html.twig',['profil' => $profil->createView()]);
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
