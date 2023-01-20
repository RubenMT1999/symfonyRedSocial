<?php

namespace App\Controller;

use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\utilidades\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    #[Route('/user/name',  name: 'app_user_list_name' ,methods:['POST'])]
    public function list_name(UserProfileRepository $userProfileRepository,UserRepository $userRepository,Utils $utilidades, Request $request): JsonResponse
    {
        // $nombre = $request -> request ->get('name');
        // $busqueda = array('name'=> $nombre);
        $data = json_decode($request->getContent(),true);
        $userProfile = $userRepository->findOneBy(['email' => $data]);
        $listProfile = $userProfileRepository->findOneBy(['user' => $userProfile]);
        if ($listProfile == null){
            throw new NotFoundHttpException('No existe Usuario con ese Email');
        }else {
            $data2[] = [
                'name' => $listProfile->getName(),
                'bio' => $listProfile->getBio(),
                'website_url' => $listProfile->getWebsiteUrl(),
                'username' => $listProfile->getTwitterUsername(),
                'company' => $listProfile->getCompany(),
                'location' => $listProfile->getLocation(),
                'email' => $listProfile->getUser()->getEmail(),
                'date_of_birth' => $listProfile->getDateOfBirth()
            ];
        }
            $listJson = $utilidades -> toJson($data2);


        return new JsonResponse($listJson, 200,[], true);
    }

}
