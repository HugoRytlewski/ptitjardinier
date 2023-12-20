<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class MesureController extends AbstractController
{
    #[Route('/mesure', name: 'app_mesure')]
    public function index(): Response
    {
        $request = Request::createFromGlobals();
        $choix=$request->get('choix');

        $session = new Session();
        $session->set('nomVariable', $choix);

        
        return $this->render('mesure/index.html.twig', [6+
            'controller_name' => 'MesureController',
        ]);
    }
}
