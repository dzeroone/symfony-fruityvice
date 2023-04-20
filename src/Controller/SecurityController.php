<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/signin', name: 'app_login')]
    public function signin(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_app_index');
        }
        return $this->render('signin.html.twig');
    }

    #[Route('/signup')]
    public function signup() {
        return $this->render('signup.html.twig');
    }

    #[Route(path: '/signout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/api-signup')]
    public function apiSignup(
        Request $request,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        AppAuthenticator $authenticator,
        Security $security
        ) {
        $user = new User();

        $requestData = $request->toArray();
        $user->setEmail($requestData['email']);
        $user->setPassword($requestData['password']);

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
    
            $this->json([
                'success' => false,
                'errors' => $errors
            ]);
        }

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );
        $user->setPassword($hashedPassword);
        
        $entityManager->persist($user);
        $entityManager->flush();

        $security->login($user, AppAuthenticator::class, 'main');

        return $this->json(['success' => true]);
    }

    #[Route('/api-signin')]
    public function apiSignin(
        Request $request,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        AppAuthenticator $authenticator,
        Security $security
        ) {
        $user = new User();

        $requestData = $request->toArray();
        $userRepo = $entityManager->getRepository(User::class);
        $user = $userRepo->findOneBy(['email' => $requestData['email']]);

        if(!$user) {
            return $this->json([
                'success' => false,
                'message' => 'Email/Password not matched'
            ], Response::HTTP_BAD_REQUEST);
        }
        
        if(!$passwordHasher->isPasswordValid($user, $requestData['password'])) {
            return $this->json([
                'success' => false,
                'message' => 'Email/Password not matched'
            ], Response::HTTP_BAD_REQUEST);
        }

        $security->login($user, AppAuthenticator::class, 'main');

        return $this->json(['success' => true]);
    }
}
