<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app.login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error= $authenticationUtils ->getLastAuthenticationError();
        $lastUserName= $authenticationUtils ->getLastUsername();

        return $this->render('Security/login.html.twig', [
            'lastUsername' => $lastUserName,
            'error' =>$error,
        ]);
    }
    
    
    #[Route('register', name:'app.register')]
    public function register(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em ): Response 
    {
        $user = new User();

        $form= $this ->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user->setPassword(
                $hasher->hashPassword($user, $form->get('password')->getData())
            );

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Bienvenue :)');
            return $this->redirectToRoute('app.login');
        }
        return $this->render('Security/register.html.twig', [
            'form' => $form, ]);
    }

    

}
