<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\MicroPost;
use App\Entity\UserProfile;
use App\Repository\UserRepository;
use App\Repository\MicroPostRepository;
use App\Repository\UserProfileRepository;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class MicroPostController extends AbstractController
{

    private $microPostRepository;

    

    public function __construct(MicroPostRepository $microPostRepository)
    {
        $this->microPostRepository = $microPostRepository;
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

    
    

    


}
