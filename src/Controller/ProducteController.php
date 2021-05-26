<?php

namespace App\Controller;

use App\Entity\Producte;
use App\Form\ProducteType;
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
 * @Route("/producte")
 */
class ProducteController extends AbstractController
{
    /**
     * @Route("/", name="producte_index", methods={"GET"})
     */
    public function index(ProducteRepository $producteRepository, SessionInterface $session): Response
    {
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        return $this->render('producte/index.html.twig', [
            'productes' => $producteRepository->findBy(['bar' => $session->get('barLogged')->getId()]),
        ]);
    }

    /**
     * @Route("/productesByCategoria", name="producte_productesByCategoria", methods={"POST"})
     */
    public function productesByCategoria(ProducteRepository $producteRepository, CategoriaRepository $categoriaRepository, BarRepository $barRepository, SessionInterface $session, Request $request): JsonResponse
    {
        $array = $request->toArray();
        $bar = $barRepository->findOneBy(['id' => $array['bar']]);
        $categoria = $categoriaRepository->findOneBy([
            'id' => $array['categoria'],
            'bar' => $bar
        ]);
        $productes = $producteRepository->findBy([
            'categoria' => $categoria,
            'bar' => $bar
        ]);
        $data = [];

        foreach ($productes as $producte) {
            $data[] = [
                'id' => $producte->getId(),
                'nom' => $producte->getNom(),
                'preu' => $producte->getPreu(),
                'disponible' => $producte->getDisponible()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/new", name="producte_new", methods={"GET","POST"})
     */
    public function new(Request $request, SessionInterface $session, BarRepository $barRepository): Response
    {
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        $producte = new Producte();
        $bar = $barRepository->findOneBy(['id' => $session->get('barLogged')->getId()]);
        $producte->setBar($bar);
        $producte->setDisponible(1);

        $form = $this->createForm(ProducteType::class, $producte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($producte);
            $entityManager->flush();

            return $this->redirectToRoute('producte_index');
        }

        return $this->render('producte/new.html.twig', [
            'producte' => $producte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/createView", name="producte_createView", methods={"GET"})
     */
    public function getCreateView(SessionInterface $session, CategoriaRepository $categoriaRepository, BarRepository $barRepository): Response
    {
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        $bar = $barRepository->findOneBy(['id' => $session->get('barLogged')->getId()]);
        $categories = $categoriaRepository->findBy([
            'bar' => $bar
        ]);
        return $this->render('producte/create.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/editView/{id}", name="producte_editView", methods={"GET","POST"})
     */
    public function getEditView(SessionInterface $session, CategoriaRepository $categoriaRepository, BarRepository $barRepository, Producte $producte): Response
    {
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        $bar = $barRepository->findOneBy(['id' => $session->get('barLogged')->getId()]);
        $categories = $categoriaRepository->findBy([
            'bar' => $bar
        ]);
        return $this->render('producte/editProd.html.twig', [
            'categories' => $categories,
            'producte' => $producte
        ]);
    }

    /**
     * @Route("/create", name="producte_create", methods={"GET","POST"})
     */
    public function create(Request $request, SessionInterface $session, BarRepository $barRepository, CategoriaRepository $categoriaRepository): Response
    {
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
        $producte = new Producte();
        $bar = $barRepository->findOneBy(['id' => $session->get('barLogged')->getId()]);
        $producte->setBar($bar);
        $producte->setDisponible(1);
        $producte->setNom($request->get("nom"));
        $producte->setPreu($request->get("preu"));
        $categoria = $categoriaRepository->findOneBy([
            'id' => $request->get("categoria")
        ]);
        $producte->setCategoria($categoria);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($producte);
        $entityManager->flush();

        return $this->redirectToRoute('producte_index');
    }

    /**
     * @Route("/{id}", name="producte_show", methods={"GET"})
     */
    public function show(Producte $producte, SessionInterface $session): Response
    {
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        return $this->render('producte/show.html.twig', [
            'producte' => $producte,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="producte_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Producte $producte, SessionInterface $session): Response
    {
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        $form = $this->createForm(ProducteType::class, $producte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('producte_index');
        }

        return $this->render('producte/edit.html.twig', [
            'producte' => $producte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/editProd", name="producte_editProd", methods={"POST"})
     */
    public function editProd(Request $request, SessionInterface $session, ProducteRepository $producteRepository, CategoriaRepository $categoriaRepository): Response
    {
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        $producte = $producteRepository->findOneBy([
            'id' => $request->get('id'),
        ]);
        $categoria = $categoriaRepository->findOneBy([
            'id' => $request->get("categoria")
        ]);
        $producte->setNom($request->get("nom"));
        $producte->setPreu($request->get("preu"));
        $producte->setCategoria($categoria);


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($producte);
        $entityManager->flush();

        return $this->redirectToRoute('producte_index');
    }

    /**
     * @Route("/{id}/modificarDisponible", name="producte_modificarDisponible", methods={"GET","POST"})
     */
    public function modificarDisponible(Request $request, Producte $producte, SessionInterface $session): Response
    {
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }

        if ($producte->getDisponible() == 1) {
            $producte->setDisponible(0);
        } else {
            $producte->setDisponible(1);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('producte_index');
    }

    /**
     * @Route("/{id}", name="producte_delete", methods={"POST"})
     */
    public function delete(Request $request, Producte $producte, SessionInterface $session): Response
    {
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        if ($this->isCsrfTokenValid('delete' . $producte->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($producte);
            $entityManager->flush();
        }

        return $this->redirectToRoute('producte_index');
    }
}
