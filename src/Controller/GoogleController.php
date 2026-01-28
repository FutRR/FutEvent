<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class GoogleController extends AbstractController
{
    #[Route('/connect/google', name: 'connect_google')]
    public function connectAction(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect();
    }

    #[Route('/connect/google/check', name: 'connect_google_check')]
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher): RedirectResponse
    {
        try {
            $client = $clientRegistry->getClient('google');
            $googleUser = $client->fetchUser();

            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $googleUser->getEmail()]);

            if (!$existingUser) {

                $user = new User();
                $user->setEmail($googleUser->getEmail());
                $user->setRoles(['ROLE_USER']);
                $user->setIsGoogleUser(true);
                $user->setUsername(explode("@", $googleUser->getEmail())[0]);

                $entityManager->persist($user);
                $entityManager->flush();
            } else {
                $user = $existingUser;
            }

            $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
            // $container->get('security.token_storage')->setToken($token);

            $event = new InteractiveLoginEvent($request, $token);
            $eventDispatcher->dispatch($event);

            // if ($container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $_SESSION['user'] = $existingUser;
            return $this->redirectToRoute('default_home');
            // } else {
            //     throw new \Exception('Échec de l'authentification après la connexion Google');
            // }

        } catch (\Exception $e) {
            return $this->redirectToRoute('app_login', ['error' => $e->getMessage()]);
        }
    }
}
