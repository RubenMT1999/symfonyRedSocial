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
use App\Entity\MicroPost;
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


    #[Route('followers/addFollower', name:'app_followers_añadir_username', methods:['POST'])]
    public  function addFollowerUsername(Utils $utils, Request $request): JsonResponse{



        $userToken = $utils->obtenerUsuarioToken($request);

        $userFollow = $this->userRepository->findOneBy(['email' =>$userToken->getEmail()]);

        $idUserParam = $request->get('twitter_username');

       $idUserFollower = $this->userProfileRepository->findIdUser($idUserParam);

       $userBuscar= $this->userRepository->findOneBy(['id' => $idUserFollower[0]['user_id']]);


        if (!empty($this->followersRepository->findIdFollowers($userFollow->getId(),$userBuscar->getId()) )){
            throw new NotFoundHttpException('Usted ya sigue a este usuario');
        }else{


            if ($userFollow == null || $idUserFollower == null){
                throw new NotFoundHttpException('Usuario incorrecto');
            }else {

                $follow = new Followers();

                $follow->setIdEmisor($userFollow);
                $follow->setIdReceptor($userBuscar);


                $this->followersRepository->addFollower($follow);


            }
        }




        return new JsonResponse(['resultado' => 'Follow Creado!'], Response::HTTP_CREATED);
    }

    #[Route('followers/deleteFollower', name:'app_followers_borrar_username', methods:['DELETE'])]
    public function deleteFollowerUsername( Utils $utils,Request $request): JsonResponse{

        $userToken = $utils->obtenerUsuarioToken($request);

        $userEmisor = $this->userRepository->findOneBy(['email' =>$userToken->getEmail()]);

        $idUserParam = $request->get('twitter_username');

        $idUserFollower = $this->userProfileRepository->findIdUser($idUserParam);

        $userBuscar= $this->userRepository->findOneBy(['id' => $idUserFollower[0]['user_id']]);

        $follower = $this->followersRepository->findIdFollowers($userEmisor->getId(), $userBuscar->getId());


        if (empty($follower)){
            throw new NotFoundHttpException('No existe ese follower');
        }else {

            $followerBorrar = $this->followersRepository->findOneBy(['id' => $follower[0]['id']]);

            $this->followersRepository->removeFollower($followerBorrar);

        }


        return new JsonResponse(['resultado' => 'Follow Eliminado!'], Response::HTTP_CREATED);

    }




}