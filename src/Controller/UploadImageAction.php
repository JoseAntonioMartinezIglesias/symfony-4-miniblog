<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/user")
 * @IsGranted("ROLE_USER")
 */
class UploadImageAction {

    /**
     * @Route("/edit/{id}", methods={"GET", "POST"}, name="image_edit")
     */
    public function edit(Request $request, Image $image): Response {

        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'user.updated_successfully');
            return $this->redirectToRoute('image_edit', ['id' => $image->getId()]);
        }

        return $this->render('user/edit.html.twig', [
                    'image' => $image,
                    'form' => $form->createView(),
        ]);
    }

}
