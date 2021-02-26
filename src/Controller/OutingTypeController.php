<?php

namespace App\Controller;

use App\Entity\OutingType;
use App\Form\OutingTypeType;
use App\Repository\OutingTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OutingTypeController extends AbstractController
{
    /**
     * Shows a list of all OutingTypes
     * 
     * @param OutingTypeRepository $outingTypeRepository
     * 
     * @return Response 
     * 
     * @Route("/outing-types", name="outing_types_index")
     */
    public function index(OutingTypeRepository $outingTypeRepository): Response
    {
        $outingTypes = $outingTypeRepository->findAll();
        return $this->render('outing_type/index.html.twig', [
            'outingTypes' => $outingTypes,
        ]);
    }

    
    /**
     * Adds an OutingType to the database from the submitted form, if not submitted, shows the form
     * 
     * @param Request $request
     * 
     * @return Response 
     * 
     * @Route("/outing-types/add", name="outing_type_add")
     */
    public function add(Request $request){
        $outingType = new OutingType();
        $form = $this->createForm(OutingTypeType::class, $outingType);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($outingType);
            $manager->flush();
            $this->addFlash('success', 'Votre type de sortie a bien été ajouté.');
            return $this->redirectToRoute('outing_types_index');
        }

        return $this->render('outing_type/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
