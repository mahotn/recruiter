<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    private $verifyEmailHelper;
    private $mailer;
    private $entityManager;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer, EntityManagerInterface $manager)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->entityManager = $manager;
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, UserInterface $user, TemplatedEmail $email): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $user->getId(),
            $user->getEmail(),
            ['id' => $user->getId()]
        );

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        $this->mailer->send($email);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, UserInterface $user): void
    {
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

        $user->setIsVerified(true);

        // On modifie le rôle de l'utilisateur.
        if($user->getValidatedAccount() && $user->isVerified()) {
            $roles = $user->getRoles();
            // On efface le rôle USER RESCTRICTED
            if(($key = array_search('ROLE_USER_RESTRICTED_ACCESS', $roles)) !== false) {
                unset($roles[$key]);
            }
            array_push($roles, 'ROLE_USER_VALIDATED');
            $user->setRoles($roles);
        } else if(!$user->getValidatedAccount() && $user->isVerified()) {
            $roles = $user->getRoles();
            array_push($roles, 'ROLE_USER_RESTRICTED_ACCESS');
            $user->setRoles($roles);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
