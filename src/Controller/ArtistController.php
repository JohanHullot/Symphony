<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Event;
use App\Form\CreateArtistType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArtistController extends AbstractController
{
    #[Route('/artist', name: 'app_artists')]
    public function artists(EntityManagerInterface $entityManager): Response
    {
        return $this->render('artist/index.html.twig',
        ['artists' => $entityManager->getRepository(Artist::class)->findAll()]);
    }

    #[Route('/artist/{id}', name: 'app_artist', requirements: ['id' => '\d+'])]
    public function artist(int $id, EntityManagerInterface $entityManager): Response
    {
        $artist = $entityManager->getRepository(Artist::class)->find($id);
        if (!$artist)
        {
//            throw $this->createNotFoundException("L'artiste n'existe pas.");
            return $this->redirectToRoute('404');
        }
        else
        {
            return $this->render('artist/artist.html.twig',
                ['artist' => $artist ]);
        }
    }

    #[Route('/artist/create', name: 'app_artist_create')]
    public function createArtist(Request $request, EntityManagerInterface $entityManager): Response
    {
        $artist = new Artist();
        $form = $this->createForm(CreateArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($artist);
            $entityManager->flush();

            return $this->redirectToRoute('app_artist', ['id' => $artist->getId()]);
        }

        return $this->render('artist/createArtist.html.twig', [
            'artistForm' => $form,
        ]);
    }

    #[Route('/artist/edit/{id}', name: 'app_artist_edit', requirements: ['id' => '\d+'])]
    public function editArtist(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $currentArtist = $entityManager->getRepository(Artist::class)->find($id);

        if (!$currentArtist) {
            return $this->redirectToRoute('404');
        }

        $form = $this->createForm(CreateArtistType::class, $currentArtist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_artist', ['id' => $currentArtist->getId()]);
        }

        return $this->render('artist/editArtist.html.twig', [
            'artistForm' => $form,
            'currentArtist' => $currentArtist
        ]);

    }

    #[Route('/artist/delete/{id}', name: 'app_artist_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function deleteArtist(int $id, EntityManagerInterface $entityManager): Response
    {
        $artist = $entityManager->getRepository(Artist::class)->find($id);

        if (!$artist) {
            return $this->redirectToRoute('404');
        }

        foreach ($artist->getEvents() as $event)
        {
            $entityManager->remove($event);
        }

        $artist->setImage("");

        $entityManager->remove($artist);
        $entityManager->flush();

        return $this->redirectToRoute('app_artists');
    }
}