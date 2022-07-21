<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Message\MailNotification;
use App\Repository\UserRepository;
use App\Service\CodeGenerator;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly CodeGenerator          $codeGenerator,
        private readonly EntityManagerInterface $em
    )
    {
    }

    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('profile');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData())
            );
            $user->setEmail($form->get('email')->getData());
            $user->setRoles(['ROLE_USER']);
            $user->setConfirmationCode($this->codeGenerator->getConfirmationCode());

            $this->em->persist($user);
            $this->em->flush();

            $this->bus->dispatch((new MailNotification())
                ->setId($user->getId())
                ->setEmail($user->getEmail())
                ->setConfirmationCode($user->getConfirmationCode())
                ->setType('client.registration')
            );
//            $this->mailer->registerProcessingEmail($user->getEmail(), $user->getConfirmationCode());

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    public function confirmEmail(UserRepository $userRepository, string $code): Response
    {
        $user = $userRepository->findOneBy(['confirmationCode' => $code]);

        if (!$user) {
            throw $this->createNotFoundException();
        }

        $user->setEnable(true);
        $user->setConfirmationCode(null);
        $this->em->flush();

        return $this->render('security/account_confirm.html.twig', [
            'user' => $user,
        ]);
    }
}
