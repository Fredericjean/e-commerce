<?php

namespace App\Controller\Frontend;

use App\Entity\Adress;
use App\Form\AdressType;
use App\Repository\AdressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/users/adress', name: 'app.users.adress')]
class AdressController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(AdressRepository $adressRepository): Response
    {

        return $this->render('Frontend/adress/index.html.twig', [
            'adresses' => $adressRepository->findAll()
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create( Request $request): Response | RedirectResponse
    {
        $adress = new Adress;

        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted()  && $form->isValid()) {
            $this->em->persist($adress);
            $this->em->flush();
            $this->addFlash('success', 'Vous venez de créer une adresse');

            return $this->redirectToRoute('app.users.adress.index');
        }

        return $this->render(
            'Frontend/Adress/create.html.twig',
            ['form' => $form]
        );
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(Request $request, ?Adress $adress, AdressRepository $adressRepository): Response | RedirectResponse
    {
        if (!$adress) {

            $this->addFlash('error', 'L\adresse n\'a pas été trouvée');

            return $this->redirectToRoute('app.users.adress.index');
        }

        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($adress);
            $this->em->flush();
            $this->addFlash('success', 'Adresse modifiée avec succès');

            return $this->redirectToRoute('app.users.adress.index');
        }

        return $this->render('Frontend/adress/update.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Adress $adress, Request $request): RedirectResponse
    {
        if (!$adress) {

            $this->addFlash('error', 'Adresse pas trouvée');

            return $this->redirectToRoute('app.users.adress.index');
        }

        if ($this->isCsrfTokenValid('delete' . $adress->getId(), $request->request->get('token'))) {
            $this->em->remove($adress);
            $this->em->flush();
            $this->addFlash('success','Adresse supprimée avec succès');

            return $this->redirectToRoute('app.users.adress.index');
        } else {
            $this->addFlash('error', 'Vous avez le mauvais token');
        }
        return $this->redirectToRoute('app.users.adress.index');
    }
}
