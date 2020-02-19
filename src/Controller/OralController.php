<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OralController extends AbstractController
{
    /**
     * @Route("/oral", name="oral")
     */
    public function index()
    {
        return $this->render('oral/index.html.twig', [
            'controller_name' => 'OralController',
        ]);
    }
}
