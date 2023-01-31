<?php

namespace App\Controller;

use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Utilidades\Utils;
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

    #[Route('/user/name',  name: 'app_user_list_name' ,methods:['POST'])]
    public function list_name(UserProfileRepository $userProfileRepository,UserRepository $userRepository,Utils $utilidades, Request $request): JsonResponse
    {

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
