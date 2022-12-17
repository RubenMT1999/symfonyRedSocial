<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\MicroPost;
use App\Entity\UserProfile;
use App\Repository\UserRepository;
use App\Repository\MicroPostRepository;
use App\Repository\UserProfileRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MicroPostController extends AbstractController
{

    private $microPostRepository;

    private $userRepository;
    private $userProfileRepository;
    private $userPasswordHasher;

    public function __construct(MicroPostRepository $microPostRepository,
                        UserRepository $userRepository,
                         UserProfileRepository $userProfileRepository,
                        UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->microPostRepository = $microPostRepository;
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }


    #[Route('/micro/post', methods:['GET'], name: 'app_micro_post')]
    public function getAll(): JsonResponse
    {
        /* $microPost = new MicroPost();
        $microPost->setTitle('It comes from controller');
        $microPost->setText('Hi!');
        $microPost->setCreated(new DateTime()); */

        /* $microPost = $posts->find(2);
        $microPost->setTitle('Welcome in general');

        $posts->save($microPost,true); */

        //dd($posts->findAll());

        $micros = $this->microPostRepository->findAll();
        $data = [];

        foreach($micros as $micro){
            $data[] = [
                'id' => $micro->getId(),
                'title' => $micro->getTitle(),
                'text' => $micro->getText(),
                'created' => $micro->getCreated(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    
    /* #[Route('/user/create', methods:['POST'], name: 'user_create')]
    public function addUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

        $email = $data['email'];
        $roles = $data['roles'];
        $password = $data['password'];

        if(empty($email) || empty($roles) || empty($password)) {
            throw new NotFoundHttpException('Se esperan otros parámetros!');
        }

        $newUser = new User();

        $hashPassword = $this->userPasswordHasher->hashPassword($newUser, $password);

        $newUser
            ->setEmail($email)
            ->setRoles($roles)
            ->setPassword($hashPassword);
        
        $this->userRepository->save($newUser,true);

        return new JsonResponse(['status' => 'User Creado!'], Response::HTTP_CREATED);
    } */


    #[Route('/profile/create', methods:['POST'], name: 'profile_create')]
    public function addProfile(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

        $name = $data['name'];
        $bio = $data['bio'];
        $website_url = $data['website_url'];
        $twitter_username = $data['twitter_username'];
        $company = $data['company'];
        $location = $data['location'];
        $date_of_birth = $data['date_of_birth'];
        $userId = $data['user_id'];

        /* if(empty($email) || empty($roles) || empty($password)) {
            throw new NotFoundHttpException('Se esperan otros parámetros!');
        } */

        if(!$this->userRepository->findOneBy(['id' => $userId])){
            throw new NotFoundHttpException('No existe un User con ese Id');
        }

        $this->userProfileRepository->guardarProfile($name,$bio,$website_url,
            $twitter_username,$company,$location,$date_of_birth,$userId);

        return new JsonResponse(['status' => 'UserProfile Creado!'], Response::HTTP_CREATED);
    }


}
