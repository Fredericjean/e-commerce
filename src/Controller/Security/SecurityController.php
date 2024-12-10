<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app.login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Security/login.html.twig', [
            'lastUsername' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/post-login', name: 'app.post_login', methods: ['GET'])]
    public function postLogin(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app.admin.users.index');
        } else {
            return $this->redirectToRoute('app.user.profil', [
                'id' => $user->getId(),
            ]);
        }
    }

    #[Route('/register', name: 'app.register')]
    public function register(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $hasher->hashPassword($user, $form->get('password')->getData())
            );

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Bienvenue :)');
            return $this->redirectToRoute('app.login');
        }

        return $this->render('Security/register.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/profile', name: 'app.user.profil', methods: ['GET'])]
    public function profile(int $id, UserRepository $userRepository): Response
    {

        $user = $userRepository->find($id);

        if (!$user) {
            $this->addFlash('error', 'utilisateur pas trouvé');
        }
        return $this->render('Frontend/Users/profile.html.twig', [
            'user' => $user
        ]);
    }
}
