<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Foodtruck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ReservationRepository;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation/add", name="reservation_add", methods={"POST"})
     */
    public function add(LoggerInterface $logger, ReservationRepository $reservationRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $data = $request->request->all();
        $tomany = false;
        if (array_key_exists('date', $data) && array_key_exists('foodtruck', $data)) {
            $date = $data['date'];
            $foodtruck = $data['foodtruck'];
            $today = new DateTime();
            if (!$date instanceof \DateTime) {
                try {
                    $date = new \DateTime($date);
                } catch (\Exception $e) {
                    throw new \InvalidArgumentException("La date fournie est invalide.");
                }
            }
            $setInterval=$today->diff($date);
            if ($setInterval->invert==0) {
                $reservations = $reservationRepository->findAllReservationByWeek($date);
                $reservationsToday=self::reservationsToday($reservations,$date);
                
                if ($date->format('D') === 'Fri') {
                    if (count($reservationsToday) > 5) {
                        $tomany = true;
                    }
                } else if (count($reservationsToday) > 6) {
                    $tomany = true;
                }

                if ($tomany) {
                    return new JsonResponse("Il n'y a plus de place à cette date", 301);
                } else {
                    $jour=self::findFoodtruckReservation($foodtruck,$reservations);
                    if ($jour != null) {
                        return new JsonResponse("Le foodtruck est déjà enregistré cette semaine ($jour)", 302);
                    } else {
                        $reservation = new Reservation();
                        $reservation->setDate($date);
                        $foodtruck = $entityManager->getRepository(Foodtruck::class)->find($foodtruck);
                        $reservation->setFoodtruck($foodtruck);
                        $entityManager->persist($reservation);
                        $entityManager->flush();
                        return new JsonResponse("Réservation enregistrée", 200);
                    }
                }


                return new JsonResponse($data);
            } else {
                return new JsonResponse("La date de réservation doit être au minimum pour demain", 300);
            }
        } else {
        }
    }

    public static function reservationsToday($reservations,$date)
    {
        $reservationsToday = array();
        foreach ($reservations as $key => $reservation) {
            if ($reservation->getDate() == $date) {
                array_push($reservationsToday, $reservation);
            }
        }
        return $reservationsToday;
    }

    public static function findFoodtruckReservation($foodtruck,$reservations){
        $jour=null;
        foreach ($reservations as $key => $reservation) {
            if ($foodtruck == $reservation->getFoodtruck()->getId()) {
                $jour = $reservation->getdate()->format('l');
            }
        }
        return $jour;
    }
}
