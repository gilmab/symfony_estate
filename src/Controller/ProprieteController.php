<?php 

namespace App\Controller ; 

use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Property ;
use App\Repository\PropertyRepository ;


class ProprieteController extends AbstractController {

    private $repository ;

    public function __construct(PropertyRepository $repository){
                $this->repository = $repository ; 
    }


    /**
     * @Route("/biens", name="proriete.index")
     * @return Response 
     */

    public function index () : Response {

       $property = $this->repository->findLatest() ;
        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties'
        ]);

    }
      //---- Ici on a une route qui reçoit les paramètres depuis home:tml.twig les paramètres slug et id 
    /**
     * @Route("/biens/{slug}-{id}", name="proriete.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Property $property
     * @return Response 
     */
           
    // Au niveau de la methode on récupère le slug et l'id on utilise ici une injection de l'entite Property et symfony se charge de faire la jonction pour nous 
    public function show(Property $property, string $slug): Response {
        // Ici on compare le slug contenu dans l'entité property et le slug renvoyé depuis le home.html.twig
        if($property->getSlug() !== $slug){
            // Si le slug n'existe pas on redirige vers la meme route grace au property slug
            $this->redirectToRoute( 'proriete.show', [
                'id' =>$property->getId(),
                'slug' => $property->getSlug()
            ], 301) ;
        }
        
        // Ici on envoie dans la page current menu utilise pour la navbar et l'element property pour avoir les donnée sur les propriété
         return $this->render('property/show.html.twig', [
                'property' => $property,
               'current_menu' => 'properties'
         ]) ; 
    }
} 