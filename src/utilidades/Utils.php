<?php

namespace App\utilidades;

use App\Entity\User;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Utils
{

    private $jwtEncoder;
    private $userRepository;
    private $userProfileRepository;
    private $userPasswordHasher;


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


    public function toJson($data): string
    {
        //Inicialización de serializador
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        //Conversion a JSON
        $json = $serializer->serialize($data, 'json');

        return $json;
    }


    public function obtenerUsuarioToken(Request $request): User
    {


        // Obtener el token JWT del encabezado de la solicitud

        $token = $request->headers->get('Authorization');
        if ($token) {
            // Decodificar el token JWT y obtener el contenido
            $data = $this->jwtEncoder->decode($token);

            $usuario = new User();

            $usuario->setEmail($data['username']);


        }


        return $usuario;
    }
}