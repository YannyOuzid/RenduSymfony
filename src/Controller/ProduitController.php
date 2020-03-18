<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Form\PanierType;
use App\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="produit")
     */
    public function index(Request $request, TranslatorInterface $translator)
    {

        $pdo = $this->getDoctrine()->getManager();

        
        $produit = new Produit();

        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $fichier = $form->get('photoUpload')->getData();

            if($fichier){
                $nomFichier = uniqid() . '.' . $fichier->guessExtension();

                try{
                    //On essaie de deplacer le fichier
                    $fichier->move(
                    $this->getParameter('upload_dir'),
                    $nomFichier
                    );
                }
                catch(FileException $e){
                    $this->addFlash('danger', "Impossible d'uploder le fichier");
                    return $this->redirecttoRoute('home');

                }

                $produit->setPhoto($nomFichier);
            }
            $pdo->persist($produit);
            $pdo->flush();
            $this->addFlash("success", $translator->trans( "produit.added" ));
        }

        $produits = $pdo->getRepository(Produit::class)->findAll();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'form_ajout' => $form->createView(),
        ]);
    }

        /**
     * @Route("/produit/{id}", name="fiche_produit")
     */

    public function produit($id, Produit $produit=null, Request $request, TranslatorInterface $translator){

        $pdo = $this->getDoctrine()->getManager();

        
        $panier = new Panier();

        $produit = $this->getDoctrine()
        ->getRepository(Produit::class)
        ->find($id);


        $form = $this->createForm(PanierType::class, $panier);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $panier = $form->getData();
            $panier->setProduit($produit);
            $pdo->persist($panier);
            $pdo->flush();
            $this->addFlash("success", $translator->trans( "produit.added" ));
        }

        $paniers = $pdo->getRepository(Panier::class)->findAll();


        return $this->render('produit/produit.html.twig', [
            'paniers' => $paniers,
            'form_ajout' => $form->createView(),
            'produit' => $produit,
        ]);

        }

        /**
     * @Route ("produit/delete/{id}", name="delete_produit")
     */

    public function delete(Produit $produit=null, TranslatorInterface $translator){

        if($produit !=null){

            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($produit);
            $produit->supprphoto();
            $pdo->flush();
            $this->addFlash("success", $translator->trans( "produit.delete" ));
        }
        return $this->redirectToRoute('produit');
    }
    
}
