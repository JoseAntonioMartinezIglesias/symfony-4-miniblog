<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function add(Request $request): Response {

        $user = new User();

        $form = $this->createForm(UserType::class, $user)
                ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           // $user->setSlug(Slugger::slugify($user->getTitle()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

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
     * @Route("/edit", methods={"GET", "POST"}, name="user_edit")
     */
    public function edit(Request $request): Response {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'user.updated_successfully');

            return $this->redirectToRoute('user_edit');
        }

        return $this->render('user/edit.html.twig', [
                    'user' => $user,
                    'form' => $form->createView(),
        ]);
    }

}
