<?php 

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response ; 
use Twig\Environment;
use App\Repository\PropertyRepository ;
use App\Entity\Property ; 


class HomeController {

    /**
     * @var Environment
     */

     private $twig;

     public function __construct(Environment $twig)
     {
         $this->twig = $twig;
     }

    public function index(PropertyRepository $repository): Response
     {
        $properties = $repository->findLatest() ; 

        $content = $this->twig->render(
            'pages/home.html.twig',
            [
                'properties' => $properties
            ]
        );

        return new Response($content);
              
    }
}