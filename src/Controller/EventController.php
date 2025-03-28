<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\CreateEventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EventController extends AbstractController
{

    #[Route('/event', name: 'app_events')]
    public function events(EntityManagerInterface $entityManager): Response
    {
        return $this->render('event/index.html.twig',
        ['events' => $entityManager->getRepository(Event::class)->findAll()]);
    }

    #[Route('/event/{id}', name: 'app_event', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function event(int $id, EntityManagerInterface $entityManager): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($id);

        if (!$event)
        {
            //            throw $this->createNotFoundException("L'event n'existe pas.");
            return $this->redirectToRoute('404');
        }
        else
        {
            return $this->render('event/event.html.twig',
                ['event' => $event ]);
        }
    }

    #[Route('/event/subscribe/{id}', name: 'app_event_subscribe', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function subscribeEvent(int $id, EntityManagerInterface $entityManager): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($id);

        $event->addUser($this->getUser());

        $entityManager->persist($event);
        $entityManager->flush();

        return $this->redirectToRoute('app_event', ['id' => $event->getId()]);
    }

    #[Route('/event/unsubscribe/{id}', name: 'app_event_unsubscribe', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function unsubscribeEvent(int $id, EntityManagerInterface $entityManager): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($id);

        $event->removeUser($this->getUser());

        $entityManager->persist($event);
        $entityManager->flush();

        return $this->redirectToRoute('app_event', ['id' => $event->getId()]);
    }

    #[Route('/event/create', name: 'app_event_create')]
    public function createEvent(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(CreateEventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event->addUser($this->getUser());

            $event->setCreator($this->getUser());

            $entityManager->persist($event);
            $entityManager->flush();


            return $this->redirectToRoute('app_event', ['id' => $event->getId()]);
        }

        return $this->render('event/createEvent.html.twig', [
            'eventForm' => $form,
        ]);
    }

    #[Route('/event/edit/{id}', name: 'app_event_edit', requirements: ['id' => '\d+'])]
    public function editEvent(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $currentEvent = $entityManager->getRepository(Event::class)->find($id);

        if (!$currentEvent)
        {
            return $this->redirectToRoute('404');
        }

        if ($this->getUser()->getId() !== $currentEvent->getCreator()->getId())
        {
            return $this->redirectToRoute('app_event', ['id' => $currentEvent->getId()]);
        }

        $form = $this->createForm(CreateEventType::class, $currentEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

//            $currentEvent->setName($event->getName());
//            $currentEvent->setArtist($event->getArtist());
//            $currentEvent->setDate($event->getDate());

            $entityManager->flush();

            return $this->redirectToRoute('app_event', ['id' => $currentEvent->getId()]);
        }

        return $this->render('event/editEvent.html.twig', [
            'eventForm' => $form,
            'currentEvent' => $currentEvent
        ]);
    }

    #[Route('/event/delete/{id}', name: 'app_event_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function deleteEvent(int $id, EntityManagerInterface $entityManager): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($id);

        if (!$event)
        {
            return $this->redirectToRoute('404');
        }

        $entityManager->remove($event);
        $entityManager->flush();

        return $this->redirectToRoute('app_events');
    }
}
