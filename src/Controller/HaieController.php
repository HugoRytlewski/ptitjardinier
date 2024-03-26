<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Haie;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\HaieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Form; 
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class HaieController extends AbstractController
{
   
 //#[Route('/haie/{code}', name: 'app_haie_afficher')]
 //public function afficher(string $code, EntityManagerInterface $em): Response
 //{
 //    $haie = $em->getRepository(Haie::class)->find($code);
 //    if (!$haie) {
 //        return new Response('Aucune haie trouvée pour le code ' . $code);
 //    }
 //    return new Response('Haie trouvée : ' . $haie->getNom(). ' ' . $haie->getPrix(). '€');
 //}

   

        #[Route('/haie/modifier/{code}', name: 'app_haie_modifier')]
        public function modifier(string $code, EntityManagerInterface $em): Response
        {
            $haie = $em->getRepository(Haie::class)->find($code);
            if (!$haie) {
                return new Response('Aucune haie trouvée pour le code ' . $code);
            }
            $haie->setNom('Laurier du Caucase');
            $em->flush();
            return $this->redirectToRoute('app_haie_voir', ['code' => $haie->getCode()]);
        }

        #[Route('/haie/supprimer/{code}', name: 'app_haie_supprimer')]
        public function supprimer(string $code, EntityManagerInterface $em): Response
        {
            $haie = $em->getRepository(Haie::class)->find($code);
            $em->remove($haie);
            $em->flush();

            return $this->redirectToRoute('app_haie_voir', ['code' => $haie->getCode()]);

        }

       
        

        
    


        #[Route('/haiee/test/{user}', name: 'app_haiee_test')]
        public function liste($user,HaieRepository $haieRepository): Response
        {
           
           $request = Request::createFromGlobals();
           $choix=$request->get('choix');

           if ($user !== 'not_connected') {
                $choix = $user;
           }

           $session = new Session();
           $session->set('nomVariable', $choix);

           $haies = $haieRepository->findAll();
            return $this->render('mesure/index.html.twig', array('mesHaies' => $haies, $choix));
            
        }


}
