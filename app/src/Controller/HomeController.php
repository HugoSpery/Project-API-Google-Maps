<?php

namespace App\Controller;

use App\Entity\History;
use App\Repository\HistoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route('/history', name: 'app_history')]
    public function HistoryPage(): Response
    {
        return $this->render('history.html.twig');
    }

    #[Route('/add-history', name: 'app_add_history')]
    public function AddToHistory(EntityManagerInterface $em, HistoryRepository $historyRepository): JsonResponse
    {
        $data = json_decode(file_get_contents('php://input'));


        $x = $data->data->features[0]->geometry->coordinates[0];
        $y = $data->data->features[0]->geometry->coordinates[1];
        $history = $historyRepository->findOneBy([
            'city' => $data->data->features[0]->properties->city,
            'street' => $data->data->features[0]->properties->street,
            'postalCode' => $data->data->features[0]->properties->postcode,
            'coordinateX' => $x,
            'coordinateY' =>  $y
        ]);


        if($history===null){

            $history = new History();
            $history->setCity($data->data->features[0]->properties->city);
            $history->setStreet($data->data->features[0]->properties->street);
            $history->setPostalCode($data->data->features[0]->properties->postcode);

            $history->setCoordinateX($data->data->features[0]->geometry->coordinates[0]);

            $history->setCoordinateY($data->data->features[0]->geometry->coordinates[1]);

            $em->persist($history);
            $em->flush();
        }



        return new JsonResponse($data);
    }

    #[Route('/get-history', name: 'app_get_history')]
    public function GetHistory(HistoryRepository $historyRepository): JsonResponse
    {
        $history = $historyRepository->findAll();
        $data = [];
        foreach ($history as $h){
            $data[] = [
                'city' => $h->getCity(),
                'street' => $h->getStreet(),
                'postalCode' => $h->getPostalCode(),
                'coordinateX' => $h->getCoordinateX(),
                'coordinateY' => $h->getCoordinateY()
            ];
        }
        return new JsonResponse($data);
    }

    #[Route('/get-history-{position}',name: 'app_get_history_info')]
    public function GetInfoHistoryByIndex(int $position, HistoryRepository $historyRepository) : JsonResponse{
        $history = $historyRepository->findAll();
        $data[] = [
            'city' => $history[$position]->getCity(),
            'street' => $history[$position]->getStreet(),
            'postalCode' => $history[$position]->getPostalCode(),
            'coordinateX' => $history[$position]->getCoordinateX(),
            'coordinateY' => $history[$position]->getCoordinateY()
        ];
        return new JsonResponse($data);
    }
}
