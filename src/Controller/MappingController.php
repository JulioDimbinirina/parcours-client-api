<?php

namespace App\Controller;

use App\Entity\MappingClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MappingClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @Route("/api")
 */
class MappingController extends AbstractController
{
  /**
     * @Route("/mapping/client", name="mapping", methods={"GET"})
     */
    public function getAll(MappingClientRepository $repo): Response
    {
       try {
           return $this->json($repo->findAll(), 200, [], ['groups' => ['mapping']]);
       } catch (\Exception $e) {
           return $this->json([
               "status" => 500,
               "message" => $e->getMessage()
           ], 500);
       }
    }

    /**
     * @Route("/save-mapping", name="create_mapp", methods={"POST"})
     */
    public function save(Request $request, EntityManagerInterface $em, 
    SerializerInterface $serializer): Response 
    {
        try {
            $jsonRecu = $request->getContent();
            if (!empty($jsonRecu)) {
                $mappingClient = $serializer->deserialize($jsonRecu, MappingClient::class, 'json');
                $em->persist($mappingClient);
                $em->flush();
            }

            return $this->json($mappingClient, 201, [], ['groups' => ['mapping']]);
           
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/update-mapping/{id}", name="modif_map", methods={"PUT"})
     */
    public function update(int $id, Request $request, EntityManagerInterface $em, 
    MappingClientRepository $repo): Response 
    {
        try {
            $mappingClient = $repo->find($id);
            $jsonRecu = json_decode($request->getContent(), true);
            if (!empty($jsonRecu)) {
                $mappingClient->setLibelle($jsonRecu['libelle']);
                $em->persist($mappingClient);
                $em->flush();
            }

            return $this->json($mappingClient, 200, [], ['groups' => ['mapping']]);

        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/delete-mapping/{id}", name="supp_mapp", methods={"DELETE"})
     */
    public function delete(int $id, MappingClientRepository $repo, EntityManagerInterface $em): Response {
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
