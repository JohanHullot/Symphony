<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        return $this->render('default/index.html.twig', [
        ]);
    }
}
