<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\FollowersRepository;
use App\utilidades\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Entity\Followers;
use App\Repository\UserProfileRepository;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\VarExporter\Internal\Values;

class FollowersController extends AbstractController
{

    private $userRepository;
    private $userProfileRepository;
    private $followersRepository;
    private $userPasswordHasher;

    private $jwtEncoder;

    public function __construct(UserRepository              $userRepository,
                                UserProfileRepository       $userProfileRepository,
                                UserPasswordHasherInterface $userPasswordHasher,
                                JWTEncoderInterface         $jwtEncoder,
                                FollowersRepository         $followersRepository)
    {
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->jwtEncoder = $jwtEncoder;
        $this->followersRepository = $followersRepository;
    }

    #[Route('followers/delete', name:'app_followers_borrar_id', methods:['DELETE'])]
    public function deleteFollower( Utils $utils,Request $request): JsonResponse{

        $userToken = $utils->obtenerUsuarioToken($request);

        $userEmisor = $this->userRepository->findOneBy(['email' =>$userToken->getEmail()]);

        $idEliminar =  $request-> query->get("idEliminar");

        $value2 = (int) $idEliminar;

        $follower = $this->followersRepository->findIdFollowers($userEmisor->getId(), $value2);


        if (empty($follower)){
            throw new NotFoundHttpException('No existe ese follower');
        }else {

            $followerBorrar = $this->followersRepository->findOneBy(['id' => $follower[0]['id']]);

            $this->followersRepository->removeFollower($followerBorrar);

        }


        return new JsonResponse(['resultado' => 'Follow Eliminado!'], Response::HTTP_CREATED);

    }

    #[Route('followers/add', name:'app_followers_añadir_id', methods:['POST'])]
    public  function addFollower(Utils $utils, Request $request): JsonResponse{



        $userToken = $utils->obtenerUsuarioToken($request);

        $userFollow = $this->userRepository->findOneBy(['email' =>$userToken->getEmail()]);

        $idUserParam = $request->get('idFollow');

        $userAddFollow = $this->userRepository->findOneBy(['id' => $idUserParam]);


        if (!empty($this->followersRepository->findIdFollowers($userFollow->getId(),$userAddFollow->getId()) )){
            throw new NotFoundHttpException('Usted ya sigue a este usuario');
        }else{


        if ($userFollow == null || $userAddFollow == null){
            throw new NotFoundHttpException('Usuario incorrecto');
        }else {

            $follow = new Followers();

            $follow->setIdEmisor($userFollow);
            $follow->setIdReceptor($userAddFollow);


            $this->followersRepository->addFollower($follow);

        }
        }

        return new JsonResponse(['resultado' => 'Follow Creado!'], Response::HTTP_CREATED);
    }

    // Metodo para ver la gente que sigue tal usuario
/*     #[Route('/followers', name: 'app_followers')]
    public function getFollowers(FollowersRepository $followersRepository,UserProfileRepository $userProfileRepository,Utils $utilidades, Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), true);
        $followers = $followersRepository->findBy(['id_emisor' => $data]);

        foreach ($followers as $array){
            $getProfile = $userProfileRepository->findOneBy(['user' => $array->getIdReceptor()]);
            $data2 [] = [
                'name' => $getProfile->getName(),
                'username' => $getProfile->getTwitterUsername(),
                'image' => $getProfile->getImage()

            ];
        }

        if ($followers == null){
            return new JsonResponse(['status' => "No tiene Seguidores"], Response::HTTP_CREATED);
        }

        $toJson = $utilidades->toJson($data2, null);

        return new JsonResponse($toJson, 200,[],true);
    } */

// Metodo para ver la gente sigue tal usuario
/*     #[Route('/following', name: 'app_following')]
    public function getFollowing(FollowersRepository $followersRepository,UserProfileRepository $userProfileRepository,Utils $utilidades, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $following = $followersRepository->findBy(['id_receptor' => $data]);

        foreach ($following as $array){
            $getProfile = $userProfileRepository->findOneBy(['user' => $array->getIdEmisor()]);
            $data2 [] = [
                'name' => $getProfile->getName(),
                'username' => $getProfile->getTwitterUsername(),
                'image' => $getProfile->getImage()
            ];
        }

        if ($following == null){
            return new JsonResponse(['status' => "No siges a nadie"], Response::HTTP_CREATED);
        }

        $toJson = $utilidades->toJson($data2, null);

        return new JsonResponse($toJson, 200,[],true);
    } */


    #[Route('followers/addFollower', name:'app_followers_añadir_username', methods:['POST'])]
    public  function addFollowerUsername(Request $request): JsonResponse{


        // Obtener el token JWT del encabezado de la solicitud
        $token = $request->headers->get('Authorization');
        if ($token) {
            // Decodificar el token JWT y obtener el contenido
            $data = $this->jwtEncoder->decode($token);

            $data2 =  $data['username'];
        }

        $usuarioDelToken = $this->userRepository->findOneBy(['email' => $data2]);

        /* $userToken = $utils->obtenerUsuarioToken($request); */

        /* $userFollow = $this->userRepository->findOneBy(['email' =>$userToken->getEmail()]); */

        $data3 = json_decode($request->getContent(), true);

        $userParam = $data3['twitterUsername'];

       $idUserFollower = $this->userProfileRepository->findOneBy(['twitterUsername' => $userParam]);

       $userBuscar= $idUserFollower->getUser();

        if (!empty($this->followersRepository->findIdFollowers($usuarioDelToken->getId(),$userBuscar->getId()) )){
            return new JsonResponse(['Seguido' => 'Usted ya sigue a este usuario!'], Response::HTTP_CREATED);
        }else{

            if ($usuarioDelToken == null || $idUserFollower == null){
                throw new NotFoundHttpException('Usuario incorrecto');
            }else {

                $follow = new Followers();

                $follow->setIdEmisor($usuarioDelToken);
                $follow->setIdReceptor($userBuscar);

                $this->followersRepository->addFollower($follow);
            }
        }
        return new JsonResponse(['resultado' => 'Follow Creado!'], Response::HTTP_CREATED);
    }

    #[Route('followers/deleteFollower', name:'app_followers_borrar_username', methods:['DELETE'])]
    public function deleteFollowerUsername( Utils $utils,Request $request): JsonResponse{

        $token = $request->headers->get('Authorization');
        if ($token) {
            // Decodificar el token JWT y obtener el contenido
            $data = $this->jwtEncoder->decode($token);

            $data2 =  $data['username'];
        }

        $userEmisor = $this->userRepository->findOneBy(['email' => $data2]);

        $data3 = json_decode($request->getContent(), true);

        $userParam = $data3['twitter_username'];

        $idUserFollower = $this->userProfileRepository->findOneBy(['twitterUsername' => $userParam]);

        $userReceptor= $idUserFollower->getUser();

        $follower = $this->followersRepository->findIdFollowers($userEmisor->getId(), $userReceptor->getId());

        if (empty($follower)){
            throw new NotFoundHttpException('No existe ese follower');
        }else {
            $followerBorrar = $this->followersRepository->findOneBy(['id' => $follower[0]['id']]);

            $this->followersRepository->removeFollower($followerBorrar);
        }

        return new JsonResponse(['resultado' => 'Follow Eliminado!'], Response::HTTP_CREATED);
    }


    #[Route('followers/contarFollowers', name:'app_contar_followers', methods:['POST'])]
    public function contarSeguidores(Request $request): JsonResponse{

        $data = json_decode($request->getContent(), true);

        $username = $data['twitter_username'];

        $usuarioProfile = $this->userProfileRepository->findOneBy(['twitterUsername' => $username]);

        $usuario = $usuarioProfile->getUser();

        $usuarioId = $usuario->getId();

        $miArray = $this->followersRepository->contarSeguidores($usuarioId);

        return new JsonResponse(['array' => $miArray[0]['numero']], Response::HTTP_CREATED);
    }


    #[Route('followers/listarFollowers', name:'app_listar_followers', methods:['POST'])]
    public function listarSeguidores(Request $request): JsonResponse{

        $data = json_decode($request->getContent(), true);

        $username = $data['twitter_username'];

        $usuarioProfile = $this->userProfileRepository->findOneBy(['twitterUsername' => $username]);

        $usuario = $usuarioProfile->getUser();

        $usuarioId = $usuario->getId();

        $miArray = $this->followersRepository->listarSeguidores($usuarioId);

        $listaUsuarios= [];

        foreach ($miArray as $misSeguidores) {
           $idUser = $this->userRepository->findOneBy(['id' => $misSeguidores['seguidor']]);
           $perfil = $idUser->getUserProfile();
           $twitterUsernamePerfil = $perfil->getTwitterUsername();
           $listaUsuarios[] = $twitterUsernamePerfil;
        }


        return new JsonResponse(['listaUsuarios' => $listaUsuarios], Response::HTTP_CREATED);
    }


    #[Route('followers/loSigue', name:'app_loSigue_followers', methods:['POST'])]
    public function loSigue(Request $request): JsonResponse{

        $data = json_decode($request->getContent(), true);

        $usernameEmisor = $data['usernameEmisor'];
        $usernameReceptor = $data['usernameReceptor'];

        $perfil1 = $this->userProfileRepository->findOneBy(['twitterUsername' => $usernameEmisor]);
        $idEmisor1 = $perfil1->getId();

        $perfil2 = $this->userProfileRepository->findOneBy(['twitterUsername' => $usernameReceptor]);
        $idReceptor= $perfil2->getId();

        $miId = $this->followersRepository->loEstasSiguiendo($idEmisor1,$idReceptor);
        $miId2 = array_column($miId,'id');

        if($miId == null){
            return new JsonResponse(['error'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['idFollow' => $miId2], Response::HTTP_CREATED);
    }



    #[Route('followers/contarQuienMeSigue', name:'app_contarQuienMeSigue_followers', methods:['POST'])]
    public function contarQuienMeSigue(Request $request): JsonResponse{

        $data = json_decode($request->getContent(), true);

        $username = $data['twitter_username'];

        $usuarioProfile = $this->userProfileRepository->findOneBy(['twitterUsername' => $username]);

        $usuario = $usuarioProfile->getUser();

        $usuarioId = $usuario->getId();

        $miArray = $this->followersRepository->contarCuantosMeSiguen($usuarioId);

        return new JsonResponse(['array' => $miArray[0]['numero']], Response::HTTP_CREATED);
    }


    #[Route('followers/listarQuienMeSigue', name:'app_listarQuienMeSigue_followers', methods:['POST'])]
    public function listarQuienMeSigue(Request $request): JsonResponse{

        $data = json_decode($request->getContent(), true);

        $username = $data['twitter_username'];

        $usuarioProfile = $this->userProfileRepository->findOneBy(['twitterUsername' => $username]);

        $usuario = $usuarioProfile->getUser();

        $usuarioId = $usuario->getId();

        $miArray = $this->followersRepository->personasQueMeSiguen($usuarioId);

        $listaUsuarios= [];

        foreach ($miArray as $misSeguidores) {
           $idUser = $this->userRepository->findOneBy(['id' => $misSeguidores['seguidor']]);
           $perfil = $idUser->getUserProfile();
           $twitterUsernamePerfil = $perfil->getTwitterUsername();
           $listaUsuarios[] = $twitterUsernamePerfil;
        }


        return new JsonResponse(['listaUsuarios' => $listaUsuarios], Response::HTTP_CREATED);
    }


}