<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class GoogleAuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(private EntityManagerInterface $entityManager){}

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {
        // TODO: Implement onAuthenticationSuccess() method.
        $user = $token->getUser();

        if (!$user instanceof \App\Entity\User) {
            return new RedirectResponse('/');
        }

        // Données Google stockées dans la session par l'authenticator
        $googleId = $request->getSession()->get('google_id');

        if ($googleId && !$user->getGoogleId()){
            $user->setGoogleId($googleId);
            $this->entityManager->flush();
            $request->getSession()->remove('google_id');
        }

        return new RedirectResponse('/');
    }
}
