<?php

namespace App\Controller;

use App\Repository\BarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'arrayErrors' => '',
        ]);
    }

    /**
     * @Route("/indexPag", name="indexPag")
     */
    public function indexPag(): Response
    {
        return $this->render('index.html.twig', []);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(SessionInterface $session): Response
    {
        $session->remove("barLogged");
        return $this->render('main/index.html.twig', [
            'arrayErrors' => null,
        ]);
    }

    /**
     * @Route("/comprovarLogin", name="comprovarLogin")
     */
    public function comprovarLogin(Request $request, SessionInterface $session, BarRepository $barRepository): Response
    {
        $error = "";
        $usuari = $request->get('usuari');
        $contrasenya = $request->get('contra');

        if ($usuari != "" && $usuari != null) {
        } else {
            $error = "El format del usuari és incorrecte.";
            return $this->render("main/index.html.twig", [
                "arrayErrors" => $error,
            ]);
        }

        if ($contrasenya != "" && $contrasenya != null) {
        } else {
            $error = "El format de la contrasenya és incorrecte.";
            return $this->render("main/index.html.twig", [
                "arrayErrors" => $error,
            ]);
        }
        if ($error != "") {
        } else {
            $bar = $barRepository->findOneBy(['nom' => $usuari]);

            if (!is_null($bar)) {
                if (strtoupper($bar->getNom()) == strtoupper($usuari) && $bar->getContrasenya() == $contrasenya) {

                    $session->set("barLogged", $bar);

                    return $this->render("index.html.twig", []);
                }
            }
        }
        $error = "L'usuari o la contrasenya són incorrectes";
        return $this->render('main/index.html.twig', [
            'arrayErrors' => $error,
        ]);
    }

    /**
     * @Route("/comprovarLoginApi", name="comprovarLoginApi", )
     */

    public function comprovarLoginApi(Request $request, BarRepository $barRepository): JsonResponse
    {
        $error = "";
        $array = $request->toArray();
        $usuari = $array['usuari'];
        $contrasenya = $array['contra'];

        if ($usuari != "" && $usuari != null) {
        } else {
            $data = [
                'sessio' => 0
            ];
            return new JsonResponse($data, Response::HTTP_OK);
        }

        if ($contrasenya != "" && $contrasenya != null) {
        } else {
            $data = [
                'sessio' => 0
            ];
            return new JsonResponse($data, Response::HTTP_OK);
        }
        if ($error != "") {
        } else {
            $bar = $barRepository->findOneBy(['nom' => $usuari]);

            if (!is_null($bar)) {
                if (strtoupper($bar->getNom()) == strtoupper($usuari) && $bar->getContrasenya() == $contrasenya) {

                    $data = [
                        'sessio' => 1,
                        'id' => $bar->getId(),
                        'nom' => $bar->getNom()
                    ];
                    return new JsonResponse($data, Response::HTTP_OK);
                }
            }
        }
        $data = [
            'sessio' => 0
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }
}
