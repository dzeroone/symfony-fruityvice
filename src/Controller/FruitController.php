<?php

namespace App\Controller;

use App\Entity\Fruit;
use App\Entity\LikedFruit;
use App\Repository\LikedFruitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FruitController extends AbstractController
{
    #[Route('/fruits', name: 'app_fruit')]
    public function getFruits(EntityManagerInterface $entityManager, Request $request): Response
    {
        $currentPage = (int) $request->query->get('page', 1);
        $search = strtolower($request->query->get('search', ''));

        $fruitRepo = $entityManager->getRepository(Fruit::class);

        list($total, $fruits) = $fruitRepo->findFruits($currentPage, $search, $this->getUser());

        return $this->json(compact('total', 'fruits'));
    }

    #[Route('/fruits/{fruitId}/like', name: 'app_fruit_like')]
    public function likeFruit(int $fruitId, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        if(!$user) {
            return $this->json([
                'success' => false
            ], Response::HTTP_UNAUTHORIZED);
        }
        $likedFruitRepo = $entityManager->getRepository(LikedFruit::class);
        $alreadyLiked = $likedFruitRepo->findOneBy(['user' => $user->getId(), 'fruit' => $fruitId]);

        $returnData = [
            'success' => true
        ];

        if($alreadyLiked) {
            $entityManager->remove($alreadyLiked);
            $entityManager->flush();
        }else{
            $fruitRepo = $entityManager->getRepository(Fruit::class);
            $fruit = $fruitRepo->findOneBy(['id' => $fruitId]);

            $qb = $entityManager->createQueryBuilder();
            $qb->select('count(lf.id)');
            $qb->from('App\Entity\LikedFruit', 'lf');
            $qb->where('lf.user = :userId');
            $qb->setParameters(['userId' => $user->getId()]);

            $count = $qb->getQuery()->getSingleScalarResult();

            if($count >= 10) {
                return $this->json([
                    'success' => false,
                    'message' => 'You have reached 10 like limit!'
                ]);
            }

            $likedFruit = new LikedFruit();
            $likedFruit->setUser($user);
            $likedFruit->setFruit($fruit);
            $entityManager->persist($likedFruit);
            $entityManager->flush();
            $returnData['id'] = $likedFruit->getId();
        }

        return $this->json($returnData);
    }
}
