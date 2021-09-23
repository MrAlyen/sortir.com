<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Form\DesactiveFormType;
use App\Repository\UserRepository;

class DesactiverSupprimerUserController extends AbstractController
{
    /**
     * @Route("/desactiverSupprimerUser", name="app_supprimer_user")
     */
    public function desactiverSupprimer(UserRepository $userRepository, Request $request): Response
    {
        $utilisateurs = $userRepository->findAll();

        $form = $this->createForm(DesactiveFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->get('user')->getData();
            $activer = $form->get('choice')->get(0)->getData();
            $desactiver = $form->get('choice')->get(1)->getData();
            $supprimer = $form->get('choice')->get(2)->getData();

            if($activer){
                $this->activer($request, $user, $userRepository);

            } elseif($desactiver) {
                $this->desactiver($request, $user, $userRepository);
            } elseif($supprimer) {
                $this->supprimer($request, $user, $userRepository);
            }
        }

        return $this->render('admin/desactiverSupprimer.html.twig', [
            'controller_name' => 'DesactiverSupprimerUserController',
            'desactiveForm' => $form->createView(),
        ]);
    }

    private function activer($request, $user, $userRepository){
        $user->setActive(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }

    private function desactiver($request, $user, $userRepository){
        $user->setActive(false);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }

    private function supprimer($request, $user, $userRepository){
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
    }
}
