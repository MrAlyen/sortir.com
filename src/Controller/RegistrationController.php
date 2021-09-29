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
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Form\Form;

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

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('pavie.sylvain@xn--univers-athl-meb.fr', 'Sylvain'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            return $this->redirectToRoute('main_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    public function ajouter($request, $passwordEncoder): \Symfony\Component\Form\FormInterface
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

            $user->setRoles([]);
            $user->setActive(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $form;
    }

    /**
     * @Route("/admin/ajoutUtilisateur", name="app_admin_ajout_utilisateur")
     */
    public function ajoutUtilisateur(Request $request, UserRepository $userRepository,CampusRepository $campusRepository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $formCsv = $this->createForm(ImportCsvFormType::class);
        $formCsv->handleRequest($request);

        $newUserNbr = 0;
        $updateUser = 0;

        if($formCsv->isSubmitted() && $formCsv->isValid()){
            $file = $formCsv->get('file')->getData();
            $fileExtension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $normalizer = [new ObjectNormalizer];
            $encoder = [
                new CsvEncoder(),
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

                if($newUser['admin'] == 'true'){
                    $user->setRoles(["ROLE_ADMIN"]);
                } else {
                    $user->setRoles(["ROLE_USER"]);
                }

                if($newUser['active'] == 'true'){
                    $user->setActive(true);
                } else {
                    $user->setActive(false);
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
        
        $formRegister = $this->ajouter($request, $passwordEncoder);
        
        return $this->render('admin/ajoutUtilisateur.html.twig', [
            'csvForm' => $formCsv->createView(),
            'newUser' => $newUserNbr,
            'updateUser' => $updateUser,
            'registerForm' => $formRegister->createView(),
        ]);
    }
}
