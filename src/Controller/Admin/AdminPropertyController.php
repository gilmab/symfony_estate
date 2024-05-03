<?php
namespace App\Controller\Admin ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\PropertyRepository ;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Property ;
use Symfony\Component\Form\FormTypeInterface ;
use Symfony\Component\HttpFoundation\Request ; 
use App\Form\PropertyType ;
use Doctrine\ORM\EntityManagerInterface ;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;





class AdminPropertyController extends AbstractController {

    /**
     * @var ObjectManager
     */
    private $em ;

    public function __construct(PropertyRepository $repository, EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager ;
        $this->repository = $repository ;
    }


    /**
     * @Route("/admin", name="admin.property.index")
     * @return \Symfony\Component\HttpFoundation\Response ;
     * 
     */

    public function index() {
        $properties = $this->repository->findAll() ;

        return $this->render('admin/property/index.html.twig', compact('properties')) ;
    }
    /**
     * @Route("/admin/property/create", name="admin.property.new")
     */
    public function new(Request $request) {
        $en = $this->getDoctrine()->getManager();

        $property = new Property() ;
        $form = $this->createForm(PropertyType::class, $property) ;

        $form->handleRequest($request) ;
            //on lui demande si le formulaire a été envoyé
            if($form->isSubmitted() && $form->isValid()){
                $en->persist($property) ;
                $en->flush() ; 
                 return $this->redirectToRoute('admin.property.index') ;
            }

            return $this->render('admin/property/new.html.twig', [
                'property' => $property,
                'form' => $form->createView()
            ]) ; 
    }
    /**
     * @Route("/admin/property/edit/{id}", name="admin.property.edit")
     */
    public function edit(Property $property, Request $request,$id){
        $en = $this->getDoctrine()->getManager();

            $form = $this->createForm(PropertyType::class, $property) ; 
               // Ici on demande au formulaire de géré la requete
            $form->handleRequest($request) ;
            //on lui demande si le formulaire a été envoyé
            if($form->isSubmitted() && $form->isValid()){
                
                $en->flush() ; 
                $this->addFlash('success', 'Bien modifie') ; 
                 return $this->redirectToRoute('admin.property.index') ;
            }

            return $this->render('admin/property/edit.html.twig', [
                'property' => $property,
                'form' => $form->createView()
            ]) ; 
    }

    /**
     * @Route("/admin/property/delete/{id}", name="admin.property.delete", methods="DELETE")
     * @param Property $property
     */
        
    public function delete(Property $property, $id, Request $request){
        if($this->isCsrfTokenValid('delete', $request->get('_token'))){
              $en = $this->getDoctrine()->getManager();
              $en->remove($property) ; 
              $en->flush() ; 
        }
       return $this->redirectToRoute('admin.property.index') ;
    }

} 