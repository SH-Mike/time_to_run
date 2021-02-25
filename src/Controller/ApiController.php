<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Outing;
use App\Repository\OutingRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    /**
     * Api function to get all the Outings
     * 
     * @param OutingRepository $outingRepository
     * @return Response
     * 
     * @Route("/api/outings/list", name="api_outings_list", methods={"GET"})
     */
    public function index(OutingRepository $outingRepository): Response
    {
        // Getting all the Outings
        $outings = $outingRepository->findAll();

        if (!$outings) {
            return new Response('Aucune sortie n\'a été trouvée.', 404);
        }

        // We use JSON Encoder
        $encoders = [new JsonEncoder()];

        // We use a normalizer to convert collection into array
        $normalizers = [new ObjectNormalizer()];

        // We instantiate the converter
        $serializer = new Serializer($normalizers, $encoders);

        // We convert into JSON and instantiate the Response
        $jsonOutings = $serializer->serialize($outings, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        $response = new Response($jsonOutings);

        // We specify HTTP header request
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Api function to get all the Outings
     * 
     * @param OutingRepository $outingRepository
     * @return Response
     * 
     * @Route("/api/outings/list/{user}", name="api_outings_user_list", methods={"GET"})
     */
    public function getUserOutings(OutingRepository $outingRepository, User $user = null): Response
    {
        // We instantiate the error code
        $error = 200;

        if (!$user) {
            $error = 404;
            return new Response('Cet utilisateur n\'existe pas.', $error);
        }

        // Getting all the Outings
        $outings = $outingRepository->findBy(['user' => $user]);

        if (!$outings) {
            $error = 404;
            return new Response('Cet utilisateur n\'a effectué aucune sortie.', $error);
        }
        // We use JSON Encoder
        $encoders = [new JsonEncoder()];

        // We use a normalizer to convert collection into array
        $normalizers = [new ObjectNormalizer()];

        // We instantiate the converter
        $serializer = new Serializer($normalizers, $encoders);

        // We convert into JSON and instantiate the Response
        $jsonOutings = $serializer->serialize($outings, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        $response = new Response($jsonOutings);

        // We specify HTTP header request
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Api function to get one Outing
     * 
     * @param OutingRepository $outingRepository
     * @param Outing $outing
     * @return Response
     * 
     * @Route("/api/outing/{outing}", name="api_outing", methods={"GET"})
     */
    public function getOuting(OutingRepository $outingRepository, Outing $outing = null): Response
    {
        // We instantiate the error code
        $error = 200;

        if (!$outing) {
            $error = 404;
            return new Response('Cette sortie n\'existe pas.', $error);
        }
        
        // We use JSON Encoder
        $encoders = [new JsonEncoder()];

        // We use a normalizer to convert collection into array
        $normalizers = [new ObjectNormalizer()];

        // We instantiate the converter
        $serializer = new Serializer($normalizers, $encoders);

        // We convert into JSON and instantiate the Response
        $jsonOutings = $serializer->serialize($outing, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        $response = new Response($jsonOutings);

        // We specify HTTP header request
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
