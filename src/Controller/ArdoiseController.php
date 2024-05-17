<?php

namespace App\Controller;

use App\Entity\Ardoise;
use App\Form\ArdoiseType;
use App\Repository\ArdoiseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ardoise')]
class ArdoiseController extends AbstractController
{
    #[Route('/', name: 'app_ardoise_index', methods: ['GET'])]
    public function index(ArdoiseRepository $ardoiseRepository): Response
    {
        return $this->render('ardoise/index.html.twig', [
            'ardoises' => $ardoiseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ardoise_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ardoise = new Ardoise();
        $form = $this->createForm(ArdoiseType::class, $ardoise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ardoise);
            $entityManager->flush();

            return $this->redirectToRoute('app_ardoise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ardoise/new.html.twig', [
            'ardoise' => $ardoise,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ardoise_show', methods: ['GET'])]
    public function show(Ardoise $ardoise): Response
    {
        return $this->render('ardoise/show.html.twig', [
            'ardoise' => $ardoise,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ardoise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ardoise $ardoise, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArdoiseType::class, $ardoise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ardoise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ardoise/edit.html.twig', [
            'ardoise' => $ardoise,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ardoise_delete', methods: ['POST'])]
    public function delete(Request $request, Ardoise $ardoise, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ardoise->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($ardoise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ardoise_index', [], Response::HTTP_SEE_OTHER);
    }
}
