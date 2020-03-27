<?php

namespace App\Controller;

use App\Entity\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class PanierController extends AbstractController
{
    /**
     * @Route("/", name="panier")
     */
    public function index()
    {

        $pdo = $this->getDoctrine()->getManager();

        $paniers = $pdo->getRepository(Panier::class)->findAll();

        $size = count($paniers);

        return $this->render('panier/index.html.twig', [
            'paniers' => $paniers,
            'size' => $size,
        ]);
    }

         /**
     * @Route ("panier/delete/{id}", name="delete_panier")
     */

    public function delete(Panier $panier=null, TranslatorInterface $translator){

        if($panier !=null){

            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($panier);
            $pdo->flush();
            $this->addFlash("success", $translator->trans( "produit.delete" ));
        }
        return $this->redirectToRoute('panier');
    }
}
