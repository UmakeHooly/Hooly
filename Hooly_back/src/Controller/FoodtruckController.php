<?php

namespace App\Controller;

use App\Entity\Foodtruck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\FoodtruckRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FoodtruckController extends AbstractController
{
    /**
     * @Route("/foodtrucks", name="foodtrucks_index", methods={"GET"})
     */
    public function index(FoodtruckRepository $foodtruckRepository): Response
    {
        $foodtrucks=$foodtruckRepository->findAllFoodtrucks();
        $data = array_map(function($foodtruck){
            return [
                'id' => $foodtruck->getId(),
                'nom' => $foodtruck->getNom(),
            ];
        },$foodtrucks);
        
        return new JsonResponse($data);
    }
}
