<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Haie;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\HaieRepository;

class EssaieController extends AbstractController
{
    #[Route('/essaie', name: 'app_essaie')]
    public function index(): Response
    {
        return $this->render('essaie/index.html.twig', [
            'controller_name' => 'EssaieController',
        ]);
    }

    
}
