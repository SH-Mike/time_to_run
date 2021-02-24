<?php

namespace App\Controller;

use App\Repository\OutingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutingController extends AbstractController
{
    /**
     * @Route("/outings", name="outings_index")
     */
    public function index(OutingRepository $outingRepository): Response
    {
        if(!$this->getUser()){
            $this->addFlash('danger', "Vous devez être connecté pour visiter cette page.");
            return $this->redirectToRoute('login');
        }

        $outings = $outingRepository->findByUser($this->getUser());
        return $this->render('outing/index.html.twig', [
            'outings' => $outings,
        ]);
    }
}
