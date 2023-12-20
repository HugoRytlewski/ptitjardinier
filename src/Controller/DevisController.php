<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class DevisController extends AbstractController
{
    #[Route('/devis', name: 'app_devis')]
    public function index(): Response
    {
        $request = Request::createFromGlobals();

        $session = new Session();
        $maVariable = $session->get('nomVariable'); 


        $haie = $request->get('haie');
        list($haieName, $haiePrice) = explode('|', $haie);


        $hauteur = $request->get('hauteur');
        $longueur = $request->get('longueur');

        $prix = $haiePrice * $longueur;

        if ($hauteur > 1.5) {
            $prix *= 1.5;
        }
        
        if ($maVariable === 'Entreprise') {
            $prix *= 0.9;
        }

        return $this->render('devis/index.html.twig', [ // corchet a la place de array 
            'haie' => $haieName,
            'prix' => $prix,
            'hauteur' => $hauteur,
            'longueur' => $longueur,
            'maVariable' => $maVariable,
            'controller_name' => 'DevisController',
        
        ]);
        
    }
}
