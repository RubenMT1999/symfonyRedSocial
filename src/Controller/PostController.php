<?php
namespace App\Controller;

use App\Entity\Followers;
use App\Entity\Post;
use App\Repository\FollowersRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\utilidades\Utils;
use Cassandra\Date;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }
    //Visualizar todos las publicaciones de tus seguidores.
    #[Route('/post/user',  name: 'app_post_user' ,methods:['GET'])]
    public function post_user(PostRepository $postRepository,FollowersRepository $followersRepository,UserRepository $userRepository,Utils $utilidades, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $user = $userRepository->findOneBy(['email' => $data]);
        $listProfile = $followersRepository->findOneBy(['id_receptor' => $user]);
        $listPost = $postRepository ->findPostOrder($listProfile);
        foreach($listPost as $array){
            $data2[] = [
                'message' => $array->getMessage(),
                'image' => $array->getImage(),
                'relio' => $array->getRelio(),
                'publication' => $array->getPublicationDate()
            ];
        }
        $listJson = $utilidades -> toJson($data2);
        return new JsonResponse($listJson, 200,[], true);
    }
    //Crear post de un usuario.
    #[Route('/post/create',  name: 'post_create' ,methods:['POST'])]
    public function post_create(PostRepository $postRepository,UserRepository $userRepository, Request $request): JsonResponse
    {
        $newPost= new Post;
        $data = json_decode($request->getContent(),true);
        $publication = $data['publication_date'];
        $date = new DateTime($publication);

        $newPost
            ->setMessage($data['message'])
            ->setImage($data['image'])
            ->setRelio($data['relio'])
            ->setPublicationDate($date)
            ->setIdUser($userRepository->findOneBy(['email'=>$data]));
        $postRepository->save($newPost, true);

        return new JsonResponse(['status' => 'Post Creado'], Response::HTTP_CREATED);
    }
    //Visualizar las publicaciones del usuario.
    #[Route('/post/user/list',  name: 'app_post_user_list' ,methods:['GET'])]
    public function post_user_list(PostRepository $postRepository,UserRepository $userRepository,Utils $utilidades, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $user = $userRepository->findOneBy(['email' => $data]);
//        $listProfile = $followersRepository->findOneBy(['id_receptor' => $user]);
        $listPost = $postRepository ->findPostOrder($user);
        $data2 = [];
        foreach($listPost as $array){
            $data2[] = [
                'message' => $array->getMessage(),
                'image' => $array->getImage(),
                'relio' => $array->getRelio(),
                'publication' => $array->getPublicationDate()
            ];
        }
        $listJson = $utilidades -> toJson($data2);
        return new JsonResponse($listJson, 200,[], true);
    }
    //Borrar publicación.
    #[Route('/post/delete',  name: 'app_delete_post' ,methods:['DELETE'])]
    public function post_delete(PostRepository $postRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $post = $postRepository->findOneBy(['id' => $data]);
        if ($post==null){
            return new JsonResponse(['status' => 'No existe publicacón'], Response::HTTP_CREATED);
        }else{
            $postRepository->remove($post,true);
            return new JsonResponse(['status' => 'Post Eliminado'], Response::HTTP_CREATED);
        }
    }
    #[Route('/post/update',  name: 'app_update_post' ,methods:['PUT'])]
    public function post_update(PostRepository $postRepository,Utils $utilidades,UserRepository $userRepository, Request $request): JsonResponse
    {
        $newPost= new Post;
        $data = json_decode($request->getContent(),true);
        $data2 = $postRepository->findOneBy(['id'=>$data]);
        $publication = $data['publication_date'];
        $date = new DateTime($publication);

        if($data2 == null){
            return new JsonResponse(['status' => 'No existe publicacón'], Response::HTTP_CREATED);
        }else{
            $data2->setMessage($data['message']);
            $data2->setImage($data['image']);
            $data2->setRelio($data['relio']);
            $data2->setPublicationDate($date);

        }

        $postRepository->save($data2, true);

        return new JsonResponse(['status' => 'Post Actualizado'], Response::HTTP_CREATED);
    }


}
