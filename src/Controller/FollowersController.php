<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\FollowersRepository;
use App\Utilidades\Utils;
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
    public function deleteUser( Utils $utils,Request $request): JsonResponse{

        $userToken = $utils->obtenerUsuarioToken($request);

        $userEmisor = $this->userRepository->findOneBy(['email' =>$userToken->getEmail()]);

        $nick = $request-> query->get("nick");

        $userReceptor = $this->userProfileRepository->findOneBy(['twitterUsername' => $nick]);

        $follower = $this->followersRepository->findIdFollowers($userEmisor->getId(), $userReceptor->getUser()->getId());

        $this->followersRepository->removeFollower($follower);

        $mensaje = "Follower eliminado";

        $usuJson = $utils->toJson($mensaje);



        return new JsonResponse($usuJson,200,[],true);

    }

}