<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Utilidades\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{


    private $userRepository;
    private $userProfileRepository;


    public function __construct(UserRepository $userRepository,
                         UserProfileRepository $userProfileRepository)
    {
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
    }

    #[Route('/user/list', name:'app_usuario_listar', methods:['GET'])]
    public function list(UserRepository $userRepository, Utils $utils): JsonResponse{

    $listUsuarios = $userRepository->findAll();

    if( empty($listUsuarios)){
        throw new NotFoundHttpException('No hay ningun usuario');
    }

    $usariosJson = $utils->toJson($listUsuarios);



        return new JsonResponse($usariosJson,200,[ ],true);
    }

    #[Route('user/list/name', name:'app_usuario_listar_nombre', methods:['GET'])]
    public function searchByName(UserProfileRepository $userProfileRepository, Utils $utils, Request $request): JsonResponse{

    $nombre = $request->query->get("name");

    $param = array(
        "name" => $nombre
    );

    $listUsuarios = $userProfileRepository->findBy($param);

    $usuJson = $utils->toJson($listUsuarios);



    return new JsonResponse($usuJson,200,[],true);        
    }

    #[Route('user/delete/{id}', name:'app_usuario_borrar_id', methods:['DELETE'])]
    public function deleteUser($id, Utils $utils,): JsonResponse{

    $usuario = $this->userRepository->findOneBy(['id' =>$id]);

    $this->userRepository->removeUser($usuario);

    $mensaje = "Usuario eliminado";

    $usuJson = $utils->toJson($mensaje);

        return new JsonResponse($usuJson,200,[],true);


    }
    
    


}