<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class SecurityController extends AbstractController {

    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $helper): Response {
        return $this->render('security/login.html.twig', [
                    // last username entered by the user (if any)
                    'last_username' => $helper->getLastUsername(),
                    // last authentication error (if any)
                    'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/confirm/{token}", name="security_confirm")
     */
    public function confirm(
            
    string $token, UserRepository $userRepository, EntityManagerInterface $entityManager
    ) {
        $user = $userRepository->findOneBy(
                [
                    'confirmationToken' => $token,
                ]
        );

        if (null !== $user) {
            $user->setEnabled(true);
            $user->setConfirmationToken('');
            $entityManager->flush();
        }

        return new Response(
                $this->twig->render(
                        'security/confirmation.html.twig', [
                    'user' => $user,
                        ]
                )
        );
    }

}
