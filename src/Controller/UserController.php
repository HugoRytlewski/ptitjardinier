<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $user): Response
    {

        $ListUser = $user->findAll();

        return $this->render('user/index.html.twig', ['ListUser' =>$ListUser,]);
        
    }

    #[Route('/user/{id}', name: 'app_user_show')]
    public function show(UserRepository $user, $id): Response
    {
        $DetailUser = $user->findOneBy(['id' => $id]);

        return $this->render('user/show.html.twig', ['user' => $DetailUser]);
    }
    #[Route('/user/modif/{id}', name: 'app_user_edit')]
    public function edit(Request $request,UserRepository $user, $id): Response
    {
        $request = Request::createFromGlobals();
        $DetailUser = $user->findOneBy(['id' => $id]);
        $email = $request->request->get('email');
        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $adresse = $request->request->get('adresse');
        $ville = $request->request->get('ville');
        $codePostal = $request->request->get('cp');
        $typeClient = $request->request->get('typeClient');

        $DetailUser->setEmail($email);
        $DetailUser->setNom($nom);
        $DetailUser->setPrenom($prenom);
        $DetailUser->setAdresse($adresse);
        $DetailUser->setVille($ville);
        $DetailUser->setCp($codePostal);
        $DetailUser->setTypeClient($typeClient);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($DetailUser);
        $entityManager->flush();

        return $this->redirectToRoute('app_user');

    }

    #[Route('/user/delete/{id}', name: 'app_user_delete')]
    public function delete(UserRepository $user, $id): Response
    {
        $DetailUser = $user->findOneBy(['id' => $id]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($DetailUser);
        $entityManager->flush();

        return $this->redirectToRoute('app_user');
    }


}
