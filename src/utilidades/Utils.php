<?php

namespace App\utilidades;

use App\Entity\User;
use Google_Client;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
/* require_once '../../vendor/autoload.php'; */

class Utils
{

    private $jwtEncoder;

            public function __construct(JWTEncoderInterface $jwtEncoder,
                                         )
        {
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


    public function verifyGoogle($id_token){
        $client = new Google_Client(['client_id' => $id_token]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken('654622771453-jf22r6uopircg7fe0221dsd6kbjn5k60.apps.googleusercontent.com');
        
        return $payload;
       
    }

}