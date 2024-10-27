<?php

namespace App\Http\Controllers;

use App\Http\Route;

#[Controller]
class ExampleController
{

    #[Route('/api/v1/example/{id}/{name}', 'GET')]
    public  function  index(int $id, string $name)
    {
        echo "$id: Hello $name";
    }

    #[Route('/', 'GET')]
    public  function  home()
    {
        echo "Hello Welcome To Home!!!!";
    }
}
