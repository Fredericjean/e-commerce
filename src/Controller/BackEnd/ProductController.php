<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/product')]
class AdminController extends AbstractController
{
    #[Route('', name:".index")]
    public function createProduct ()
    {
        return $this->render('Backend/Product/create.html.twig');
    }
}
