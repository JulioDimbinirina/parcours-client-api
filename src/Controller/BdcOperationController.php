<?php

namespace App\Controller;

use App\Entity\HausseIndiceSyntecClient;
use App\Repository\BdcOperationRepository;
use App\Repository\BdcRepository;
use App\Repository\HausseIndiceSyntecClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class BdcOperationController extends AbstractController
{
    /**
     * @Route("/get/some/bdcoperation/{id}", name="some_bdcoperation", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function getSomeBdcOperation(int $id, BdcOperationRepository $bdcOperationRepository): Response
    {
        try {
            $bdcsOperations = $bdcOperationRepository->findBy([
                "bdc" => $id
            ]);
            return $this->json($bdcsOperations, 200, [], ['groups' => ['bdc-operation']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    
    /**
     * @Route("/get/test/hausse", name="hausse", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function testHausse(HausseIndiceSyntecClientRepository $repoHausseClient) :Response {
        try {
            $ok=$repoHausseClient->getHausseByCustomerYearsCurent(16,"2023-01-01");
            dd($ok);
            return $this->json("okok", 200, [], ['groups' => ['bdc-operation']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/get/one/bdcoperation/{id}", name="one_bdcoperation", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function getOneBdcOperation(int $id, BdcOperationRepository $bdcOperationRepository): Response
    {
        try {
            $bdcsOperation = $bdcOperationRepository->find($id);

            if ($bdcsOperation){
                return $this->json($bdcsOperation, 200, [], []);
            }

            return $this->json("Ligne facturation not found", 200, [], []);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
