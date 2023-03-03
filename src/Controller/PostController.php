<?php
namespace App\Controller;


use App\Entity\Dislike;
use App\Entity\Followers;
use App\Entity\Megusta;
use App\Entity\Post;
use App\Entity\Relio;
use App\Repository\FollowersRepository;
use App\Repository\MegustaRepository;
use App\Repository\PostRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Repository\DislikeRepository;
use App\Repository\RelioRepository;
use App\utilidades\Utils;
use DateTime;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController

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


    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }
    //Visualizar todas las publicaciones de tus seguidores.
    #[Route('/post/user',  name: 'app_post_user' ,methods:['POST'])]
    public function post_user(DislikeRepository $dislikeRepository,RelioRepository $relioRepository,MegustaRepository $likeRepository,PostRepository $postRepository,FollowersRepository $followersRepository,UserRepository $userRepository,UserProfileRepository $userProfileRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $user = $userRepository->findOneBy(['email' => $data]);
        $lista = $followersRepository->findBy(['id_emisor' => $user]);
        $data2 = [];
        if ($lista!= null){
            foreach ($lista as $a) {
                $listPost = $postRepository ->findPostOrder($a->getIdReceptor()->getId());

                foreach($listPost as $array){
                    $user1 = $userRepository -> findOneBy(['id' =>$array->getIdUser()]);
                    $like = $likeRepository->findPorLikeUser($array);
                    $dislike = $dislikeRepository->findPorDislikeUser($array);
                    $relio = $relioRepository->findPorRelioeUser($array);
                    if(empty($like)){
                        $like=0;
                    }else{
                        $like = $like[0]['veces'];
                    }
                    if(empty($dislike)){
                        $dislike=0;
                    }else{
                        $dislike = $dislike[0]['veces'];
                    }
                    if(empty($relio)){
                        $relio=0;
                    }else{
                        $relio = $relio[0]['veces'];
                    }

                    $data2[] = [
                        'id' => $array->getId(),
                        'username' => $user1->getUserProfile()->getTwitterUsername(),
                        'pais' => $user1->getUserProfile()->getLocation(),
                        'message' => $array->getMessage(),
                        'image' => $array->getImage(),
                        'publication' => $array->getPublicationDate(),
                        'like' => $like,
                        'dislike' => $dislike,
                        'relio' => $relio
                    ];
                }
            }
            }else{

            $listaLike = $likeRepository -> findPorLike();
            foreach ($listaLike as $array){
                $listaConMasLike = $postRepository -> findOneBy(['id' =>$array[0]->getIdPost()]);
                $user1 = $userRepository -> findOneBy(['id' =>$listaConMasLike->getIdUser()]);
                $like= $array['veces'];
                $data2[] = [
                    'id' => $listaConMasLike->getId(),
                    'username' => $user1->getUserProfile()->getTwitterUsername(),
                    'pais' => $user1->getUserProfile()->getLocation(),
                    'message' => $listaConMasLike->getMessage(),
                    'image' => $listaConMasLike -> getImage(),
                    'publication' => $listaConMasLike->getPublicationDate(),
                    'like'=>$like
                ];
            }
        }

        return new JsonResponse(['userPosts' => $data2], Response::HTTP_OK);
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
            ->setPublicationDate($date)
            ->setIdUser($userRepository->findOneBy(['email'=>$data]));
        $postRepository->save($newPost, true);

        return new JsonResponse(['status' => 'Post Creado'], Response::HTTP_CREATED);
    }
    //Visualizar las publicaciones del usuario.
    #[Route('/post/user/list',  name: 'app_user_list' ,methods:['POST'])]
    public function post_user_list(DislikeRepository $dislikeRepository,MegustaRepository $megustaRepository,RelioRepository $relioRepository,PostRepository $postRepository,UserRepository $userRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $user = $userRepository->findOneBy(['email' => $data]);
//        $listProfile = $followersRepository->findOneBy(['id_receptor' => $user]);
        $listPost = $postRepository ->findPostOrder($user);
        $data2= [];
        foreach($listPost as $array){
            $like = $megustaRepository->findPorLikeUser($array);
            $dislike = $dislikeRepository->findPorDislikeUser($array);
            $relio = $relioRepository->findPorRelioeUser($array);
            if(empty($like)){
                $like=0;
            }else{
                $like = $like[0]['veces'];
            }
            if(empty($dislike)){
                $dislike=0;
            }else{
                $dislike = $dislike[0]['veces'];
            }
            if(empty($relio)){
                $relio=0;
            }else{
                $relio = $relio[0]['veces'];
            }

            $data2[] = [
                'id' => $array->getId(),
                'message' => $array->getMessage(),
                'image' => $array->getImage(),
                'publication' => $array->getPublicationDate(),
                'like'=>$like,
                 'dislike' => $dislike,
                'relio' =>$relio
            ];
        }
        return new JsonResponse(['userPosts' => $data2], Response::HTTP_OK);
    }
    //Borrar publicación.
    #[Route('/post/delete',  name: 'app_delete_post' ,methods:['DELETE'])]
    public function post_delete(PostRepository $postRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $post = $postRepository->findOneBy(['id' => $data]);
        if ($post==null){
            return new JsonResponse(['status' => 'No existe publicacón'], Response::HTTP_BAD_REQUEST);
        }else{
            $postRepository->remove($post,true);
            return new JsonResponse(['status' => 'Post Eliminado'], Response::HTTP_CREATED);
        }
    }
    #[Route('/post/update',  name: 'app_update_post' ,methods:['PUT'])]
    public function post_update(PostRepository $postRepository,Utils $utilidades,UserRepository $userRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $data2 = $postRepository->findOneBy(['id'=>$data]);
        $publication = $data['publication_date'];
        $date = new DateTime($publication);

        if($data2 == null){
            return new JsonResponse(['status' => 'No existe publicacón'], Response::HTTP_CREATED);
        }else{
            $data2->setMessage($data['message']);
            $data2->setImage($data['image']);
            $data2->setPublicationDate($date);

        }

        $postRepository->save($data2, true);

        return new JsonResponse(['status' => 'Post Actualizado'], Response::HTTP_CREATED);
    }

    #[Route('/post/addlike', name:'app_post_añadir_like', methods:['POST'])]
    public  function addPostLike(Utils $utils, Request $request, MegustaRepository $likeRepository, UserRepository $userRepository, PostRepository $postRepository): JsonResponse{

        $data = json_decode($request->getContent(), true);
        $userToken = $utils->obtenerUsuarioToken($request);

        $userP = $this->userRepository->findOneBy(['email' =>$userToken->getEmail()]);

        $idPost = $data['id_post'];

        $Post = $postRepository->findOneBy(['id' => (int)$idPost]);

        $likeComprobar = $likeRepository->findIdLike($Post,$userP);



        if (!empty($likeComprobar)){
            $likeEliminar = $likeRepository->findOneBy(['id'=> $likeComprobar[0]['id']]);
            $likeRepository->removeLike($likeEliminar);
            return new JsonResponse(['resultado' => 'Like Eliminado!'], Response::HTTP_CREATED);
        }else{

        $newlike = new Megusta();

        $newlike->setIdPost($Post);
        $newlike->setIdUser($userP);


        $likeRepository->addLike($newlike);

            return new JsonResponse(['resultado' => 'Like Creado!'], Response::HTTP_CREATED);

            }






    }

    #[Route('/post/addDislike', name:'app_post_añadir_dislike', methods:['POST'])]
    public  function addPostDislike(Utils $utils, Request $request, DislikeRepository $dislikeRepository, UserRepository $userRepository, PostRepository $postRepository): JsonResponse{


        $data = json_decode($request->getContent(),true);
        $userToken = $utils->obtenerUsuarioToken($request);

        $userP = $this->userRepository->findOneBy(['email' =>$userToken->getEmail()]);

        $idPost = $data['id_post'];

        $Post = $postRepository->findOneBy(['id' => (int)$idPost]);

        $dislikeComprobar = $dislikeRepository->findIdDislike($Post,$userP);



        if (!empty($dislikeComprobar)){
            $dislikeEliminar = $dislikeRepository->findOneBy(['id'=> $dislikeComprobar[0]['id']]);
            $dislikeRepository->removeDislike($dislikeEliminar);
            return new JsonResponse(['resultado' => 'Dislike Eliminado!'], Response::HTTP_CREATED);
        }else{

            $newDislike = new Dislike();

            $newDislike->setIdPost($Post);
            $newDislike->setIdUser($userP);


            $dislikeRepository->addDislike($newDislike);
            return new JsonResponse(['resultado' => 'Dislike Creado!'], Response::HTTP_CREATED);

        }


    }



    #[Route('/post/addrelio', name:'app_post_añadir_relio', methods:['POST'])]
    public  function addPostRelio(Utils $utils, Request $request, RelioRepository $relioRepository, UserRepository $userRepository, PostRepository $postRepository): JsonResponse{

        $data = json_decode($request->getContent(), true);
        $userToken = $utils->obtenerUsuarioToken($request);

        $userP = $this->userRepository->findOneBy(['email' =>$userToken->getEmail()]);

        $idPost = $data['id_post'];

        $Post = $postRepository->findOneBy(['id' => (int)$idPost]);

        $relioComprobar = $relioRepository->findIdRelio($Post,$userP);



        if (!empty($relioComprobar)){
            $relioEliminar = $relioRepository->findOneBy(['id'=> $relioComprobar[0]['id']]);
            $relioRepository->removeRelio($relioEliminar);
            return new JsonResponse(['resultado' => 'Relio eliminado!'], Response::HTTP_CREATED);
        }else{

            $newRelio = new Relio();

            $newRelio->setIdPost($Post);
            $newRelio->setIdUser($userP);


            $relioRepository->addRelio($newRelio);

            return new JsonResponse(['resultado' => 'Relio Creado!'], Response::HTTP_CREATED);

        }






    }

    #[Route('/post/mostrarRelio', name:'app_post_mostrar_relio', methods:['POST'])]
    public  function showRelio(Utils $utils, Request $request, RelioRepository $relioRepository, UserRepository $userRepository, PostRepository $postRepository): JsonResponse{



        $userToken = $utils->obtenerUsuarioToken($request);

        $userP = $this->userRepository->findOneBy(['email' =>$userToken->getEmail()]);

        $relio = $relioRepository ->findBy(['id_user' => $userP->getId()]);

        foreach($relio as $array) {
            $listaPost = $postRepository->findBy(['id' => $array->getIdPost()]);

            $data2[] = [
                'id_user' => $listaPost[0]->getIdUser()->getUserProfile()->getName(),
                'mensaje' => $listaPost[0]->getMessage(),
                'imagen' => $listaPost[0]->getImage(),
                'fecha_Publicación' => $listaPost[0]->getPublicationDate(),
            ];

        }

            return new JsonResponse(['publicacion' => $data2], Response::HTTP_OK);


        }




}
