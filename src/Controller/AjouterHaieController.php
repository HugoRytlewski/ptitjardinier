<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Haie;
use App\Form\HaieType;
use App\Repository\HaieRepository;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class AjouterHaieController extends AbstractController
{
    #[Route('/ajouter/haie', name: 'app_ajouter_haie')]
    public function index(Request $request): Response
    {
  
        $haie = new Haie();
        $form = $this->createForm(HaieType::class, $haie);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $haie = $form->getData();   
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($haie);
            $entityManager->flush();
        }   


        return $this->render('ajouter_haie/index.html.twig', [
            'form' => $form->createView()]);
    }





    }



