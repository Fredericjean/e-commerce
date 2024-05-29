<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/product/create', name:"admin.product.create")]
    public function createProduct ()
    {
        return $this->render('Backend/Product/create.html.twig');
    }
}
