<?php

namespace App\Controller;

use App\Repository\BdcRepository;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SaisieActeController extends AbstractController
{
    /**
     * @Route ("/find/all/customers", name="find_all_customers", methods={"GET"})
     * @param CustomerRepository $customerRepository
     * @return Response
     */
    public function getAllCustomers(CustomerRepository $customerRepository): Response {
        try {
            $result = $customerRepository->findAll();
            return $this->json($result, 200, [], ['groups' => ['saisie-acte']]);
        } catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @Route ("/find/bdc/en/prod", name="find_bdc_en_prod", methods={"GET"})
     * @param BdcRepository $bdcRepository
     * @return Response
     */
    public function getOperations(BdcRepository $bdcRepository): Response {
        try {
            $statutLeadProd = $this->getParameter('statut_lead_bdc_signe_client');
            return $this->json($bdcRepository->getBdcEnProduction($statutLeadProd), 200, [], ['groups' => ['get-by-bdc']]);
        } catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }
}
