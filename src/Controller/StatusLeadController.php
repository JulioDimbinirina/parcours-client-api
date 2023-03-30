<?php

namespace App\Controller;

use App\Repository\StatusLeadRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class StatusLeadController extends AbstractController
{
    /**
     * @Route("/get/all/statut/lead", name="get_all_statut_lead", methods={"GET"})
     * @return Response
     */
    public function getAllStatutLead(StatusLeadRepository $statusLeadRepository): Response
    {
        try {
            return $this->json($statusLeadRepository->findAll(), 200, [], ['groups' => ['all:ref']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/statut/lead/all/{page}", name="statut_lead_all", methods={"GET"})
     * @return Response
     */
    public function LeadForContact(int $page, StatusLeadRepository $statusLeadRepository, PaginatorInterface $paginator): Response
    {
        try {
            $getAllLead = $statusLeadRepository->findAll();

            $paginateCustomer = $paginator->paginate($getAllLead, $page, 5);

            return $this->json([ count($getAllLead),$paginateCustomer], 200, [], ['groups' => ['status:lead']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/get/statutlead/for/customer/{id}", name="get_statuslead_customer", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function StatusLeadForOneCustomer(int $id, StatusLeadRepository $statusLeadRepository): Response
    {
        $result = $statusLeadRepository->findOneBy([
            'customer' => $id
        ]);

        $leadCustomer = null;
        if(!empty($result)) {
            $leadCustomer = $result->getStatus();
        }

        return $this->json($leadCustomer, 200, [], ['groups' => ['status:lead']]);
    }

}
