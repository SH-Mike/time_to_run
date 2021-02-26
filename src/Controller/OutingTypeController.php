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
        //We get the outing types list
        try{
            $outingTypes = $outingTypeRepository->findAll();
        }
        catch(\Exception $e){
            $this->addFlash("danger", "Une erreur est survenue lors de la récupération des types de sortie.");
            return $this->redirectToRoute("homepage");
        }

        //We return the response page
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
        //Test if the user is connected
        if (!$this->getUser()) {
            $this->addFlash('danger', "Vous devez être connecté pour visiter cette page.");
            return $this->redirectToRoute('login');
        }

        //We create the OutingType object and the form
        $outingType = new OutingType();
        $form = $this->createForm(OutingTypeType::class, $outingType);

        //We handle the request and check if the form has been submitted
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //We try to add the outing type to the database and redirect the user depending on the result
            try{
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($outingType);
                $manager->flush();
            }
            catch(\Exception $e) {
                $this->addFlash("danger", "Une erreur est survenue lors de l'ajout de ce type de sortie.");
                return $this->redirectToRoute("outing_types_index");
            }
            $this->addFlash('success', 'Votre type de sortie a bien été ajouté.');
            return $this->redirectToRoute('outing_types_index');
        }

        //We return the response page
        return $this->render('outing_type/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edits an OutingType from the submitted form, if not submitted, shows the form
     * 
     * @param Request $request
     * @param OutingType $outingType
     * 
     * @return Response
     * 
     * @Route("/outing-types/edit/{outingType}", name="outing_type_edit")
     */
    public function editOuting(Request $request, OutingType $outingType = null): Response
    {
        //Test if the user is connected
        if (!$this->getUser()) {
            $this->addFlash('danger', "Vous devez être connecté pour visiter cette page.");
            return $this->redirectToRoute('login');
        }

        //Test if the outing type exists
        if (!$outingType) {
            $this->addFlash("danger", "Le type de sortie que vous souhaitez modifier n'existe pas.");
            return $this->redirectToRoute("outing_types_index");
        }

        //We create the form
        $form = $this->createForm(OutingTypeType::class, $outingType);

        //We handle the request and check if the form has been submitted
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //We try to edit the outing type in the database and redirect the user depending on the result
            try{
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($outingType);
                $manager->flush();
            }
            catch(\Exception $e) {
                $this->addFlash("danger", "Une erreur est survenue lors de la modification de ce type de sortie.");
                return $this->redirectToRoute("outing_types_index");
            }

            $this->addFlash("success", "Votre type sortie a bien été modifié.");
            return $this->redirectToRoute("outing_types_index");
        }

        //We return the response page
        return $this->render('outing_type/edit.html.twig', [
            'form' => $form->createView(),
            'outingType' => $outingType,
        ]);
    }

    /**
     * Deletes an OutingType given in parameters
     * 
     * @param Outing $outing
     * 
     * @return Response
     * 
     * @Route("/outing-types/delete/{outingType}", name="outing_type_delete")
     */
    public function deleteOuting(OutingType $outingType = null): Response
    {
        //Test if the user is connected
        if (!$this->getUser()) {
            $this->addFlash('danger', "Vous devez être connecté pour visiter cette page.");
            return $this->redirectToRoute('login');
        }

        //Test if the outing type exists
        if (!$outingType) {
            $this->addFlash("danger", "Le type de sortie que vous souhaitez supprimer n'existe pas.");
            return $this->redirectToRoute("outing_types_index");
        }

        //We try to delete the outing type from the database and redirect the user depending on the result
        try{
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($outingType);
            $manager->flush();
        } 
        catch(\Exception $e) {
            $this->addFlash("danger", "Une erreur est survenue lors de la suppression de ce type de sortie.");
            return $this->redirectToRoute("outing_types_index");
        }

        //No response page here, we redirect the user to the outing types index page
        $this->addFlash("success", "Le type de sortie a bien été supprimée.");
        return $this->redirectToRoute("outing_types_index");
    }
}
