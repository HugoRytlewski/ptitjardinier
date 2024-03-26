<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChoixController extends AbstractController
{
    #[Route('/choix', name: 'app_choix')]
public function index(): Response
{
    //regatder si quelqu'un est connecté
    $user = $this->getUser();
    if ($user) {
        $typeClient = $user->getTypeClient();
        if ($typeClient === 1) {
            $typeClient = "Entreprise";
            return $this->redirectToRoute('app_haiee_test', ['user' => $typeClient]);
        }
        if ($typeClient === 0) {
            $typeClient = "Particulier";
            return $this->redirectToRoute('app_haiee_test', ['user' => $typeClient]);
        }
    } else {
        return $this->render('choix/index.html.twig', [
            'controller_name' => 'ChoixController',
        ]);
    }

    // Ajoutez une réponse de secours au cas où aucune condition n'est remplie
    // Vous pouvez personnaliser cette réponse en fonction de vos besoins
    return new Response('Une erreur s\'est produite.');
}
}

