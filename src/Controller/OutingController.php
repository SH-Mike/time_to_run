<?php

namespace App\Controller;

use App\Entity\Outing;
use App\Form\OutingType as OutingTypeForm;
use App\Repository\OutingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OutingController extends AbstractController
{
    /**
     * Shows a list of Outings from the current user
     * 
     * @param OutingRepository $outingRepository
     * 
     * @return Response
     * 
     * @Route("/outings", name="outings_index")
     */
    public function index(OutingRepository $outingRepository): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('danger', "Vous devez être connecté pour visiter cette page.");
            return $this->redirectToRoute('login');
        }

        $outings = $outingRepository->findByUser($this->getUser());
        return $this->render('outing/index.html.twig', [
            'outings' => $outings,
        ]);
    }

    /**
     * Adds a Outing to the database from the submitted form, if not submitted, shows the form
     * 
     * @param Request $request
     * 
     * @return Response
     * 
     * @Route("/outings/add", name="outings_add")
     */
    public function addOuting(Request $request)
    {
        $outing = new Outing();
        $form = $this->createForm(OutingTypeForm::class, $outing);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $outing->setUser($this->getUser());
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($outing);
            $manager->flush();
            $this->addFlash('success', 'Votre sortie a bien été ajoutée');
            return $this->redirectToRoute('outings_index');
        }

        return $this->render('outing/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
