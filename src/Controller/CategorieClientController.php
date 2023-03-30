<?php

namespace App\Controller;

use App\Entity\CategorieClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @Route("/api")
 */
class CategorieClientController extends AbstractController
{
    /**
     * @Route("/categorie/client", name="categorie_client", methods={"GET"})
     */
    public function getAll(CategorieClientRepository $repo): Response
    {
       try {
           return $this->json($repo->findAll(), 200, [], ['groups' =>['categorie']]);
       } catch (\Exception $e) {
           return $this->json([
               "status" => 500,
               "message" => $e->getMessage()
           ], 500);
       }
    }

    /**
     * @Route("/save-categorie", name="create_cat", methods={"POST"})
     */
    public function saveCategorie(Request $request, EntityManagerInterface $em, 
    SerializerInterface $serializer): Response 
    {
        try {
            $jsonRecu = $request->getContent();
            if (!empty($jsonRecu)) {
                $categorieClient = $serializer->deserialize($jsonRecu, CategorieClient::class, 'json');
                $em->persist($categorieClient);
                $em->flush();
            }

            return $this->json($categorieClient, 201, [], ['groups' => ['categorie']]);
           
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/update-categorie/{id}", name="modif_cat", methods={"PUT"})
     */
    public function updateCategorie(int $id, Request $request, EntityManagerInterface $em, 
    CategorieClientRepository $repo): Response 
    {
        try {
            $categorieClient = $repo->find($id);
            $jsonRecu = json_decode($request->getContent(), true);
            if (!empty($jsonRecu)) {
                $categorieClient->setLibelle($jsonRecu['libelle']);
                $em->persist($categorieClient);
                $em->flush();
            }

            return $this->json($categorieClient, 200, [], ['groups' => ['categorie']]);

        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/delete-categorie/{id}", name="supp_cat", methods={"DELETE"})
     */
    public function delete(int $id, CategorieClientRepository $repo, EntityManagerInterface $em): Response {
        try {
            $dataSupp = $repo->find($id);
            $em->remove($dataSupp);
            $em->flush();

            return $this->json(["status"=> 200, "message" => "resource deleted successfully !"], 200);
            
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }
}
