<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use App\Entity\User;
use App\Entity\MicroPost;
use App\Entity\UserProfile;
use App\Repository\UserProfileRepository;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends AbstractController
{
    
    private $userRepository;
    private $userProfileRepository;
    private $userPasswordHasher;

    private $jwtEncoder;

    public function __construct(UserRepository $userRepository,
                         UserProfileRepository $userProfileRepository,
                        UserPasswordHasherInterface $userPasswordHasher,
                        JWTEncoderInterface $jwtEncoder)
    {
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->jwtEncoder = $jwtEncoder;
    }

    #[Route('/user/create', methods:['POST'], name: 'user_create')]
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
    }


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
        $usermail = $data['usermail'];

        /* if(empty($email) || empty($roles) || empty($password)) {
            throw new NotFoundHttpException('Se esperan otros parámetros!');
        } */

        if(!$this->userRepository->findOneBy(['email' => $usermail])){
            throw new NotFoundHttpException('No existe un User con ese email');
        }

        $this->userProfileRepository->guardarProfile($name,$bio,$website_url,
            $twitter_username,$company,$location,$date_of_birth,$usermail);

        return new JsonResponse(['status' => 'UserProfile Creado!'], Response::HTTP_CREATED);
    }


    #[Route('/profile/get', methods:['GET'], name: 'profile_get')]
    public function getProfile(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

        $user = $data['user'];

        $miUsuario = $this->userRepository->findOneBy(['email' => $user]);
        $miProfile = $this->userProfileRepository->findOneBy(['user' => $miUsuario]);

        if(!$miUsuario){
            throw new NotFoundHttpException('No existe ese usuario');
        }

        if(!$miProfile){
            throw new NotFoundHttpException('No existe un profile de ese usuario');
        }

        $data[] = [
            'name' => $miProfile->getName(),
            'bio' => $miProfile->getBio(),
            'website_url' => $miProfile->getWebsiteUrl(),
            'username' => $miProfile->getTwitterUsername(),
            'company' => $miProfile->getCompany(),
            'location' => $miProfile->getLocation(),
            'date_of_birth' => $miProfile->getDateOfBirth(),
        ];

        return new JsonResponse(['status' => 'UserProfile encontrado!',
                                    'userProfile' => $data], Response::HTTP_OK);
    }




    #[Route('/userLogged', methods:['GET'], name: 'user_logged')]
    public function userLogged(Request $request): JsonResponse{
        
        // Obtener el token JWT del encabezado de la solicitud
        $token = $request->headers->get('Authorization');
        if ($token) {
            // Decodificar el token JWT y obtener el contenido
            $data = $this->jwtEncoder->decode($token);

            $data2[] = [
                'username' => $data['username'],
                'roles' => $data['roles']
            ];
            
            // $data es un array con los datos del token JWT

            // Transformar el array a formato JSON y devolverlo como respuesta
            return new JsonResponse($data2);
        }
    }


}
