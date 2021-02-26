<?php

namespace App\Controller;

use App\Repository\OutingTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutingTypeController extends AbstractController
{
    /**
     * @Route("/outing-types", name="outing_type_index")
     */
    public function index(OutingTypeRepository $outingTypeRepository): Response
    {
        $outingTypes = $outingTypeRepository->findAll();
        return $this->render('outing_type/index.html.twig', [
            'outingTypes' => $outingTypes,
        ]);
    }
}
