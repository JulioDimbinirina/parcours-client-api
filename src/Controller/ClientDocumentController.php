<?php

namespace App\Controller;

use App\Repository\BdcDocumentRepository;
use App\Repository\ClientDocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ClientDocumentController extends AbstractController
{
    private $clientDocumentRepository;

    private $bdcDocumentRepository;

    public function __construct(ClientDocumentRepository $clientDocumentRepository, BdcDocumentRepository $bdcDocumentRepository)
    {
        $this->clientDocumentRepository = $clientDocumentRepository;
        $this->bdcDocumentRepository = $bdcDocumentRepository;
    }

    /**
     * @Route("/document/for/one/customer/{id}", name="document_for_one_customer", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function getDocumentForOneCustomer(int $id = null): Response
    {
        try {
            $clientsDocuments = $this->clientDocumentRepository->findBy([
                'customer' => $id
            ]);

            $bdcsDocuments = $this->bdcDocumentRepository->getBdcDocumentForThisCustomer($id);

            if (count($clientsDocuments) > 0 || count($bdcsDocuments) > 0) {
                $documents = array_merge($clientsDocuments, $bdcsDocuments);
                return $this->json($documents, 200, [], ['groups' => ['document']]);
            } else {
                return $this->json("Auccun document correspond à ce client", 200, [], ['groups' => ['document']]);
            }
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
