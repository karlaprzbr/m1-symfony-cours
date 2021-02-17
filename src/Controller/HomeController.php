<?php

namespace App\Controller;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/export", name="export")
     * @return Response
     */
    public function export(RoomRepository $repository)
    {
        $fp = fopen('php://temp', 'w');
        fputcsv($fp, array('id', 'name', 'capacity', 'category'));
        $rooms = $repository->findAll();
        foreach ($rooms as $room) {
            fputcsv($fp, array($room->getId(), $room->getName(), $room->getCapacity(), $room->getCategory()->getName()));
        }
        rewind($fp);
        $response = new Response(stream_get_contents($fp));
        fclose($fp);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="testing.csv"');
        return $response;
    }
}
