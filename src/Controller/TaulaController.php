<?php

namespace App\Controller;

use App\Entity\Taula;
use App\Form\TaulaType;
use App\Repository\BarRepository;
use App\Repository\TaulaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function index(TaulaRepository $taulaRepository, SessionInterface $session, BarRepository $barRepository): Response
    {

        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        $bar = $barRepository->findOneBy(['id' => $session->get('barLogged')->getId()]);
        $taules = $taulaRepository->findBy([
            'bar' => $bar
        ]);
        return $this->render('taula/index.html.twig', [
            'taulas' => $taules,
        ]);
    }

    /**
     * @Route("/taules", name="taula_taulesApi", methods={"POST"})
     */
    public function taulesApi(TaulaRepository $taulaRepository, BarRepository $barRepository, Request $request): Response
    {
        $array = $request->toArray();
        $bar = $barRepository->findOneBy(['id' => $array['bar']]);
        $taules = $taulaRepository->findBy([
            'bar' => $bar
        ]);
        $data = [];

        foreach ($taules as $taula) {
            $data[] = [
                'id' => $taula->getId(),
                'nom' => $taula->getIdentificador(),
                'ocupada' => $taula->getOcupada()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/new", name="taula_new", methods={"GET","POST"})
     */
    public function new(Request $request, SessionInterface $session, BarRepository $barRepository): Response
    {
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        $taula = new Taula();
        $bar = $barRepository->findOneBy(['id' => $session->get('barLogged')->getId()]);
        $taula->setBar($bar);
        $taula->setOcupada(0);
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
        if ($session->get('barLogged') == null) {
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
        if ($session->get('barLogged') == null) {
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
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        if ($this->isCsrfTokenValid('delete' . $taula->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($taula);
            $entityManager->flush();
        }

        return $this->redirectToRoute('taula_index');
    }
}
