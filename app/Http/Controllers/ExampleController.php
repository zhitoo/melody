<?php
namespace App\Http\Controllers;
use App\Http\Route;

#[Controller]
class ExampleController
{

    #[Route('/api/v1/example/{id?}/{hello?}', 'GET')]
    public  function  index()
    {
        echo "Hello World!";
    }

    #[Route('/', 'GET')]
    public  function  home()
    {
        echo "Hello Welcome To Home!!!!";
    }

}