<?php

namespace App\Controller;

date_default_timezone_set('UTC');

use App\Entity\MensajePersonalizado;
use App\Entity\Messages;
use App\Entity\PersonalizarMensaje;
use App\Repository\MessagesRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use DateTime;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Message;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ChatController extends AbstractController
{

    private $userRepository;
    private $userProfileRepository;
    private $jwtEncoder;
    private $messagesRepository;


    public function __construct(UserRepository              $userRepository,
                                UserProfileRepository       $userProfileRepository,
                                JWTEncoderInterface         $jwtEncoder,
                                MessagesRepository $messagesRepository)
    {
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
        $this->jwtEncoder = $jwtEncoder;
        $this->messagesRepository = $messagesRepository;
    }


    #[Route('/message/create', methods:['POST'], name: 'message_create')]
    public function createMessage(Request $request): JsonResponse
    {

        $token = $request->headers->get('Authorization');
        if ($token) {
            $data = $this->jwtEncoder->decode($token);
        
            $data2 =  $data['username'];
        }

        $data3 = json_decode($request->getContent(), true);
        $textoMensaje = $data3['texto'];
        $fecha = $data3['creation_date'];
        $miFecha = date("d-m-Y H:i:s", strtotime($fecha));

        $otraFecha = new DateTime($miFecha);


        $usernameReceptor = $this->userProfileRepository->findOneBy(['twitterUsername' => $data3['usernameReceptor']]);
        $userReceptor = $usernameReceptor->getUser();

        $prueba = $this->userRepository->findOneBy(['email' => $data2]);
        $obtenerUsername = $prueba->getUserProfile()->getTwitterUsername();
        $usernameEmisor = $this->userProfileRepository->findOneBy(['twitterUsername' => $obtenerUsername]);
        $userEmisor = $usernameEmisor->getUser();

        $newMessage = new Messages();

        $newMessage
            ->setTexto($textoMensaje)
            ->setUsuarioEmisor($userEmisor)
            ->setUsuarioReceptor($userReceptor)
            ->setCreationDate($otraFecha);

        $this->messagesRepository->save($newMessage, true);


        return new JsonResponse(['status' => 'Mensaje Creado!'], Response::HTTP_CREATED);
    }



    #[Route('message/listarMessages', name:'app_listar_messages', methods:['POST'])]
    public function listarMessages(Request $request): JsonResponse{


        $token = $request->headers->get('Authorization');
        if ($token) {
            $data = $this->jwtEncoder->decode($token);
        
            $data2 =  $data['username'];
        }

        $data3 = json_decode($request->getContent(), true);

        $usernameReceptor = $this->userProfileRepository->findOneBy(['twitterUsername' => $data3['usernameReceptor']]);
        $userReceptor = $usernameReceptor->getUser();

        $prueba = $this->userRepository->findOneBy(['email' => $data2]);
        $obtenerUsername = $prueba->getUserProfile()->getTwitterUsername();
        $usernameEmisor = $this->userProfileRepository->findOneBy(['twitterUsername' => $obtenerUsername]);
        $userEmisor = $usernameEmisor->getUser();

        $miLista = $this->messagesRepository->listarMensajes($userEmisor,$userReceptor);

        $datosMensajes= [];
        foreach($miLista as $array){
       
            $datosMensajes[] = $array;
        }
        return new JsonResponse(['listaMensajes' => $datosMensajes], Response::HTTP_CREATED);
    }


    #[Route('message/listarMessagesMios', name:'app_listar_messagesmios', methods:['POST'])]
    public function listarMessagesMios(Request $request): JsonResponse{


        $token = $request->headers->get('Authorization');
        if ($token) {
            $data = $this->jwtEncoder->decode($token);
        
            $data2 =  $data['username'];
        }

        $data3 = json_decode($request->getContent(), true);

        $usernameReceptor = $this->userProfileRepository->findOneBy(['twitterUsername' => $data3['usernameReceptor']]);
        $userReceptor = $usernameReceptor->getUser();

        $prueba = $this->userRepository->findOneBy(['email' => $data2]);
        $obtenerUsername = $prueba->getUserProfile()->getTwitterUsername();
        $usernameEmisor = $this->userProfileRepository->findOneBy(['twitterUsername' => $obtenerUsername]);
        $userEmisor = $usernameEmisor->getUser();

        $miLista = $this->messagesRepository->listarMensajesMios($userEmisor,$userReceptor);

        $datosMensajes= [];
        foreach($miLista as $array){
       
            $datosMensajes[] = $array;
        }
        return new JsonResponse(['listaMensajes' => $miLista], Response::HTTP_CREATED);
    }

}
