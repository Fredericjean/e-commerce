<?php

namespace App\Controller\Backend;

use App\Repository\GenderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/models', name:'app.admin.models')]
class ModelController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {}

    #[Route('', name: '.index')]
    public function index(GenderRepository $genderRepository): Response
    {
        return $this->render('backend/model/index.html.twig', [
            'genders' => $genderRepository ->findall(),
        ]);
    }

    #[Route]
}
