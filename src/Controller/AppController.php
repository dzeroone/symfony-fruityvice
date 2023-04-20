<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\{Fruit, User};

class AppController extends AbstractController
{
    #[Route('/')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $currentUser = $this->getUser();

        $currentPage = (int) $request->query->get('page', 1);
        $search = strtolower($request->query->get('search', ''));

        $fruitRepo = $entityManager->getRepository(Fruit::class);

        list($total, $fruits) = $fruitRepo->findFruits($currentPage, $search, $currentUser);

        $isSignedIn = $currentUser ? true : false;

        return $this->render('index.html.twig', compact('fruits', 'total', 'currentPage', 'isSignedIn'));
    }

    #[Route('/api-check-email')]
    public function apiCheckEmail(
        Request $request,
        EntityManagerInterface $entityManager
        ) {
        $requestData = $request->toArray();
        $userRepo = $entityManager->getRepository(User::class);
        $exists = $userRepo->findOneBy(['email' => $requestData['email']]);

        return $this->json(['exists' => !!$exists]);
    }
}
