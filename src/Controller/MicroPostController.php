<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MicroPostController extends AbstractController
{

    private $microPostRepository;

    public function __construct(MicroPostRepository $microPostRepository)
    {
        $this->microPostRepository = $microPostRepository;
    }


    #[Route('/micro/post', name: 'app_micro_post')]
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
