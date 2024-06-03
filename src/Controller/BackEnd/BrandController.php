<?php

namespace App\Controller\Backend;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


#[Route('/admin/brands', name: 'app.admin.brands')]
class BrandController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    #[Route('', name: '.index')]
    public function index(BrandRepository $brandRepository): Response
    {

        return $this->render('Backend/Brands/index.html.twig', [
            'brands' => $brandRepository->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response | RedirectResponse
    {

        $brand = new Brand;
        $form = $this->createForm(BrandType::class, $brand);
        $form->handlerequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($brand);
            $this->em->flush();
            $this->addFlash('success', 'Marque créée avec succès');

            return $this->redirectToRoute('app.admin.brands.index');
        }

        return $this->render('Backend/Brands/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(Request $request, ?Brand $brand): Response | RedirectResponse
    {
        if (!$brand) {

            $this->addFlash('error', 'La marque n\'a pas été trouvée');

            return $this->redirectToRoute('app.admin.brands.index');
        }

        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($request);
            $this->em->flush();
            $this->addFlash('success', 'Marque modifiée avec succès');

            return $this->redirectToRoute('app.admin.brands.index');
        }

        return $this->render('Backend/Brands/index.html.twig');
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Brand $brand, Request $request): RedirectResponse
    {
        if (!$brand) {
            $this->addFlash('error', 'La marque n\'a pas été trouvée');
            $this->redirectToRoute('app.admin.brands.index');
        }

        if ($this->isCsrfTokenValid('delete' . $brand->getId(), $request->request->get('token'))) {
            $this->em->remove($brand);
            $this->em->flush();
            $this->addFlash('success', 'Marque supprimée avec succès');
        } else {
            $this->addFlash('error', 'Token CSRF invalide');
        }
        return $this->redirectToRoute('app.admin.brands.index');
    }
}
