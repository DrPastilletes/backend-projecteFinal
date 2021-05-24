<?php

namespace App\Controller;

use App\Entity\Taula;
use App\Form\TaulaType;
use App\Repository\TaulaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/taula")
 */
class TaulaController extends AbstractController
{
    /**
     * @Route("/", name="taula_index", methods={"GET"})
     */
    public function index(TaulaRepository $taulaRepository, SessionInterface $session): Response
    {
        if ($session->get('barLogged') ==null){
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        return $this->render('taula/index.html.twig', [
            'taulas' => $taulaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="taula_new", methods={"GET","POST"})
     */
    public function new(Request $request, SessionInterface $session): Response
    {
        if ($session->get('barLogged') ==null){
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        $taula = new Taula();
        $form = $this->createForm(TaulaType::class, $taula);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($taula);
            $entityManager->flush();

            return $this->redirectToRoute('taula_index');
        }

        return $this->render('taula/new.html.twig', [
            'taula' => $taula,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="taula_show", methods={"GET"})
     */
    public function show(Taula $taula, SessionInterface $session): Response
    {
        if ($session->get('barLogged') ==null){
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        return $this->render('taula/show.html.twig', [
            'taula' => $taula,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="taula_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Taula $taula, SessionInterface $session): Response
    {
        if ($session->get('barLogged') ==null){
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        $form = $this->createForm(TaulaType::class, $taula);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('taula_index');
        }

        return $this->render('taula/edit.html.twig', [
            'taula' => $taula,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="taula_delete", methods={"POST"})
     */
    public function delete(Request $request, Taula $taula, SessionInterface $session): Response
    {
        if ($session->get('barLogged') ==null){
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        if ($this->isCsrfTokenValid('delete'.$taula->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($taula);
            $entityManager->flush();
        }

        return $this->redirectToRoute('taula_index');
    }
}
