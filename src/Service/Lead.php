<?php
namespace App\Service;

use App\Entity\StatusLead;
use App\Entity\WorkflowLead;
use App\Repository\BdcRepository;
use App\Repository\StatusLeadRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class Lead{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var BdcRepository
     */
    private $bdcRepo;

    /**
     * @var StatusLeadRepository
     */
    private $statusLeadRepo;

    /**
     * @param EntityManagerInterface $manager
     * @param BdcRepository $bdcRepo
     * @param StatusLeadRepository $statusLeadRepo
     */
    public function __construct(EntityManagerInterface $manager, BdcRepository $bdcRepo, StatusLeadRepository $statusLeadRepo)
    {
        $this->manager = $manager;
        $this->bdcRepo = $bdcRepo;
        $this->statusLeadRepo = $statusLeadRepo;
    }

    public function addWorkflowLead($customer, $status){
            $workflow = new WorkflowLead();
            $workflow->setClient($customer)
                ->setStatut($status)
                ->setDate(new \DateTime());
            $this->manager->persist($workflow);
            $this->manager->flush();
    }

    public function updateStatusLeadByCustomer($customer, $status){
        $customerStatus = $this->statusLeadRepo->findOneBy(['customer' => $customer]);
        if ($customerStatus) {
            $customerStatus->setStatus($status);
            $this->manager->persist($customerStatus);
            $this->manager->flush();
        } else {
            $customerStatus = new StatusLead();
            $customerStatus->setCustomer($customer);
            $customerStatus->setStatus($status);
            $this->manager->persist($customerStatus);
            $this->manager->flush();
        }
    }

    public function updateStatusLeadBdc($idBdc, $status){
        $bdc = $this->bdcRepo->find($idBdc);
        if ($bdc) {
            $bdc->setStatutLead($status);
            $this->manager->persist($bdc);
            $this->manager->flush();
        }
    }
}