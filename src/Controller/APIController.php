<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;

final class APIController extends AbstractController
{
    #[Route('/api/artist', name: 'app_api_artists', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the artists list',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Artist::class))
        )
    )]
    public function apiArtists(EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $data = $entityManager->getRepository(Artist::class)->findAll();
        
        $context = circularCounter();

        $jsonContent = $serializer->serialize($data, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);
    }

    #[Route('/api/artist/{id}', name: 'app_api_artist', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the artist of id',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Artist::class))
        )
    )]
    public function apiArtist(int $id, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $data = $entityManager->getRepository(Artist::class)->find($id);

        $context = circularCounter();

        $jsonContent = $serializer->serialize($data, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);
    }

    #[Route('/api/event', name: 'app_api_events', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the events list',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Event::class))
        )
    )]
    public function apiEvents(EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $data = $entityManager->getRepository(Event::class)->findAll();

        $context = circularCounter();

        $jsonContent = $serializer->serialize($data, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);
    }

    #[Route('/api/event/{id}', name: 'app_api_event', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the event of id',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Event::class))
        )
    )]
    public function apiEvent(int $id, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $data = $entityManager->getRepository(Event::class)->find($id);

        $context = circularCounter();

        $jsonContent = $serializer->serialize($data, 'json', $context);

        return JsonResponse::fromJsonString($jsonContent);
    }
}

function circularCounter()
{
    return [
        AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (object $object, ?string $format, array $context): string {
            if (!$object instanceof Event) {
                if (!$object instanceof Artist) {
                    if (!$object instanceof User) {
                        throw new CircularReferenceException('A circular reference has been detected when serializing the object of class "' . get_debug_type($object) . '".');
                    }
                }
            }
            // serialize the nested Organization with only the name (and not the members)
            return $object->getId();
        },
    ];
}
