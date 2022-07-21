<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserAuthSecurityController extends AbstractController
{
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getEnabled()) {
                return $this->redirectToRoute('profile');
            }else{
                return $this->redirectToRoute('app_logout');
            }
        }

        $loginError = $authenticationUtils->getLastAuthenticationError();
        $lastEnteredUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/user_login.html.twig', [
            'last_username' => $lastEnteredUsername,
            'error' => $loginError,
        ]);
    }

    public function logout(): void
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }

    public function profile(): Response
    {
        return $this->render('user/profile.html.twig');
    }
}
