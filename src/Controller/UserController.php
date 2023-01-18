<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\utils\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{  
  


    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

  

    #[Route('/user/list', name: 'app_user_list')]
    public function list(UserRepository $userRepository,Utils $utilidades, Request $request): JsonResponse
    {   
        // $nombre = $request -> request ->get('name');
        // $busqueda = array('name'=> $nombre);
        $listUsuario = $userRepository ->findAll();
        $listJson = $utilidades -> toJson($listUsuario);
        return new JsonResponse($listJson, 200,[], true);
    }
}
