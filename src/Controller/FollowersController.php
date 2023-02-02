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

        $followerBorrar = $this->followersRepository->findOneBy(['id' =>$follower[0]['id']]);

        $this->followersRepository->removeFollower($followerBorrar);

        $mensaje = "Follower eliminado";

        $usuJson = $utils->toJson($mensaje);



        return new JsonResponse($usuJson,200,[],true);

    }

    #[Route('followers/add', name:'app_followers_añadir_id', methods:['POST'])]
    public  function addFollower(Utils $utils, Request $request): JsonResponse{

        $userToken = $utils->obtenerUsuarioToken($request);

        $userFollow = $this->userRepository->findOneBy(['email' =>$userToken->getEmail()]);

        $idUserParam = $request->get('idFollow');

        $userAddFollow = $this->userRepository->findOneBy(['id' => $idUserParam]);

        $follow = new Followers();

        $follow->setIdEmisor($userFollow->getId());
        $follow->setIdReceptor($userAddFollow->getId());


        $this->followersRepository->save($follow);


        if ($this->followersRepository->findIdFollowers($userFollow->getId(),$userAddFollow->getId()) != 0 || $this->followersRepository->findIdFollowers($userFollow->getId(),$userAddFollow->getId()) != null ){

        }

        $mensaje = "Follower añadido";

        $messageJson = $utils->toJson($mensaje);
        return new JsonResponse($messageJson,200,true);
    }



}