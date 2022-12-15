<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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


    
    


}