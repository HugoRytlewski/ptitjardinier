<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Haie;
use App\Form\HaieType;
use App\Form\ModifType;
use App\Repository\HaieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ModifierHaieController extends AbstractController
{
    #[Route('/modifier/haie', name: 'app_modifier_haie')]
    public function index(): Response
    {
        return $this->render('modifier_haie/index.html.twig', [
            'controller_name' => 'ModifierHaieController',
        ]);
    }

    #[Route('/modifier/list', name: 'app_modifier_list')]
    public function liste(HaieRepository $haieRepository): Response
    { 
        $haies = $haieRepository->findAll();
        return $this->render('modifier_haie/index.html.twig', array('mesHaies' => $haies));
            
    }

    #[Route('/modifier/{code}', name: 'app_haie_modifierUneHaie')]
    public function modif(string $code ,EntityManagerInterface $em ,Request $request): Response
    {

     

        $haie = $em->getRepository(Haie::class)->find($code);
        return $this->render('LaHaiemodifier_haie/index.html.twig', ['haie' => $haie]);
    }

    #[Route('/modifier/', name: 'app_haie_modifLaHaie')]
    public function modifierHaie(EntityManagerInterface $em ,Request $request): Response
    {

        $request = Request::createFromGlobals();
        $nom=$request->get('nom');
        $prix=$request->get('prix');
        $code=$request->get('code');
        $codeModif=$request->get('codeModif');


        $haie = $em->getRepository(Haie::class)->find($codeModif);
        $haie->setNom($nom);
        $haie->setPrix($prix);
        $haie->setCode($code);
        $em->persist($haie);
        $em->flush();
        return $this->redirectToRoute('app_modifier_list');

       
    }
    
    




}
