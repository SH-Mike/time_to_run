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
        //Test if the user is connected
        if (!$this->getUser()) {
            $this->addFlash('danger', "Vous devez être connecté pour visiter cette page.");
            return $this->redirectToRoute('login');
        }

        //We get the outings list
        try {
            $outings = $outingRepository->findByUser($this->getUser());
        } catch (\Exception $e) {
            $this->addFlash("danger", "Une erreur est survenue lors de la récupération de vos sorties.");
            return $this->redirectToRoute("homepage");
        }

        //We return the response page
        return $this->render('outing/index.html.twig', [
            'outings' => $outings,
        ]);
    }

    /**
     * Adds an Outing to the database from the submitted form, if not submitted, shows the form
     * 
     * @param Request $request
     * 
     * @return Response
     * 
     * @Route("/outings/add", name="outing_add")
     */
    public function addOuting(Request $request): Response
    {
        //Test if the user is connected
        if (!$this->getUser()) {
            $this->addFlash('danger', "Vous devez être connecté pour visiter cette page.");
            return $this->redirectToRoute('login');
        }

        //We create the Outing object and the form
        $outing = new Outing();
        $form = $this->createForm(OutingTypeForm::class, $outing);

        //We handle the request and check if the form has been submitted
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Checking the integrity of Outing data
            // Distance has to be numeric and greater than 0
            if (!is_numeric($outing->getDistance()) || $outing->getDistance() < 0) {
                $this->addFlash('danger', "Veuillez entrer une distance valide.");
                return $this->redirectToRoute('outing_add');
            }

            // Start date has to be before end date
            if ($outing->getStartDate() > $outing->getEndDate()) {
                $this->addFlash('danger', "A moins de courir plus vite que la vitesse de la lumière ou de maîtriser le voyage temporel, il est impossible de terminer avant d'avoir commencé.");
                return $this->redirectToRoute('outing_add');
            }

            // End date has to be before end date
            // This also is the case for start date
            if ($outing->getEndDate() > new \DateTime('now')) {
                $this->addFlash('danger', "A moins de maîtriser le voyage temporel, il est impossible de terminer après la date actuelle.");
                return $this->redirectToRoute('outing_add');
            }

            //We try to add the outing to the database and redirect the user depending on the result
            try {
                // We set the current connected user to this outing
                $outing->setUser($this->getUser());
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($outing);
                $manager->flush();
            } catch (\Exception $e) {
                $this->addFlash('danger', "Une erreur est survenue lors de l'ajout de cette sortie.");
                return $this->redirectToRoute('outings_index');
            }
            $this->addFlash('success', 'Votre sortie a bien été ajoutée.');
            return $this->redirectToRoute('outings_index');
        }

        //We return the response page
        return $this->render('outing/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edits an Outing from the submitted form, if not submitted, shows the form
     * 
     * @param Request $request
     * @param Outing $outing
     * 
     * @return Response
     * 
     * @Route("/outings/edit/{outing}", name="outing_edit")
     */
    public function editOuting(Request $request, Outing $outing = null): Response
    {
        //Test if the user is connected
        if (!$this->getUser()) {
            $this->addFlash('danger', "Vous devez être connecté pour visiter cette page.");
            return $this->redirectToRoute('login');
        }

        //Test if the outing exists
        if (!$outing) {
            $this->addFlash("danger", "La sortie que vous souhaitez modifier n'existe pas.");
            return $this->redirectToRoute("outings_index");
        }

        // Test if the outing belongs to the connected user
        if ($outing->getUser() != $this->getUser()) {
            $this->addFlash("danger", "Vous ne pouvez pas modifier une sortie qui ne vous appartient pas.");
            return $this->redirectToRoute("outings_index");
        }

        //We create the form
        $form = $this->createForm(OutingTypeForm::class, $outing);

        //We handle the request and check if the form has been submitted
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Distance has to be numeric and greater than 0
            if (!is_numeric($outing->getDistance()) || $outing->getDistance() < 0) {
                $this->addFlash('danger', "Veuillez entrer une distance valide.");
                return $this->redirectToRoute('outing_edit', ['outing' => $outing->getId()]);
            }

            // Start date has to be before end date
            if ($outing->getStartDate() > $outing->getEndDate()) {
                $this->addFlash('danger', "A moins de courir plus vite que la vitesse de la lumière ou de maîtriser le voyage temporel, il est impossible de terminer avant d'avoir commencé.");
                return $this->redirectToRoute('outing_edit', ['outing' => $outing->getId()]);
            }

            // End date has to be before end date
            // This also is the case for start date
            if ($outing->getEndDate() > new \DateTime('now')) {
                $this->addFlash('danger', "A moins de maîtriser le voyage temporel, il est impossible de terminer après la date actuelle.");
                return $this->redirectToRoute('outing_edit', ['outing' => $outing->getId()]);
            }

            //We try to edit the outing in the database and redirect the user depending on the result
            try {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($outing);
                $manager->flush();
            } catch (\Exception $e) {
                $this->addFlash("danger", "Une erreur est survenue lors de la modification de cette sortie.");
                return $this->redirectToRoute("outings_index");
            }

            $this->addFlash("success", "Votre sortie a bien été modifiée.");
            $this->redirectToRoute("outings_index");
        }

        //We return the response page
        return $this->render('outing/edit.html.twig', [
            'form' => $form->createView(),
            'outing' => $outing,
        ]);
    }

    /**
     * Deletes an Outing given in parameters
     * 
     * @param Outing $outing
     * 
     * @return Response
     * 
     * @Route("/outings/delete/{outing}", name="outing_delete")
     */
    public function deleteOuting(Outing $outing = null): Response
    {
        //Test if the user is connected
        if (!$this->getUser()) {
            $this->addFlash('danger', "Vous devez être connecté pour visiter cette page.");
            return $this->redirectToRoute('login');
        }

        //Test if the outing type exists
        if (!$outing) {
            $this->addFlash("danger", "La sortie que vous souhaitez supprimer n'existe pas.");
            return $this->redirectToRoute("outings_index");
        }

        // Test if the outing belongs to the connected user
        if ($outing->getUser() == $this->getUser()) {
            //We try to delete the outing from the database and redirect the user depending on the result
            try {
                $manager = $this->getDoctrine()->getManager();
                $manager->remove($outing);
                $manager->flush();
            } catch (\Exception $e) {
                $this->addFlash("danger", "Une erreur est survenue lors de la suppression de cette sortie.");
                return $this->redirectToRoute("outings_index");
            }

            $this->addFlash("success", "La sortie a bien été supprimée.");
            return $this->redirectToRoute("outings_index");
        } else {
            $this->addFlash("danger", "Vous ne pouvez pas supprimer une sortie qui ne vous appartient pas.");
            return $this->redirectToRoute("outings_index");
        }
    }
}
