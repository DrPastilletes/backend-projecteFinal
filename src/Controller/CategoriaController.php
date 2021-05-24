<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Form\CategoriaType;
use App\Repository\BarRepository;
use App\Repository\CategoriaRepository;
use App\Repository\ProducteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categoria")
 */
class CategoriaController extends AbstractController
{
    /**
     * @Route("/", name="categoria_index", methods={"GET"})
     */
    public function index(BarRepository $barRepository, CategoriaRepository $categoriaRepository, SessionInterface $session): Response
    {
        $bar = $barRepository->findOneBy(['id' => $session->get('barLogged')->getId()]);
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        return $this->render('categoria/index.html.twig', [
            'categorias' => $categoriaRepository->findBy(
                ["bar" => $bar]
            ),
        ]);
    }

    /**
     * @Route("/categories", name="categoria_categories", methods={"POST"})
     */
    public function categoriesApi(CategoriaRepository $categoriaRepository, BarRepository $barRepository, Request $request): JsonResponse
    {
        $array = $request->toArray();
        $bar = $barRepository->findOneBy(['id' => $array['bar']]);
        $categories = $categoriaRepository->findBy([
            'bar' => $bar
        ]);

        $data = [];

        foreach ($categories as $cat) {
            $data[] = [
                'id' => $cat->getId(),
                'nom' => $cat->getNom(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/new", name="categoria_new", methods={"GET","POST"})
     */
    public function new(Request $request, SessionInterface $session, BarRepository $barRepository): Response
    {
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $categorium = new Categoria();
        $bar = $session->get('barLogged');
        $barNou = $barRepository->findOneBy(['id' => $bar->getId()]);

        $categorium->setBar($barNou);
        $form = $this->createForm(CategoriaType::class, $categorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$entityManager->persist($bar);
            $entityManager->persist($categorium);
            $entityManager->flush();

            return $this->redirectToRoute('categoria_index');
        }

        return $this->render('categoria/new.html.twig', [
            'categorium' => $categorium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categoria_show", methods={"GET"})
     */
    public function show(Categoria $categorium, SessionInterface $session): Response
    {
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        return $this->render('categoria/show.html.twig', [
            'categorium' => $categorium,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categoria_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Categoria $categorium, SessionInterface $session): Response
    {
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        $form = $this->createForm(CategoriaType::class, $categorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categoria_index');
        }

        return $this->render('categoria/edit.html.twig', [
            'categorium' => $categorium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categoria_delete", methods={"POST"})
     */
    public function delete(Request $request, Categoria $categorium, SessionInterface $session): Response
    {
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        if ($this->isCsrfTokenValid('delete' . $categorium->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categorium);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categoria_index');
    }
}
