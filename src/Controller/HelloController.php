<?php

//composer.json autoload
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{

    private array $messages = [
        "Hello", "Bye", "Hi"
    ];

    // <\d+> indica que el parámetro debe ser un número.
    //limitar el numero de elemento que se muestra del array.
    //empezará por el indice 0 y muestra $limit elementos
    // con ? lo hacemos opcional y el 3 es el valor por defecto.
    #[Route('/{limit<\d+>?3}', name: 'app_index')]
    public function index(int $limit): Response{
        return new Response(implode(',', array_slice($this->messages, 0, $limit)));
    }
    
    #[Route('/messages/{id<\d+>}', name: 'app_show_one')]
    public function showOne($id): Response{
        return new Response($this->messages[$id]);
    }

}
