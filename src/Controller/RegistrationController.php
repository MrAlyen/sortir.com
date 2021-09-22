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
use App\Repository\CampusRepository;

use App\Repository\UserRepository;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $user->setRoles(["ROLE_ADMIN"]);
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
    public function ajoutUtilisateur(Request $request, UserRepository $userRepository,CampusRepository $campusRepository, UserPasswordEncoderInterface $passwordEncoder){
        $form = $this->createForm(ImportCsvFormType::class);
        $form->handleRequest($request);

        $newUserNbr = 0;
        $updateUser = 0;

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

            

            foreach($data as $newUser){
                if(!$user = $userRepository->findOneBy(['email' => $newUser['email']])){
                    $user = new User();
                    $newUserNbr++;
                } else {
                    $updateUser++;
                }

                $campus = $campusRepository->find($newUser['campus_id']);

                $user->setCampus($campus);
                $user->setEmail($newUser['email']);

                if($newUser['admin'] == true){
                    $user->setRoles(["ROLE_ADMIN"]);
                } else {
                    $user->setRoles([]);
                }

                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $newUser['password']
                    )
                );

                $user->setPseudo($newUser['pseudo']);
                $user->setPrenom($newUser['prenom']);
                $user->setNom($newUser['nom']);
                $user->setTelephone($newUser['telephone']);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }
        }
        
        
        return $this->render('admin/ajoutUtilisateur.html.twig', [
            'csvForm' => $form->createView(),
            'newUser' => $newUserNbr,
            'updateUser' => $updateUser,
        ]);
    }
}
