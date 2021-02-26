<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutingTypeController extends AbstractController
{
    /**
     * @Route("/outing/type", name="outing_type")
     */
    public function index(): Response
    {
        return $this->render('outing_type/index.html.twig', [
            'controller_name' => 'OutingTypeController',
        ]);
    }
}
