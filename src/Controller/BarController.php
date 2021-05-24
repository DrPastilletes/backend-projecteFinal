<?php

namespace App\Controller;

use App\Entity\Bar;
use App\Entity\Categoria;
use App\Form\BarType;
use App\Repository\BarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/bar")
 */
class BarController extends AbstractController
{
    /**
     * @Route("/", name="bar_index", methods={"GET"})
     */
    public function index(BarRepository $barRepository, SessionInterface $session): Response
    {
        $barLogged = $session->get('barLogged');
        if ($barLogged == null || $barLogged->getNom() != "admin" ){
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        return $this->render('bar/index.html.twig', [
            'bars' => $barRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="bar_new", methods={"GET","POST"})
     */
    public function new(Request $request, SessionInterface $session): Response
    {
        $bar = new Bar();
        $form = $this->createForm(BarType::class, $bar);
        $form->handleRequest($request);
        $categories = ["Cafe","Entrepa Fred","Entrepa Calent","Cervesa","Aperitiu","Alcohol","Gelats","Postre"];

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bar);
            $entityManager->flush();
            foreach ($categories as $nom){
                $categoria = new Categoria();
                $categoria->setBar($bar);
                $categoria->setNom($nom);
                $entityManager->persist($categoria);
                $entityManager->flush();
            }

            return $this->redirectToRoute('bar_index');
        }

        return $this->render('bar/new.html.twig', [
            'bar' => $bar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bar_show", methods={"GET"})
     */
    public function show(Bar $bar, SessionInterface $session): Response
    {
        return $this->render('bar/show.html.twig', [
            'bar' => $bar,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="bar_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Bar $bar, SessionInterface $session): Response
    {
        $form = $this->createForm(BarType::class, $bar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bar_index');
        }

        return $this->render('bar/edit.html.twig', [
            'bar' => $bar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bar_delete", methods={"POST"})
     */
    public function delete(Request $request, Bar $bar, SessionInterface $session): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bar->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('bar_index');
    }
}
