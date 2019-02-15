<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Event\UserRegisterEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Security\PasswordGenerator;
use App\Security\TokenGenerator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @Route("/user")
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController {

    /**
     * @Route("/", defaults={"page": "1"}, methods={"GET"}, name="user_index")
     * @Route("/page/{page<[1-9]\d*>}", methods={"GET"}, name="user_index_paginated")
     */
    public function index(Request $request, int $page, UserRepository $users): Response {
        $users = $users->findLatest($page);
        return $this->render('user/index.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/add", methods={"GET", "POST"}, name="user_add")
     */
    public function add(
    EventDispatcherInterface $eventDispatcher,
    TokenGenerator $tokenGenerator, PasswordGenerator $password, UserPasswordEncoderInterface $passwordEncoder, Request $request): Response {

        $user = new User();

        $form = $this->createForm(UserType::class, $user)
                ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword(
                    $user, $password->getRandomPassword(5, 2)
            );

            $user->setPassword($password);
            $user->setConfirmationToken($tokenGenerator->getRandomSecureToken(30));
            $user->setRoles(['ROLE_ADMIN']);
            $user->setEnabled(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $userRegisterEvent = new UserRegisterEvent($user);
            $eventDispatcher->dispatch(
                    UserRegisterEvent::NAME, $userRegisterEvent
            );

            $this->addFlash('success', 'user.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('user_add');
            }

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/add.html.twig', [
                    'post' => $user,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", methods={"GET", "POST"}, name="user_edit")
     */
    public function edit(Request $request, User $user): Response {

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'user.updated_successfully');
            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
                    'user' => $user,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", methods = {"POST"}, name = "user_delete")
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, User $user): Response {

        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('user_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'post.deleted_successfully');
        return $this->redirectToRoute('user_index');
    }

}
