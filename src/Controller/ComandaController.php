<?php

namespace App\Controller;

use App\Entity\Comanda;
use App\Form\ComandaType;
use App\Repository\BarRepository;
use App\Repository\ComandaRepository;
use App\Repository\ProducteRepository;
use App\Repository\TaulaRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comanda")
 */
class ComandaController extends AbstractController
{
    /**
     * @Route("/", name="comanda_index", methods={"GET"})
     */
    public function index(ComandaRepository $comandaRepository, SessionInterface $session, BarRepository $barRepository): Response
    {
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }

        return $this->render('comanda/index.html.twig', [
            'comandas' => $comandaRepository->findBy(['acabat' => 0, 'bar' => $barRepository->findOneBy(['id' => $session->get('barLogged')])]),
        ]);
    }

    /**
     * @Route("/comandesAcabades", name="comandesAcabades", methods={"GET"})
     */
    public function comndesAcabades(ComandaRepository $comandaRepository, SessionInterface $session, BarRepository $barRepository): Response
    {
        if ($session->get('barLogged') == null) {
            return $this->render('main/index.html.twig', [
                'arrayErrors' => null,
            ]);
        }
        return $this->render('comanda/indexAcabades.html.twig', [
            'comandas' => $comandaRepository->findBy(['acabat' => 1, 'bar' => $barRepository->findOneBy(['id' => $session->get('barLogged')])]),
        ]);
    }

    /**
     * @Route("/acabarComanda", name="acabarComanda", methods={"POST"})
     */
    public function acabarComanda(ComandaRepository $comandaRepository, SessionInterface $session, BarRepository $barRepository, Request $request): JsonResponse
    {
        $objDateTime = new DateTime('NOW');
        $array = $request->toArray();
        $comanda = $comandaRepository->findOneBy(["id" => $array['id']]);
        $comanda->setAcabat(true);
        $comanda->setHoraAcabat($objDateTime);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comanda);
        $entityManager->flush();

        return new JsonResponse($array, Response::HTTP_OK);
    }

    /**
     * @Route("/afegirComandaPost", name="afegirComandaPost", methods={"POST"})
     */
    public function afegirComandaPost(Request $request, BarRepository $barRepository, SessionInterface $session, TaulaRepository $taulaRepository, ProducteRepository $producteRepository): JsonResponse
    {
        $array = $request->toArray();
        $arrayComanda = [];
        if ($array['bar'] == $session->get('barLogged')->getId()) {
            $bar = $barRepository->findOneBy(['id' => $array['bar']]);
            $taula = $taulaRepository->findOneBy(['id' => $array['taula']]);

            $comandax = new Comanda();
            $comandax->setAcabat(false);
            $comandax->setBar($bar);
            $comandax->setComentari($array['comentari']);
            $comandax->setTaula($taula);
            $comandax->setPreuTotal($array['preu']);

            $productes = $array['productes'];

            foreach ($productes as $producte) {
                $prod = $producteRepository->findOneBy(['id' => $producte["id"]]);
                $prod->addComanda($comandax);
                $comandax->addProducte($prod);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comandax);
            $entityManager->flush();
            $arrayComanda = [
                "id" => $comandax->getId(),
                "comentari" => $comandax->getComentari(),
                "taula" => $array['taula'],
                "productes" => $productes,
                "preuTotal" => $comandax->getPreuTotal()
            ];
        } else {
            $arrayComanda = null;
        }

        return new JsonResponse($arrayComanda, Response::HTTP_OK);
    }

    /**
     * @Route("/new", name="comanda_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $comanda = new Comanda();
        $form = $this->createForm(ComandaType::class, $comanda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comanda);
            $entityManager->flush();

            return $this->redirectToRoute('comanda_index');
        }

        return $this->render('comanda/new.html.twig', [
            'comanda' => $comanda,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comanda_show", methods={"GET"})
     */
    public function show(Comanda $comanda): Response
    {
        return $this->render('comanda/show.html.twig', [
            'comanda' => $comanda,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="comanda_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Comanda $comanda): Response
    {
        $form = $this->createForm(ComandaType::class, $comanda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comanda_index');
        }

        return $this->render('comanda/edit.html.twig', [
            'comanda' => $comanda,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comanda_delete", methods={"POST"})
     */
    public function delete(Request $request, Comanda $comanda): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comanda->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comanda);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comanda_index');
    }


}
