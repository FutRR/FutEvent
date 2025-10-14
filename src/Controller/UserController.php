<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class UserController extends AbstractController
{
    #[Route('/users', name: 'user_list', methods: ['GET'])]
    #[isGranted('ROLE_ADMIN')]
    public function list(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/{username}', name: 'user_profile', methods: ['GET'])]
    #[isGranted('ROLE_USER')]
    public function profile(Request $request, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['username' => $request->get('username')]);

        if($this->getUser() === $user) {

            return $this->render('user/show.html.twig', [
                'user' => $user,
            ]);
        }
        flash()->error('You are not allowed to access this page');
        return $this->redirectToRoute('default_home');

    }
}
