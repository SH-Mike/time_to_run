<?php

namespace App\Controller;

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
}
