<?php

namespace App\Controller;

use App\Entity\Haie;
use App\Entity\Tailler;
use App\Entity\Devis;
use App\Repository\HaieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class DevisController extends AbstractController
{
    #[Route('/devis', name: 'app_devis')]
    public function index(HaieRepository $haieRepo): Response
    {

        $request = Request::createFromGlobals();

        $longueurs = $request->query->get('longueurs');
        $hauteurs = $request->query->get('hauteurs');
        $haies = $request->query->get('haie');
        
        $session = new Session();
        $ParticulierOuEntrprise = $session->get('nomVariable'); 

        $tabsHaie = [];
        //pour chaque haie on crée un objet haie avec sa longueur et sa hauteur
        for ($i = 1; $i <= count($haies); $i++) {
            $tabsHaie[] = [
                'haie' => $haies[$i],
                'longueur' => $longueurs[$i],
                'hauteur' => $hauteurs[$i]
            ];
        }

        //calcul du prix total
        $prixFinal = 0;
        foreach ($tabsHaie as $haie) {
            $haiePrice = $haieRepo->find($haie['haie'])->getPrix();
            $prix = $haie['longueur'] * $haiePrice;
            if ($haie['hauteur'] > 1.5) {
                $prix *= 1.5;
            }
            if ($ParticulierOuEntrprise === 'Entreprise') {
                $prix *= 0.9;
            }
            $prixFinal += $prix;
        }
        return $this->render('devis/index.html.twig', [ // Utilisation de crochets pour définir un tableau

            'devis' => [
                'nomination' =>$ParticulierOuEntrprise,
                'haies' => $tabsHaie,
                'prix' => $prixFinal,
            ]
        ]);
        
        
    }

    #[Route('/devis/create', name: 'app_devis_create')]
    public function create(Request $request): Response
    {
        // Récupérer les données du formulaire
        $haieList = $request->get('haie');
        $hauteur = $request->get('hauteur');
        $longueur = $request->get('longueur');

        // Créer une instance de Devis et définir ses propriétés
        $devis = new Devis();
        $devis->setDate(new \DateTime());
        $devis->setUser($this->getUser());

        // Enregistrer le devis en base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($devis);
        $entityManager->flush();
    
        
        foreach ($haieList as $haie) {
            $Lahaie = $this->getDoctrine()->getRepository(Haie::class)->findOneBy(['nom' => $haie['nom']]);
            $longueur = $haie['longueur'];
            $hauteur = $haie['hauteur'];
            if($longueur != 0   || $hauteur != 0){
            $tailler = new Tailler();
            $tailler->setDevis($devis);
            $tailler->setHaie($Lahaie);
            $tailler->setHauteur($hauteur);
            $tailler->setLongueur($longueur);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tailler);
            $entityManager->flush();
        }

        }

       
      

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
        $tailler = $this->getDoctrine()->getRepository(Tailler::class)->findBy(['devis' => $devis->getNo()]);
        foreach ($tailler as $taille) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($taille);
            $entityManager->flush();
        }
        $entityManager->remove($devis);
        $entityManager->flush();
        return $this->redirectToRoute('app_devis_list');
    }

    #[Route('/devis/detail/{id}', name: 'app_devis_detail')]
    public function detail($id): Response
    {
        $devis = $this->getDoctrine()->getRepository(Devis::class)->find($id);
        $tailler = $this->getDoctrine()->getRepository(Tailler::class)->findBy(['devis' => $devis->getNo()]);
        $tabTailler = [];
        foreach ($tailler as $taille) {
            $tabTailler[] = [
                'haie' => $taille->getHaie()->getNom(),
                'longueur' => $taille->getLongueur(),
                'hauteur' => $taille->getHauteur(),
            ];
        }

        return $this->render('devis/detail.html.twig', [
            'devis' => $devis,
            'tailler' => $tabTailler,
        ]);
    }



}
