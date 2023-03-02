<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Post;
use App\Repository\CommentsRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\utilidades\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{
    #[Route('/comments', name: 'app_comments')]
    public function index(): Response
    {
        return $this->render('comments/index.html.twig', [
            'controller_name' => 'CommentsController',
        ]);
    }
    #[Route('/comments/create',  name: 'app_comments_create' ,methods:['POST'])]
    public function comments_create(PostRepository $postRepository,UserRepository $userRepository,CommentsRepository $commentsRepository, Request $request): JsonResponse
    {
        $newComments = new comments;
        $data = json_decode($request->getContent(),true);

        $newComments
            ->setText($data['text'])
            ->setIdPost($postRepository->findOneBy(['id'=>$data]))
            ->setIdUser($userRepository->findOneBy(['email'=>$data]))
            ->setDateComments($data['date_comments']);
        $commentsRepository->save($newComments, true);

        return new JsonResponse(['status' => 'Comentario creado'], Response::HTTP_CREATED);
    }
    #[Route('/comments/delete',  name: 'comments_delete' ,methods:['DELETE'])]
    public function comments_delete(CommentsRepository $commentsRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $post = $commentsRepository->findOneBy(['id' => $data]);
        if ($post==null){
            return new JsonResponse(['status' => 'No existe comentario'], Response::HTTP_CREATED);
        }else{
            $commentsRepository->remove($post,true);
            return new JsonResponse(['status' => 'Comentario Eliminado'], Response::HTTP_CREATED);
        }
    }
    #[Route('/comments/update',  name: 'comments_update' ,methods:['PUT'])]
    public function comments_update(PostRepository $postRepository,UserRepository $userRepository,CommentsRepository $commentsRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $data2 = $commentsRepository->findOneBy(['id'=>$data]);
        $id_post = $postRepository->findOneBy(['id'=>$data]);
        $id_user = $userRepository->findOneBy(['email'=>$data]);

        if($data2 == null){
            return new JsonResponse(['status' => 'No existe comentario'], Response::HTTP_CREATED);
        }else{
            $data2->setText($data['text']);
            $data2->setIdPost($id_post);
            $data2->setIdUser($id_user);
        }

        $commentsRepository->save($data2, true);

        return new JsonResponse(['status' => 'Comentario Actualizado'], Response::HTTP_CREATED);
    }

    #[Route('/comments/post',  name: 'comments_post' ,methods:['POST'])]
    public function comments_post(UserRepository $userRepository, CommentsRepository $commentsRepository, Utils $utilidades, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $listPost = $commentsRepository->findCommentsOrder($data);

        $data2 = [];
        foreach($listPost as $array){
        $id = $userRepository->findOneBy(['id'=>$array->getIdUser()]);
            $data2[] = [
                'text' => $array->getText(),
                'date_comments' => $array->getDateComments(),
                'email' => $id->getUserProfile()->getName(),
                'id_post' => $array->getIdPost()->getId()
            ];



        }

        return new JsonResponse(['commentsUser' => $data2], Response::HTTP_OK);
    }

    #[Route('/comments/user',  name: 'comments_user' ,methods:['GET'])]
    public function comments_user(UserRepository $userRepository,PostRepository $postRepository, CommentsRepository $commentsRepository, Utils $utilidades, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $id = $userRepository->findOneBy($data);
        $listPost = $commentsRepository->findCommentsUserOrder($id);

        $data2 = [];
        foreach($listPost as $array){
            $id = $postRepository->findOneBy(['id'=>$array->getIdPost()]);
            $data2[] = [
                'text' => $array->getText(),
                'date_comments' => $array->getDateComments(),
                'message' => $id->getMessage()
            ];
        }
        $listJson = $utilidades -> toJson($data2);
        return new JsonResponse($listJson, 200,[], true);
    }

}
