<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\ImportCsvFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/ajoutUtilisateur", name="app_admin_ajout_utilisateur")
     */
    public function ajoutUtilisateur(Request $request){
        $form = $this->createForm(ImportCsvFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('file')->getData();
            $fileExtension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $normalizer = [new ObjectNormalizer];
            $encoder = [
                new CsvEncoder()
            ];

            $serializer = new Serializer($normalizer, $encoder);
            
            $fileString = file_get_contents($file);
            $data = $serializer->decode($fileString, $fileExtension);

            dd($data);
        }
        
        
        return $this->render('admin/ajoutUtilisateur.html.twig', [
            'csvForm' => $form->createView(),
        ]);
    }
}
