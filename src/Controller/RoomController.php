<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/room")
 */
class RoomController extends AbstractController
{
    /**
     * @Route("/", name="room_index", methods={"GET"})
     */
    public function index(RoomRepository $roomRepository, UserInterface $user): Response
    {
        if($this->isGranted('ROLE_ADMIN')) {
            return $this->render('room/index.html.twig', [
                'rooms' => $roomRepository->findAll(),
            ]);
        } else {
            return $this->render('room/index.html.twig', [
                'rooms' => $roomRepository->findByNotBooked(),
            ]);
        }
    }

    /**
     * @Route("/booked", name="room_booked", methods={"GET"})
     */
    public function booked(RoomRepository $roomRepository): Response
    {
        return $this->render('room/booked.html.twig', [
            'rooms' => $roomRepository->findByBooked(),
        ]);

    }

    /**
     * @Route("/new", name="room_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserInterface $user): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $room->setCreationUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($room);
            $entityManager->flush();

            return $this->redirectToRoute('room_index');
        }

        return $this->render('room/new.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="room_show", methods={"GET"})
     */
    public function show(Room $room): Response
    {
        return $this->render('room/show.html.twig', [
            'room' => $room,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="room_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Room $room): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('room_index');
        }

        return $this->render('room/edit.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="room_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Room $room): Response
    {
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($room);
            $entityManager->flush();
        }

        return $this->redirectToRoute('room_index');
    }

    /**
     * @Route("/book/{id}", name="room_book", methods={"BOOK"})
     */
    public function book(Request $request, Room $room)
    {
        if ($this->isCsrfTokenValid('book'.$room->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $room->setIsBooked(true);
            $entityManager->persist($room);
            $entityManager->flush();
        }

        return $this->redirectToRoute('room_index');
    }

    /**
     * @Route("/book/{id}", name="room_unbook", methods={"UNBOOK"})
     */
    public function unBook(Request $request, Room $room)
    {
        if ($this->isCsrfTokenValid('unbook'.$room->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $room->setIsBooked(false);
            $entityManager->persist($room);
            $entityManager->flush();
        }

        return $this->redirectToRoute('room_index');
    }


}
