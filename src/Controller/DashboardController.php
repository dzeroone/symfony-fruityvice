<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        EntityManagerInterface $entityManager
    ): Response
    {
        $currentUser = $this->getUser();
        if(!$currentUser) {
            return $this->redirect('/');
        }

        $qb = $entityManager->createQueryBuilder();
        $qb->select('f, n')->from('App\Entity\LikedFruit', 'lf')
            ->leftJoin('App\Entity\User', 'u', 'WITH', 'lf.user = u.id')
            ->leftJoin('App\Entity\Fruit', 'f', 'WITH', 'f.id = lf.fruit')
            ->leftJoin('f.nutrition', 'n')
            ->where('lf.user = :user')
            ->setParameter('user', $currentUser);

        $fruits = $qb->getQuery()->getArrayResult();

        $qb = $entityManager->createQueryBuilder();
        $qb->select('sum(n.carbohydrates + n.protein + n.fat + n.calories + n.sugar)');
        $qb->from('App\Entity\Nutrition', 'n');

        $sum = $qb->getQuery()->getSingleScalarResult();

        return $this->render('dashboard/index.html.twig', [
            'fruits' => $fruits,
            'totalNutrition' => $sum
        ]);
    }
}
