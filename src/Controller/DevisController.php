<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Haie;
use App\Entity\Tailler;
use App\Entity\Devis;
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

        return $this->render('devis/index.html.twig', [ // Utilisation de crochets pour définir un tableau

            'devis' => [
                'haie' => $haieName,
                'prix' => $prix,
                'hauteur' => $hauteur,
                'longueur' => $longueur,
                'maVariable' => $maVariable,
                'controller_name' => 'DevisController',
            ]
        ]);
        
        
    }

    #[Route('/devis/create', name: 'app_devis_create')]
    public function create(Request $request): Response
    {
        // Récupérer les données du formulaire
        $haieId = $request->get('haie');
        $hauteur = $request->get('hauteur');
        $longueur = $request->get('longueur');

        // Créer une instance de Devis et définir ses propriétés
        $devis = new Devis();
        $devis->setDate(new \DateTime());
        $devis->setUser($this->getUser());

        // Récupérer la haie à partir de son ID
        $haie = $this->getDoctrine()->getRepository(Haie::class)->find($haieId);

        // Créer une instance de Tailler et définir ses propriétés
        $tailler = new Tailler();
        $tailler->setDevis($devis);
        $tailler->setHaie($haie);
        $tailler->setHauteur($hauteur);
        $tailler->setLongueur($longueur);

        // Enregistrer les entités dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($devis);
        $entityManager->persist($tailler);
        $entityManager->flush();

        // Rediriger vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }

    #[Route('/devis/list', name: 'app_devis_list')]
    public function list(): Response
    {
        $devis = $this->getDoctrine()->getRepository(Devis::class)->findAll();
        return $this->render('devis/list.html.twig', [
            'devis' => $devis,
        ]);
    }

    //supprimer un devis
    #[Route('/devis/delete/{id}', name: 'app_devis_delete')]
    public function delete($id): Response
    {

        //vérifier si l'utilisateur est admin
        $user = $this->getUser();
        if ($user === null || $user->getRoles()[0] !== 'ROLE_ADMIN'  ) {
            return $this->redirectToRoute('app_accueil');
        }
        $devis = $this->getDoctrine()->getRepository(Devis::class)->find($id);
        $tailler = $this->getDoctrine()->getRepository(Tailler::class)->findOneBy(['devis' => $devis]);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($tailler);
        $entityManager->remove($devis);

        $entityManager->flush();
        return $this->redirectToRoute('app_devis_list');
    }

}
