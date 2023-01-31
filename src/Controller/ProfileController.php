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
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;

use function PHPSTORM_META\type;

class ProfileController extends AbstractController
{

    private $userRepository;
    private $userProfileRepository;
    private $userPasswordHasher;

    private $jwtEncoder;

    public function __construct(UserRepository              $userRepository,
                                UserProfileRepository       $userProfileRepository,
                                UserPasswordHasherInterface $userPasswordHasher,
                                JWTEncoderInterface         $jwtEncoder)
    {
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->jwtEncoder = $jwtEncoder;
    }



    

    #[Route('/user/create', methods:['POST'], name: 'user_create')]
/*     #[OA\Response(
        response: 200,
        description: 'Usuario creado correctamente',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class, groups: ['full']))
        )
    )]
    #[OA\RequestBody(
        description: 'Envía el email, los roles y el password',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property:'email',type:'string'),
                new OA\Property(property:'roles',type:'array', @OA\Items(anyOf={@OA\Schema(type: "string")})),
                new OA\Property(property:'password',type:'string'),
            ]
        )
    )] */
    public function addUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'];
        $roles = $data['roles'];
        $password = $data['password'];

        if (empty($email) || empty($roles) || empty($password)) {
            throw new NotFoundHttpException('Se esperan otros parámetros!');
        }

        $newUser = new User();

        $hashPassword = $this->userPasswordHasher->hashPassword($newUser, $password);

        $newUser
            ->setEmail($email)
            ->setRoles($roles)
            ->setPassword($hashPassword);

        $this->userProfileRepository->establecerProfileVacio($newUser);

        $this->userRepository->save($newUser, true);

        return new JsonResponse(['status' => 'User Creado!'], Response::HTTP_CREATED);
    }


    #[Route('/profile/create', methods: ['POST'], name: 'profile_create')]
    public function addProfile(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $bio = $data['bio'];
        $website_url = $data['website_url'];
        $twitter_username = $data['twitter_username'];
        $company = $data['company'];
        $location = $data['location'];
        $date_of_birth = $data['date_of_birth'];
        $usermail = $data['usermail'];
        $phone_number = $data['phone_number'];

        /* if(empty($email) || empty($roles) || empty($password)) {
            throw new NotFoundHttpException('Se esperan otros parámetros!');
        } */

        if (!$this->userRepository->findOneBy(['email' => $usermail])) {
            throw new NotFoundHttpException('No existe un User con ese email');
        }

        $obtenerUser = $this->userRepository->findOneBy(['email' => $usermail]);
        $obtenerProfile = $this->userProfileRepository->findOneBy(['twitterUsername' => $twitter_username]);


        if($obtenerProfile != null){
            if($obtenerProfile->getTwitterUsername() != $obtenerUser->getEmail()
                && $obtenerProfile->getTwitterUsername() != $obtenerUser->getUserProfile()->getTwitterUsername()){
                return new JsonResponse(['status' => 'Ese username ya está en uso!'], Response::HTTP_CONFLICT);
            }
        }

        if($obtenerUser->getUserProfile() != null){
            $obtenerUser->getUserProfile()->setName($name);
            $obtenerUser->getUserProfile()->setBio($bio);
            $obtenerUser->getUserProfile()->setWebsiteUrl($website_url);
            $obtenerUser->getUserProfile()->setTwitterUsername($twitter_username);
            $obtenerUser->getUserProfile()->setCompany($company);
            $obtenerUser->getUserProfile()->setLocation($location);
            $obtenerUser->getUserProfile()->setPhoneNumber($phone_number);
            $fecha = new DateTime($date_of_birth);
            $obtenerUser->getUserProfile()->setDateOfBirth($fecha);

            $updatedProfile = $this->userProfileRepository->updateProfile($obtenerUser->getUserProfile());
            return new JsonResponse(['status' => 'UserProfile Actualizado!'], Response::HTTP_CREATED);
        }

        $this->userProfileRepository->guardarProfile($name, $bio, $website_url,
            $twitter_username, $company, $location, $date_of_birth, $usermail, $phone_number);

        return new JsonResponse(['status' => 'UserProfile Creado!'], Response::HTTP_CREATED);
    }


    #[Route('/profile/get', methods: ['POST'], name: 'profile_get')]
    public function getProfile(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $data['user'];

        $miUsuario = $this->userRepository->findOneBy(['email' => $user]);
        $miProfile = $this->userProfileRepository->findOneBy(['user' => $miUsuario]);

        if (!$miUsuario) {
            throw new NotFoundHttpException('No existe ese usuario');
        }

        if (!$miProfile) {
            throw new NotFoundHttpException('No existe un perfil de ese usuario');
        }

        $data2[] = [
            'name' => $miProfile->getName(),
            'bio' => $miProfile->getBio(),
            'website_url' => $miProfile->getWebsiteUrl(),
            'username' => $miProfile->getTwitterUsername(),
            'company' => $miProfile->getCompany(),
            'location' => $miProfile->getLocation(),
            'date_of_birth' => $miProfile->getDateOfBirth(),
            'phone_number' => $miProfile->getPhoneNumber(),
        ];

        return new JsonResponse(['userProfile' => $data2], Response::HTTP_OK);
    }


    #[Route('/profile/buscar', methods: ['POST'], name: 'buscar_usuario')]
    public function buscarUsuario(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $data['username'];

        $misProfiles = $this->userProfileRepository->findBy(['twitterUsername' => $user]);


        if (!$misProfiles) {
            throw new NotFoundHttpException('No existe un profile de ese usuario');
        }

        foreach ($misProfiles as $miProfile) {
            $data2[] = [
                'name' => $miProfile->getName(),
                'bio' => $miProfile->getBio(),
                'website_url' => $miProfile->getWebsiteUrl(),
                'username' => $miProfile->getTwitterUsername(),
                'company' => $miProfile->getCompany(),
                'direccion' => $miProfile->getLocation(),
                'fecha' => $miProfile->getDateOfBirth(),
                'phone_number' => $miProfile->getPhoneNumber(),
            ];
        }


        return new JsonResponse(['userProfile' => $data2], Response::HTTP_OK);
    }


    #[Route('/profile/buscar', methods: ['POST'], name: 'buscar_usuario')]
    public function buscarUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $data['username'];

        $misProfiles = $this->userProfileRepository->findBy(['twitterUsername' => $user]);


        if (!$misProfiles) {
            throw new NotFoundHttpException('No existe un profile de ese usuario');
        }

        foreach ($misProfiles as $miProfile) {
            $data2[] = [
                'name' => $miProfile->getName(),
                'bio' => $miProfile->getBio(),
                'website_url' => $miProfile->getWebsiteUrl(),
                'username' => $miProfile->getTwitterUsername(),
                'company' => $miProfile->getCompany(),
                'direccion' => $miProfile->getLocation(),
                'fecha' => $miProfile->getDateOfBirth(),
                'phone_number' => $miProfile->getPhoneNumber(),
            ];
        }


        return new JsonResponse(['userProfile' => $data2], Response::HTTP_OK);
    }


    #[Route('/userLogged', methods: ['GET'], name: 'user_logged')]
    public function userLogged(Request $request): JsonResponse
    {

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

        }
        return new JsonResponse($data2);
    }



    #[Route('/sugerencia', methods:['POST'], name: 'sugerir_profile')]
    public function sugerirProfile(Request $request): JsonResponse{

        $data = json_decode($request->getContent(),true);

        $user = $data['username'];

        $misPerfiles = $this->userProfileRepository->sugerirProfile($user);

        if(!$misPerfiles){
            throw new NotFoundHttpException('No coincide ningún perfil.');
        }


        foreach($misPerfiles as $miProfile){
            $data2[] = [
                'name' => $miProfile->getName(),
                'bio' => $miProfile->getBio(),
                'website_url' => $miProfile->getWebsiteUrl(),
                'username' => $miProfile->getTwitterUsername(),
                'company' => $miProfile->getCompany(),
                'direccion' => $miProfile->getLocation(),
                'fecha' => $miProfile->getDateOfBirth(),
                'phone_number' => $miProfile->getPhoneNumber(),
            ];
        }
        
        return new JsonResponse(['userProfile' => $data2], Response::HTTP_OK);
    }



}
