<?php

namespace App\Controller;

use App\Entity\Bu;
use App\Entity\CategorieClient;
use App\Entity\Contact;
use App\Entity\CoutHoraire;
use App\Entity\Customer;
use App\Entity\DureeTrt;
use App\Entity\FamilleOperation;
use App\Entity\HoraireProduction;
use App\Entity\IndicatorQualitatif;
use App\Entity\IndicatorQuantitatif;
use App\Entity\LangueTrt;
use App\Entity\LeadDetailOperation;
use App\Entity\ObjectifQualitatif;
use App\Entity\ObjectifQuantitatif;
use App\Entity\Operation;
use App\Entity\OriginLead;
use App\Entity\PotentielTransformation;
use App\Entity\ResumeLead;
use App\Entity\Bdc;
use App\Entity\BdcOperation;
use App\Entity\PaysFacturation;
use App\Entity\PaysProduction;
use App\Entity\Tarif;
use App\Entity\TypeFacturation;
use App\Entity\WorkflowLead;
use App\Repository\BdcOperationRepository;
use App\Repository\BdcRepository;
use App\Repository\ContactRepository;
use App\Repository\CustomerRepository;
use App\Repository\DureeTrtRepository;
use App\Repository\FamilleOperationRepository;
use App\Repository\LeadDetailOperationRepository;
use App\Repository\OperationRepository;
use App\Repository\OriginLeadRepository;
use App\Repository\PotentielTransformationRepository;
use App\Repository\ResumeLeadRepository;
use App\Repository\WorkflowLeadRepository;
use App\Service\CreateBonDeCommande;
use App\Service\Lead;
use App\Services\Base64Service;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Class FicheQualificationController
 * @Route("/api")
 */
class FicheQualificationController extends AbstractController
{
    /**
     * @Route("/get/all/resume/lead", name="get_list_of_resume_lead", methods={"GET"})
     */
    public function getListOfResumeLead(ResumeLeadRepository $resumeLeadRepository, UserInterface $user): Response
    {
        $resumeLeads = $resumeLeadRepository->getMyResumeLeads($user->getId());

        if (count($resumeLeads)) {
            return $this->json($resumeLeads, 200, [], ['groups' => 'get-fq-id']);
        } else {
            return $this->json("Vide", 200, [], []);
        }
    }

    /**
     * @Route("/resume/lead", name="resume_lead_create", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function create(Request $request,
                           SerializerInterface $serializer,
                           EntityManagerInterface $em,
                           UserInterface $user,
                           Lead $lead,
                           \Swift_Mailer $mailer,
                            CreateBonDeCommande $createBonDeCommande): Response
    {
        try {
            $resumeLeadArray = json_decode($request->getContent(), true);
            $leadDetailOperationsArray = isset(json_decode($request->getContent(), true)["leadDetailOperations"]) ? json_decode($request->getContent(), true)["leadDetailOperations"] : [];
            $piecesJointesArray = isset(json_decode($request->getContent(), true)["piecesJointes"]) ? json_decode($request->getContent(), true)["piecesJointes"] : [];
            unset($resumeLeadArray['leadDetailOperations']);
            unset($resumeLeadArray['piecesJointes']);

            # Ajout fiche qualification ou resumé du lead ............................................................
            $resumeLead = $serializer->deserialize(json_encode($resumeLeadArray), ResumeLead::class, 'json');
            $resumeLead->setOriginLead(isset($resumeLeadArray['originLead']) ? $this->getDoctrine()->getRepository(OriginLead::class)->find($resumeLeadArray['originLead']) : NULL);
            $resumeLead->setDureeTrt(isset($resumeLeadArray['dureeTrt']) ? $this->getDoctrine()->getRepository(DureeTrt::class)->find($resumeLeadArray['dureeTrt']) : NULL);
            $resumeLead->setPotentielTransformation(isset($resumeLeadArray['potentielTransformation']) ? $this->getDoctrine()->getRepository(PotentielTransformation::class)->find($resumeLeadArray['potentielTransformation']) : NULL);

            $customer = $this->getDoctrine()->getRepository(Customer::class)->find($resumeLeadArray['customer']);

            $resumeLead->setCustomer($customer);
            $resumeLead->setSepContactClient($resumeLeadArray["sepContactClient"] ?? null);

            # Ajout interlocuteur................
            $tabIdContatcs = [];
            if (!empty($resumeLeadArray['interlocuteur'])) {
                foreach ($resumeLeadArray['interlocuteur'] as $item) {
                    $tabIdContatcs[] = $item;
                }
                $resumeLead->setInterlocuteur($tabIdContatcs);
            }

            # upload files.......................................
            if(!empty($piecesJointesArray))
            {
                $base64service = new Base64Service();
                $files = [];
                foreach ($piecesJointesArray as $item) {
                    $arrayTemp = [];
                    $arrayTemp['id'] = uniqid();

                    if(!empty($item['nom']))
                    {
                        $arrayTemp['name'] = $item['nom'];
                    }
                    else
                    {
                        $arrayTemp['name'] = $item['name'];
                    }

                    $arrayTemp['fileName'] = $base64service->convertToFile($item['base64'], $this->getParameter('fiche_qualification_files_dir'), 'FQ_');
                    $files[] = $arrayTemp;
                }
                $resumeLead->setPiecesJointes($files);
            }

            # Necessaire vers le retour au front
            $idOfAllCreatedDevis = [];

            $result = $createBonDeCommande->regroupOperation("paysProduction", $leadDetailOperationsArray);

            if (!empty($result)){
                foreach ($result as $bdcOperationArray){
                    # Creation du nouvel bon de commande
                    $createdBdc = $createBonDeCommande->NewBonCommandeForNewLignFact($resumeLead, $resumeLeadArray, $bdcOperationArray);

                    if ($createdBdc instanceof Bdc){
                        # Creation des lignes de facturations manuelles et Lead Detail Operation
                        $langTrtArray = $createBonDeCommande->createManuelleLignFactAndLeadDetailOperation($createdBdc, $bdcOperationArray);

                        # Ajout opération automatique
                        $createBonDeCommande->ajoutOperationAutomatique($createdBdc, $bdcOperationArray, $langTrtArray);

                        # Necessaire au retour vers le front
                        $idOfAllCreatedDevis[] = $createdBdc->getId();
                    }
                }
            }


            # Ajout ou MAJ statut client dans la table StatutLead
            $lead->updateStatusLeadByCustomer($customer, $this->getParameter('statut_lead_fiche_qualification_creer'));

            # Ajout d'une ligne dans la table WorkflowLead
            $lead->addWorkflowLead($customer, $this->getParameter('statut_lead_fiche_qualification_creer'));

            # save it in database..............................
            $em->persist($resumeLead);
            $em->flush();

            if (intval($resumeLead->getSepContactClient()) == 1) {
                $pdfFilePath = $this->generatePdfForSEP($resumeLead);
                if ($pdfFilePath) {
                    $this->sendNotifForSEP($pdfFilePath, $resumeLead, $user->getEmail(), $mailer);
                }
            }

            return $this->json(['idResumeLead' => $resumeLead->getId(), 'idOfAllCreatedDevis' => $idOfAllCreatedDevis], 200, [], ['groups' => 'post:read']);
        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/generate/pdf/for/sep/{id}", name="generate_pdf_for_sep", methods={"GET"})
     */
    public function generatePdfForServEtude(int $id): Response
    {
        $resumeLead = $this->getDoctrine()->getRepository(ResumeLead::class)->find($id);

        if ($resumeLead){
            $pdfFilePath = $this->generatePdfForSEP($resumeLead);

            return $this->json($resumeLead, 200, [], ['groups' => ['get-fq-id', 'post:read']]);
        }
    }

    /**
     * @Route("/resume/lead/{id}", name="resume_lead_edit", methods={"PUT", "PATCH"})
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager, ResumeLeadRepository $resumeLeadRepository, LeadDetailOperationRepository $leadDetailOperationRepository, BdcRepository $bdcRepository, UserInterface $user, Lead $lead, \Swift_Mailer $mailer): Response
    {
        $currentResumeLead = $resumeLeadRepository->find($id);

        if ($currentResumeLead) {
            try {
                # Récupération de donné provenant du front
                $resumeLeadArray = json_decode($request->getContent(), true);
                $leadDetailOperationsArray = isset(json_decode($request->getContent(), true)["leadDetailOperations"]) ? json_decode($request->getContent(), true)["leadDetailOperations"] : [];
                $piecesJointesArray = isset(json_decode($request->getContent(), true)["piecesJointes"]) ? json_decode($request->getContent(), true)["piecesJointes"] : [];

                # Modification fiche qualification ou resumé du lead
                $currentResumeLead->setDateDebut($resumeLeadArray["dateDebut"] ? new \DateTime($resumeLeadArray["dateDebut"]) : null);
                $currentResumeLead->setTypeOffre($resumeLeadArray["typeOffre"] ?? null);
                $currentResumeLead->setResumePrestation($resumeLeadArray["resumePrestation"] ?? null);
                $currentResumeLead->setPotentielCA($resumeLeadArray["potentielCA"] ?? null);
                $currentResumeLead->setSepContactClient($resumeLeadArray["sepContactClient"] ?? null);
                $currentResumeLead->setNiveauUrgence($resumeLeadArray["niveauUrgence"]);
                $currentResumeLead->setDelaiRemiseOffre($resumeLeadArray["delaiRemiseOffre"] ? new \DateTime($resumeLeadArray["delaiRemiseOffre"]) : null);
                $currentResumeLead->setDateDemarrage($resumeLeadArray["dateDemarrage"] ? new \DateTime($resumeLeadArray["dateDemarrage"]) : null);
                $currentResumeLead->setIsOutilFournis($resumeLeadArray["isOutilFournis"] ?? null);
                $currentResumeLead->setPercisionClient($resumeLeadArray["percisionClient"] ?? null);
                $currentResumeLead->setPointVigilance($resumeLeadArray["pointVigilance"] ?? null);
                $currentResumeLead->setOriginLead($resumeLeadArray["originLead"] ? $this->getDoctrine()->getRepository(OriginLead::class)->find($resumeLeadArray["originLead"]) : NULL);

                # Update interlocuteur................
                if (!empty($resumeLeadArray['interlocuteur'])) {
                    $tabIdContatcs = [];

                    foreach ($resumeLeadArray['interlocuteur'] as $item) {
                        $tabIdContatcs[] = $item;
                    }
                    $currentResumeLead->setInterlocuteur($tabIdContatcs);
                }

                # Modification du piece jointe
                if (!empty($piecesJointesArray)) {
                    // dd($piecesJointesArray);
                    $base64service = new Base64Service();
                    $files = [];

                    if ((pathinfo($piecesJointesArray[0]["fileName"], PATHINFO_EXTENSION)) != "bin") {
                        foreach ($piecesJointesArray as $item) {
                            $arrayTemp = [];
                            $arrayTemp['id'] = uniqid();
                            $arrayTemp['name'] = $item['name'];
                            $arrayTemp['fileName'] = $base64service->convertToFile($item['base64'], $this->getParameter('fiche_qualification_files_dir'), 'FQ_');
                            $files[] = $arrayTemp;
                        }
                        $currentResumeLead->setPiecesJointes($files);
                    }
                }

                $tempPaysProd = array();
                $tabBdcUniqId = array();
                $oneBdcUniqId = '';

                foreach ($leadDetailOperationsArray as $item) {
                    $leadDetailOperation = $leadDetailOperationRepository->findOneBy(['id' => $item['id']]);

                    $bdcs = $bdcRepository->findBy(
                        ['resumeLead' => $currentResumeLead]
                    );

                    if($leadDetailOperation) {
                        # Mis à jour du lead_detail_operation
                        $item = $this->saveLeadDetailOperation($leadDetailOperation, $item);

                        $currentResumeLead->addLeadDetailOperation($leadDetailOperation);

                        $uniqIdOperation = $leadDetailOperation->getUniqBdcFqOperation();

                        $this->editLigneFacturationByUniqId($uniqIdOperation, $item, $entityManager);

                        // $entityManager->persist($leadDetailOperation);

                        foreach ($bdcs as $bdc) {
                            $bdcLead = $bdc->getResumeLead()->getLeadDetailOperations();
                            foreach ($bdcLead as $ldop) {
                                if ($ldop->getId() == $item['id']) {
                                    $tabBdcUniqId[] = $bdc->getUniqId();
                                }
                            }
                        }
                        if (!in_array($item['paysProduction'], $tempPaysProd)) {
                            array_push($tempPaysProd, $item['paysProduction']);
                        }
                    } else {

                        $leadDetailOperation = new LeadDetailOperation();

                        $leadDetailOperation->setResumeLead($currentResumeLead);
                        $leadDetailOperation->setCategorieLead(isset($item['categorieLead']) ? $item['categorieLead'] : null);
                        $leadDetailOperation->setDateDebutCross(isset($item['dateDebutCross']) ? new \DateTime($item['dateDebutCross']) : null);
                        $leadDetailOperation->setTypeFacturation(isset($item['typeFacturation']) ? $this->getDoctrine()->getRepository(TypeFacturation::class)->find($item['typeFacturation']) : NULL);
                        $leadDetailOperation->setLangueTrt(isset($item['langueTrt']) ? $this->getDoctrine()->getRepository(LangueTrt::class)->find($item['langueTrt']) : NULL);
                        $leadDetailOperation->setBu(isset($item['bu']) ? $this->getDoctrine()->getRepository(Bu::class)->find($item['bu']) : NULL);
                        $leadDetailOperation->setOperation(isset($item['operation']) ? $this->getDoctrine()->getRepository(Operation::class)->find($item['operation']) : NULL);
                        $leadDetailOperation->setFamilleOperation(isset($item['familleOperation']) ? $this->getDoctrine()->getRepository(FamilleOperation::class)->find($item['familleOperation']) : NULL);
                        $leadDetailOperation->setHoraireProduction(isset($item['horaireProduction']) ? $this->getDoctrine()->getRepository(HoraireProduction::class)->find($item['horaireProduction']) : NULL);
                        $leadDetailOperation->setPaysFacturation($this->getDoctrine()->getRepository(PaysFacturation::class)->find($item['paysFacturation']));
                        $leadDetailOperation->setPaysProduction($this->getDoctrine()->getRepository(PaysProduction::class)->find($item['paysProduction']));
                        $entityManager->persist($leadDetailOperation);

                        if (!in_array($item['paysProduction'], $tempPaysProd)) {
                            # Ajout bon de commande en general (1 bon de commande plus précisement)...............................................
                            $bdc = new Bdc();
                            $bdc->setTitre('Titre bon de commande');
                            $bdc->setDateFin(null);

                            if(!empty($resumeLeadArray['resumePrestation']))
                            {
                                $bdc->setResumePrestation($resumeLeadArray['resumePrestation']);
                            }

                            $bdc->setSocieteFacturation(null);
                            $bdc->setDateCreate(new \DateTime());
                            $bdc->setDateModification(null);
                            $bdc->setResumeLead($currentResumeLead);
                            $bdc->setStatutClient(null);
                            $bdc->setUniqId(uniqid());
                            $bdc->setPaysProduction($this->getDoctrine()->getRepository(PaysProduction::class)->find($item['paysProduction']));
                            $bdc->setPaysFacturation($this->getDoctrine()->getRepository(PaysFacturation::class)->find($item['paysFacturation']));

                            # Liste de diffusion BDC
                            $customer = $this->getDoctrine()->getRepository(Customer::class)->find($resumeLeadArray['customer']);
                            $contacts = $customer->getContacts();

                            $listeDiffusion = "";
                            foreach($contacts As $contact)
                            {
                                if($contact->getIsCopieFacture())
                                {
                                    $listeDiffusion .= $contact->getEmail() . ";";
                                }
                            }

                            $bdc->setDiffusions($listeDiffusion);

                            $entityManager->persist($bdc);

                            $tabBdcUniqId[] = $bdc->getUniqId();

                            # Ajout dans la table Tarif
                            $tarif = new Tarif();
                            $tarif->setDateDebut(new \DateTime());
                            $tarif->setBu($this->getDoctrine()->getRepository(Bu::class)->find($item['bu']));
                            $tarif->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($item['typeFacturation']));
                            $tarif->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($item['operation']));
                            $tarif->setPaysProduction($this->getDoctrine()->getRepository(PaysProduction::class)->find($item['paysProduction']));
                            $tarif->setLangueTraitement($this->getDoctrine()->getRepository(LangueTrt::class)->find($item['langueTrt']));
                            $entityManager->persist($tarif);

                            # Ajout ligne de facturation
                            $ligneFacturation = new BdcOperation();

                            if (isset($item['volumeATraite'])) {
                                $ligneFacturation->setVolumeATraite(intval($item['volumeATraite']));
                            }

                            $ligneFacturation->setCategorieLead($item['categorieLead']);
                            $ligneFacturation->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($item['operation']));
                            $ligneFacturation->setLangueTrt($this->getDoctrine()->getRepository(LangueTrt::class)->find($item['langueTrt']));
                            $ligneFacturation->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($item['typeFacturation']));
                            $ligneFacturation->setBu($this->getDoctrine()->getRepository(Bu::class)->find($item['bu']));
                            $ligneFacturation->setIsParamPerformed(1);

                            $familleOperation = $this->getDoctrine()->getRepository(FamilleOperation::class)->find($item['familleOperation']);
                            $ligneFacturation->setFamilleOperation($familleOperation);
                            $ligneFacturation->setTarif($tarif);

                            $irm = 0;
                            if(!empty($familleOperation->getIsIrm()))
                            {
                                $irm = $familleOperation->getIsIrm();
                            }
                            $ligneFacturation->setIrm($irm);

                            $siRenta = 0;
                            if(!empty($familleOperation->getIsSiRenta()))
                            {
                                $siRenta = $familleOperation->getIsSiRenta();
                            }
                            $ligneFacturation->setSiRenta($siRenta);

                            $sage = 0;
                            if(!empty($familleOperation->getIsSage()))
                            {
                                $sage = $familleOperation->getIsSage();
                            }
                            $ligneFacturation->setSage($sage);
                            $ligneFacturation->setBdc($bdc);

                            $entityManager->persist($ligneFacturation);

                            array_push($tempPaysProd, $item['paysProduction']);
                        }
                    }
                }

                $custom = $this->getDoctrine()->getRepository(Customer::class)->find($resumeLeadArray['customer']);

                # Ajout ou MAJ statut client dans la table StatutLead
                $lead->updateStatusLeadByCustomer($custom, $this->getParameter('statut_lead_fiche_qualification_creer'));

                # Ajout d'une ligne dans la table WorkflowLead
                $lead->addWorkflowLead($custom, $this->getParameter('statut_lead_fiche_qualification_creer'));

                # Enregistrement des mis à jour
                $entityManager->persist($currentResumeLead);
                $entityManager->flush();

                $nbBDC = count($tempPaysProd);
                $idResumeLead = $currentResumeLead->getId();

                if (count($bdcs) == 1) {
                    $uniqbdc = $bdcRepository->findOneBy(['resumeLead' => $currentResumeLead]);
                    $oneBdcUniqId = $uniqbdc->getUniqId();
                }

                if (intval($currentResumeLead->getSepContactClient()) == 1) {
                    $pdfFilePath = $this->generatePdfForSEP($currentResumeLead);
                    if ($pdfFilePath) {
                        $this->sendNotifForSEP($pdfFilePath, $currentResumeLead, $user->getEmail(), $mailer);
                    }
                }

                return $this->json(['uniqId' => $tabBdcUniqId, 'nombreBdc' => $nbBDC, 'oneBdc' => $oneBdcUniqId, 'idFq' => $idResumeLead],
                    200, [], ['groups' => 'post:read']);
            } catch (\Exception $e) {
                return $this->json([
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }

    /**
     * @Route("/resume/lead/{id}", name="resume_lead_delete", methods={"DELETE"})
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $currentResumeLead = $this->getDoctrine()->getRepository(ResumeLead::class)->find($id);
        $entityManager->remove($currentResumeLead);
        $entityManager->flush();

        return $this->json("Suppression avec succès", 200, [], []);
    }

    /**
     * @Route("/delete/lead/detail/operation/{id}", name="lead_detail_operation_delete", methods={"DELETE"})
     * @return Response
     */
    public function deleteLeadDetailOperation(LeadDetailOperation $leadDetailOperation, BdcOperationRepository $bdcOperationRepository, LeadDetailOperationRepository $leadDetailOperationRepository): Response
    {
        $bdcOperation = $bdcOperationRepository->findOneBy(
            ['uniqBdcFqOperation' => $leadDetailOperation->getUniqBdcFqOperation()]
        );

        # Suppression du ligne de facturation correspondant à l'opération à supprimé
        if ($bdcOperation) {
            $bdcOperationRepository->deleteById($bdcOperation->getId());
        }

        # Suppression de l'opération
        $leadDetailOperationRepository->deleteOperationViaHisId($leadDetailOperation->getId());

        return $this->json("Suppression éffectué", 200, [], ['groups' => 'get-fq-id']);
    }

    /**
     * @Route("/delete/row/from/resumelead/{id}", name="resume_lead_delete", methods={"DELETE"})
     * @param int $id
     * @return Response
     */
    public function deleteResumeLead(int $id, ResumeLeadRepository $resumeLeadRepository, LeadDetailOperationRepository $leadDetailOperationRepository, BdcRepository $bdcRepository, BdcOperationRepository $bdcOperationRepository, WorkflowLeadRepository $workflowLeadRepository, Lead $lead): Response {
        # Modification du statut lead du client
        $currentResumeLead = $resumeLeadRepository->find($id);
        $customer = $currentResumeLead->getCustomer();

        $workflowCustomers = $workflowLeadRepository->findBy([
            "client" => $customer,
            "statut" => -1
        ]);

        foreach ($workflowCustomers as $workflowCustomer) {
            $workflowLeadRepository->deleteRowInWorkFlowLead($workflowCustomer->getId());
        }

        $workflowLeadCustomers = $workflowLeadRepository->findlastrecentlyWorkflow($customer->getId());

        if ($workflowLeadCustomers) {
            $lastStatus = $workflowLeadCustomers[0]->getStatut();

            $lead->updateStatusLeadByCustomer($customer->getId(), $lastStatus);
        } else {
            $lead->updateStatusLeadByCustomer($customer->getId(), 1);
        }

        $bdcs = $bdcRepository->findBy([
            'resumeLead' => $id
        ]);

        foreach ($bdcs as $bdc) {
            # Suppression des lignes de facturation
            $bdcOperationRepository->deleteById($bdc->getId());

            # Suppression du Bon de commande
            $bdcRepository->deleteBdcViaIdResumeLead($id);
        }

        # Suppression des opérations
        $leadDetailOperationRepository->deleteByResumeLeadId($id);

        # Suppression du fiche qualification (resumeLead)
        $resumeLeadRepository->deleteRowInResumeLead($id);

        return $this->json($workflowCustomers, 200, [], ['groups' => ['del-fq']]);
    }

    /**
     * @Route("/resume/lead/{id}", name="resume_lead_get", methods={"GET"})
     * @param int $id
     * @return Response
     * Get id entité fiche qualification ou resumé du lead
     */
    public function getResumeLeadById(int $id): Response
    {
        try {
            $response[] = $this->getDoctrine()->getRepository(ResumeLead::class)->find($id);
            return $this->json($response, 200, [], ['groups' => ['get-fq-id']]);
        } catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/get/resumelead/by/{customer}", name="resumelead_by_customer", methods={"GET"})
     * @param int $customer
     * @return Response
     * Get fiche qualification ou resumé du lead via l'id du customer
     */
    public function getResumeLeadByCustomer(int $customer): Response
    {
        try {
            $response[] = $this->getDoctrine()->getRepository(ResumeLead::class)->findOneBy([
                'customer' => $customer
            ]);
            return $this->json($response, 200, [], ['groups' => ['get-fq-id']]);
        } catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/resumes/leads", name="resume_lead_getAll", methods={"GET"})
     */
    public function getAll(): Response
    {
        return $this->json($this->getDoctrine()->getRepository(ResumeLead::class)->findAll(), 200, [], ['groups' => 'post:read']);
    }

    /**
     * @Route("/ref/fiche/qualification/{className}", name="get_refs_fiche", methods={"GET"})
     * @param string $className
     * @return Response
     */
    public function getRefs(string $className): Response
    {
        $data = $this->getDoctrine()->getRepository(sprintf('App\Entity\%s', $className))->findAll();
        return $this->json($data, 200, [], ['groups' => 'post:read']);
    }

    /**
     * @Route("/get/fiche/qualification/operation/{familleOperation}", name="get_operation", methods={"GET"})
     * @param int $familleOperation
     * @return Response
     */
    public function getOperationsAll(int $familleOperation, OperationRepository $operationRepository): Response
    {

        $datas = $operationRepository->findBy(
            ['familleOperation' => $familleOperation]
        );
        return $this->json($datas, 200, [], ['groups' => 'post:read']);
    }

    /**
     * @Route("/get/operation/without/hno/{familleOperation}", name="get_operation_without_hno", methods={"GET"})
     * @param int $familleOperation
     * @return Response
     */
    public function getOperations(int $familleOperation, OperationRepository $operationRepository): Response
    {

        $datas = $operationRepository->findBy(
            ['familleOperation' => $familleOperation]
        );

        $arrayTab = [];
        foreach ($datas as $operation) {
            $libelle = $operation->getLibelle();
            if (strpos(strtolower($libelle),"hno") === false) {
                array_push($arrayTab, $operation);
            }
        }
        return $this->json($arrayTab, 200, [], ['groups' => 'post:read']);
    }

    /**
     * @Route("/generate/pdf/{id}", name="generate_pdf", methods={"GET"})
     * @return Response
     */
    public function generatePdfForServiceEtude(int $id): Response
    {
        $resumeLead = $this->getDoctrine()->getRepository(ResumeLead::class)->find($id);

        $res = $this->generatePdfForSEP($resumeLead);

        return $this->json("Le pdf a été bien génerée", 200, [], ['groups' => ['get-fq-id']]);
    }

    /**
     * Generation pdf pour service etude
     */
    private function generatePdfForSEP($resumeLead){
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);

        $idContacts = $resumeLead->getInterlocuteur();

        $contacts = [];
        foreach ($idContacts as $idContact) {
            $contact = $this->getDoctrine()->getRepository(Contact::class)->find($idContact);
            $contacts[] = $contact;
        }

        $html = $this->renderView('forServiceEtude/forServiceEtude.html.twig', [
            'resumeLead' => $resumeLead,
            'contacts' => $contacts
        ]);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $output = $dompdf->output();

        $raisonSociale = $resumeLead->getCustomer()->getRaisonSocial();

        $date = date_format(new \DateTime(), 'Y-m-d');

        $fileName = 'FQ_' . $raisonSociale . '_' . $date . '.pdf';

        $pdfFilePath = $this->getParameter('bdc_dir') . $fileName;

        file_put_contents($pdfFilePath, $output);

        return $pdfFilePath;
    }

    private function sendNotifForSEP($file, $resumeLead, $exp, $mailer) {
        $obj = "Fiche qualification à visualiser";
        $dest = $this->getParameter('emailServiceEtude');
        $template = 'emailContent/forServiceEtudeProjet.html.twig';
        $message = (new \Swift_Message($obj))
            ->setFrom($exp)
            ->setTo($dest)
            ->setBody(
                $this->renderView(
                    $template,
                    ['resumeLead' => $resumeLead]
                ),
                "text/html"
            )
            ->attach(\Swift_Attachment::fromPath($file));
        $mailer->send($message);
        return "OK";
    }

    /**
     * @param $resumeLeadArray // resumeLead venant du front
     * @param $resumeLead // objet resumeLead
     * @param $em
     * @param $tabDups // tableau de leadDetailOperation
     * @param $tabBdcUniqId
     */
    private function saveBdcDuiplicates($resumeLeadArray, $resumeLead, $em, $tabDups, &$tabBdcUniqId) {

        // Supprimer les doublons pays de production via front avant de poster
        $idPaysProd = [];
        foreach ($tabDups as $tabDup) {
            if (!in_array($tabDup['paysProduction'], $idPaysProd)) {
                $idPaysProd[] = $tabDup['paysProduction'];
            }
        }

        foreach ($idPaysProd as $pays) {
            # Ajout nouvel bdc
            $bdc = $this->newBonDeCommande($resumeLeadArray, $resumeLead);

            # Ajout dans la table Tarif
            $tarif = new Tarif();
            $tarif->setDateDebut(new \DateTime());
            $tarif->setPaysProduction($this->getDoctrine()->getRepository(PaysProduction::class)->find($pays));
            foreach ($tabDups as $ligneFact) {
                if ($ligneFact['paysProduction'] == $pays) {
                    $bdc->setPaysFacturation($this->getDoctrine()->getRepository(PaysFacturation::class)->find($ligneFact['paysFacturation']));
                    $tarif->setBu($this->getDoctrine()->getRepository(Bu::class)->find($ligneFact['bu']));
                    $tarif->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($ligneFact['typeFacturation']));
                    $tarif->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($ligneFact['operation']));
                    $tarif->setLangueTraitement($this->getDoctrine()->getRepository(LangueTrt::class)->find($ligneFact['langueTrt']));
                }
            }
            $em->persist($tarif);

            # Ajout ligne de facturation
            $tabVerif = [1,2];
            foreach ($tabDups as $value) {
                if ($value['paysProduction'] == $pays) {
                    # Ajout ligne de facturation manuel
                    $this->newLigneFacturation($value, $tarif, $bdc);

                    # On va affecter dans un variable valueHno la valeur du hno via front
                    if (isset($value['hno']) && ($value['hno'] == "Oui")) {
                        // $operationsHno[] = $value;
                        if ($value['typeFacturation'] == $this->getParameter('param_id_type_fact_mixte')){
                            # Ajout des lignes des facturations HNO pour typeFact mixte (nb = 4)
                            for ($j = 0; $j < 4; $j++) {
                                if (!empty($value['operation'])){
                                    list($facturationType, $hnoHorsDimanche, $hnoDimanche) = $this->typeFactValueForHnoLigneFact($j);

                                    $operationBdc = new BdcOperation();
                                    $operationBdc->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($value['operation']));
                                    $operationBdc->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($facturationType));
                                    $operationBdc->setBu($this->getDoctrine()->getRepository(Bu::class)->find($value['bu'] ?? null));
                                    $operationBdc->setLangueTrt($this->getDoctrine()->getRepository(LangueTrt::class)->find($value['langueTrt'] ?? null));
                                    $operationBdc->setCategorieLead($value['categorieLead'] ?? null);
                                    $operationBdc->setIsHnoDimanche($hnoDimanche ?? null);
                                    $operationBdc->setIsHnoHorsDimanche($hnoHorsDimanche ?? null);
                                    $operationBdc->setIsParamPerformed(0);

                                    $bdc->addBdcOperation($operationBdc);
                                }
                            }
                        } else {
                            for ($j = 0; $j < sizeof($tabVerif); $j++) {
                                $operationBdc = new BdcOperation();
                                $operationBdc->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($value['operation']));
                                $operationBdc->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($value['typeFacturation'] ?? null));
                                $operationBdc->setBu($this->getDoctrine()->getRepository(Bu::class)->find($value['bu'] ?? null));
                                $operationBdc->setLangueTrt($this->getDoctrine()->getRepository(LangueTrt::class)->find($value['langueTrt'] ?? null));
                                $operationBdc->setCategorieLead($value['categorieLead'] ?? null);
                                if ($tabVerif[$j] == 1) {
                                    $operationBdc->setIsHnoDimanche(1);
                                } else {
                                    $operationBdc->setIsHnoHorsDimanche(1);
                                }
                                $operationBdc->setIsParamPerformed(0);

                                $bdc->addBdcOperation($operationBdc);
                            }
                        }
                    }
                }
            }

            $bdc->setPaysProduction($this->getDoctrine()->getRepository(PaysProduction::class)->find($pays));
            $bdc->setUniqId(uniqid());
            $tabBdcUniqId[] = $bdc->getUniqId();
            $em->persist($bdc);
            $em->flush();

            # Ajout opération automatique
            $this->ajoutOperationAutomatique($bdc->getId(), $em, $tabDups);
        }
    }

    /**
     * @param $tempArray
     * @param $resumeLeadArray
     * @param $resumeLead
     * @param $em
     * @param $tabBdcUniqId
     */
    private function saveMutliBdc($tempArray, $resumeLeadArray, $resumeLead, $em, &$tabBdcUniqId) {
        /**
         * Contient les id des langue de traitement
         * Necessire à la creation des lignes de facturation automatique
         */
        $idLangueTrt = [];

        $nbrDetailOperation = sizeof($tempArray);
        for ($i=0; $i <$nbrDetailOperation ; $i++) {
            $result = $tempArray[$i]['paysProduction'];
            if ($result) {
                $bdc = $this->newBonDeCommande($resumeLeadArray, $resumeLead);
                $bdc->setPaysFacturation($this->getDoctrine()->getRepository(PaysFacturation::class)->find($tempArray[$i]['paysFacturation']));

                # Ajout dans la table Tarif
                $tarif = new Tarif();
                $tarif->setDateDebut(new \DateTime());
                $tarif->setBu($this->getDoctrine()->getRepository(Bu::class)->find($tempArray[$i]['bu']));
                $tarif->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($tempArray[$i]['typeFacturation']));
                $tarif->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($tempArray[$i]['operation']));
                $tarif->setPaysProduction($this->getDoctrine()->getRepository(PaysProduction::class)->find($tempArray[$i]['paysProduction']));
                $tarif->setLangueTraitement($this->getDoctrine()->getRepository(LangueTrt::class)->find($tempArray[$i]['langueTrt']));
                $em->persist($tarif);

                # Ajout ligne de facturation
                $ligneFacturation = new BdcOperation();

                $ligneFacturation->setCategorieLead($tempArray[$i]['categorieLead']);
                $ligneFacturation->setPrixUnit($tempArray[$i]['prixUnit'] ?? null);
                $ligneFacturation->setTempsProductifs($tempArray[$i]['tempsProductifs'] ?? null);
                $ligneFacturation->setTarifHoraireCible($tempArray[$i]['tarifHoraireCible'] ?? null);
                $ligneFacturation->setDmt($tempArray[$i]['dmt'] ?? null);
                $ligneFacturation->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($tempArray[$i]['operation']));
                $ligneFacturation->setLangueTrt($this->getDoctrine()->getRepository(LangueTrt::class)->find($tempArray[$i]['langueTrt']));
                $ligneFacturation->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($tempArray[$i]['typeFacturation']));

                $familleOperation = $this->getDoctrine()->getRepository(FamilleOperation::class)->find($tempArray[$i]['familleOperation']);
                $ligneFacturation->setFamilleOperation($familleOperation);
                $ligneFacturation->setTarif($tarif);

                $ligneFacturation->setBu($this->getDoctrine()->getRepository(Bu::class)->find($tempArray[$i]['bu']));
                $ligneFacturation->setValueHno($tempArray[$i]['hno'] ?? null);
                $ligneFacturation->setNbHeureMensuel($tempArray[$i]['nbHeureMensuel'] ?? null);

                $ligneFacturation->setUniqBdcFqOperation($tempArray[$i]['uniq'] ?? null);
                $ligneFacturation->setNbEtp($tempArray[$i]['nbEtp'] ?? null);
                $ligneFacturation->setIsParamPerformed(1);

                $ligneFacturation->setProductiviteActe($tempArray[$i]['productiviteActe'] ?? null);
                $ligneFacturation->setPrixUnitaireActe($tempArray[$i]['prixUnitaireActe'] ?? null);
                $ligneFacturation->setPrixUnitaireHeure($tempArray[$i]['prixUnitaireHeure'] ?? null);

                if (isset($tempArray[$i]['designationActe'])){
                    $ligneFacturation->setDesignationActe($this->getDoctrine()->getRepository(Operation::class)->find($tempArray[$i]['designationActe']));
                }

                if (!empty($tempArray[$i]['coutHoraire'])) {
                    $ligneFacturation->setCoutHoraire($this->getDoctrine()->getRepository(CoutHoraire::class)->find($tempArray[$i]['coutHoraire']));
                }

                if (isset($tempArray[$i]['volumeATraite'])) {
                    $ligneFacturation->setVolumeATraite(intval($tempArray[$i]['volumeATraite']));
                }

                if(!empty($tempArray[$i]['objectifQuantitatif']))
                {
                    foreach($tempArray[$i]['objectifQuantitatif'] As $objectifQuantitatif)
                    {
                        $ligneFacturation->addObjectifQuantitatif($this->getDoctrine()->getRepository(ObjectifQuantitatif::class)->find($objectifQuantitatif));
                    }
                }

                if(!empty($tempArray[$i]['objectifQualitatif']))
                {
                    foreach($tempArray[$i]['objectifQualitatif'] As $objectifQualitatif)
                    {
                        $ligneFacturation->addObjectifQualitatif($this->getDoctrine()->getRepository(ObjectifQualitatif::class)->find($objectifQualitatif));
                    }
                }

                if (!empty($tempArray[$i]['indicateurQl'])){
                    foreach ($tempArray[$i]['indicateurQl'] as $indicQl) {
                        $indicatorQl = new IndicatorQualitatif();

                        $indicatorQl->setObjectifQualitatif($this->getDoctrine()->getRepository(ObjectifQualitatif::class)->find($indicQl['objectifQl']));
                        $indicatorQl->setIndicator($indicQl['indicator'] ?? null);
                        $indicatorQl->setUniqBdcFqOperation($tempArray[$i]['uniq'] ?? null);

                        $ligneFacturation->addIndicatorQualitatif($indicatorQl);
                    }
                }

                if (!empty($tempArray[$i]['indicateurQt'])){
                    foreach ($tempArray[$i]['indicateurQt'] as $indicQt) {
                        $indicatorQt = new IndicatorQuantitatif();

                        $indicatorQt->setObjectifQuantitatif($this->getDoctrine()->getRepository(ObjectifQuantitatif::class)->find($indicQt['objectifQt']));
                        $indicatorQt->setIndicator($indicQt['indicator'] ?? null);
                        $indicatorQt->setUniqBdcFqOperation($tempArray[$i]['uniq'] ?? null);

                        $ligneFacturation->addIndicatorQuantitatif($indicatorQt);
                    }
                }

                $irm = 0;
                if(!empty($familleOperation->getIsIrm()))
                {
                    $irm = $familleOperation->getIsIrm();
                }
                $ligneFacturation->setIrm($irm);

                $siRenta = 0;
                if(!empty($familleOperation->getIsSiRenta()))
                {
                    $siRenta = $familleOperation->getIsSiRenta();
                }
                $ligneFacturation->setSiRenta($siRenta);

                $sage = 0;
                if(!empty($familleOperation->getIsSage()))
                {
                    $sage = $familleOperation->getIsSage();
                }

                # Quantité
                $quantite = null;
                $quantiteActe = null;
                $quantiteHeure = null;
                $typeFacturation = intval($tempArray[$i]['typeFacturation']) ?? null;

                if ($typeFacturation != null)  {
                    switch($typeFacturation)
                    {
                        case 1: # Acte
                            # Si type de facturation = Acte, alors quantite = volume à traiter
                            $quantite = intval($tempArray[$i]['volumeATraite']) ?? 1;
                            break;

                        case 3: # A l'heure
                        case 5: # Forfait
                            # Si type de facturation = A l'heure, alors quantite = nombre d'heure mensuel
                            $quantite = (intval($tempArray[$i]['nbHeureMensuel']) * intval($tempArray[$i]['nbEtp'])) ?? 1;
                            break;

                        case 4: # En regie forfaitaire
                            # Si type de facturation = Acte, alors quantite = volume à traiter
                            $quantite = 1;
                            break;
                        case 7: # Mixte (Heure/Acte)
                            # quantiteActe = (nbEtp * nbHeureMensuel) * productiviteActe
                            $quantiteActe = ((intval($tempArray[$i]['nbEtp']) * intval($tempArray[$i]['nbHeureMensuel'])) * intval($tempArray[$i]['productiviteActe']));

                            # quantiteHeure = nbEtp * nbHeureMensuel
                            $quantiteHeure = (intval($tempArray[$i]['nbEtp']) * intval($tempArray[$i]['nbHeureMensuel']));
                            break;
                        default:
                            $quantite = 1;
                            break;
                    }
                }

                $ligneFacturation->setQuantite($quantite);
                $ligneFacturation->setQuantiteActe($quantiteActe);
                $ligneFacturation->setQuantiteHeure($quantiteHeure);
                $ligneFacturation->setSage($sage);
                $bdc->addBdcOperation($ligneFacturation);

                $bdc->setPaysProduction($this->getDoctrine()->getRepository(PaysProduction::class)->find($result));
                $bdc->setUniqId(uniqid());
                $tabBdcUniqId[] = $bdc->getUniqId();
                $em->persist($bdc);

                $em->flush();

                # Ajout de l'id du langue de traitement dans le tableau
                !in_array(intval($tempArray[$i]['langueTrt']), $idLangueTrt) && $idLangueTrt[] = intval($tempArray[$i]['langueTrt']);

                # Ajout nouvelle ligne fact hno dimanche et hors dimanche
                $this->saveNewLigneFacturationHnoDimancheAndHorDimanche($bdc->getId(), $em);

                # Ajout opération automatique
                $this->ajoutOperationAutomatique($bdc->getId(), $em, $tempArray);
            }
        }
    }

    /**
     * Mis à ligne de facturation via son uniqId
     * @param $unidId
     * @param $tempArray
     * @param $em
     */
    private function editLigneFacturationByUniqId($unidId, $tempArray, $em) {
        $ligneFacturation = $this->getDoctrine()->getRepository(BdcOperation::class)->findOneBy([
            'uniqBdcFqOperation' => $unidId
        ]);

        if ($ligneFacturation) {
            $ligneFacturation->setCategorieLead($tempArray['categorieLead']);
            $ligneFacturation->setPrixUnit($tempArray['prixUnit'] ?? null);
            $ligneFacturation->setTempsProductifs($tempArray['tempsProductifs'] ?? null);
            $ligneFacturation->setTarifHoraireCible($tempArray['tarifHoraireCible'] ?? null);
            $ligneFacturation->setDmt($tempArray['dmt'] ?? null);
            $ligneFacturation->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($tempArray['operation']));
            $ligneFacturation->setLangueTrt($this->getDoctrine()->getRepository(LangueTrt::class)->find($tempArray['langueTrt']));
            $ligneFacturation->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($tempArray['typeFacturation']));

            $familleOperation = $this->getDoctrine()->getRepository(FamilleOperation::class)->find($tempArray['familleOperation']);
            $ligneFacturation->setFamilleOperation($familleOperation);
            $ligneFacturation->setBu($this->getDoctrine()->getRepository(Bu::class)->find($tempArray['bu']));
            $ligneFacturation->setValueHno($tempArray['hno'] ?? null);
            $ligneFacturation->setNbHeureMensuel($tempArray['nbHeureMensuel'] ?? null);
            $ligneFacturation->setUniqBdcFqOperation($tempArray['uniq'] ?? null);
            $ligneFacturation->setNbEtp($tempArray['nbEtp'] ?? null);
            $ligneFacturation->setUniqBdcFqOperation($unidId);
            $ligneFacturation->setIsParamPerformed(1);

            if (!empty($tempArray['coutHoraire'])) {
                $ligneFacturation->setCoutHoraire($this->getDoctrine()->getRepository(CoutHoraire::class)->find($tempArray['coutHoraire']));
            }

            if (isset($tempArray['volumeATraite'])) {
                $ligneFacturation->setVolumeATraite(intval($tempArray['volumeATraite']));
            }

            # Quantité
            $quantite = 0;
            $typeFacturation = intval($tempArray['typeFacturation']) ?? null;

            if ($typeFacturation != null)  {
                switch($typeFacturation)
                {
                    case 1: # Acte
                        # Si type de facturation = Acte, alors quantite = volume à traiter
                        $quantite = intval($tempArray['volumeATraite']) ?? 1;
                        break;

                    case 3: # A l'heure
                        # Si type de facturation = A l'heure, alors quantite = nombre d'heure mensuel
                        // $quantite = intval($tempArray[$i]['nbHeureMensuel']) ?? 1;
                        $quantite = (intval($tempArray['nbHeureMensuel']) * intval($tempArray['nbEtp'])) ?? 1;
                        break;

                    case 4: # En regie forfaitaire
                        # Si type de facturation = Acte, alors quantite = volume à traiter
                        $quantite = "1";
                        break;
                    case 5: # Forfait
                        # Si type de facturation = Forfait, alors quantite = 1
                        $quantite = "1";
                        break;
                    default:
                        $quantite = "1";
                        break;
                }
            }
            $ligneFacturation->setQuantite($quantite);

            $em->persist($ligneFacturation);
            $em->flush();
        }
    }

    /**
     * @param $resumeLeadArray
     * @param $resumeLead
     * @param $tabUniq
     * @param $em
     * @param $tabBdcUniqId
     */
    private function saveBdcUniq($resumeLeadArray, $resumeLead, $tabUniq, $em, &$tabBdcUniqId) {

        foreach ($tabUniq as $item) {
            $bdc = $this->newBonDeCommande($resumeLeadArray, $resumeLead);
            $bdc->setPaysFacturation($this->getDoctrine()->getRepository(PaysFacturation::class)->find($item['paysFacturation']));

            # Ajout dans la table Tarif
            $this->newTarif($item, $em, $bdc);

            $bdc->setPaysProduction($this->getDoctrine()->getRepository(PaysProduction::class)->find($item['paysProduction']));
            $bdc->setUniqId(uniqid());
            $tabBdcUniqId[] = $bdc->getUniqId();
            $em->persist($bdc);
            $em->flush();

            # Ajout nouvelle ligne fact hno dimanche et hors dimanche
            $this->saveNewLigneFacturationHnoDimancheAndHorDimanche($bdc->getId(), $em);

            # Ajout opération automatique
            $this->ajoutOperationAutomatique($bdc->getId(), $em, $tabUniq);

        }
    }

    /**
     * @param $leadDetailOperation
     * @param $item
     * @return mixed
     */
    private function saveLeadDetailOperation($leadDetailOperation, $item)
    {
        $leadDetailOperation->setTypeFacturation(isset($item['typeFacturation']) ? $this->getDoctrine()->getRepository(TypeFacturation::class)->find($item['typeFacturation']) : NULL);
        $leadDetailOperation->setLangueTrt(isset($item['langueTrt']) ? $this->getDoctrine()->getRepository(LangueTrt::class)->find($item['langueTrt']) : NULL);
        $leadDetailOperation->setBu(isset($item['bu']) ? $this->getDoctrine()->getRepository(Bu::class)->find($item['bu']) : NULL);
        $leadDetailOperation->setCategorieLead($item['categorieLead'] ?? null);
        $leadDetailOperation->setOperation(isset($item['operation']) ? $this->getDoctrine()->getRepository(Operation::class)->find($item['operation']) : NULL);
        $leadDetailOperation->setFamilleOperation(isset($item['familleOperation']) ? $this->getDoctrine()->getRepository(FamilleOperation::class)->find($item['familleOperation']) : NULL);
        $leadDetailOperation->setHoraireProduction(isset($item['horaireProduction']) ? $this->getDoctrine()->getRepository(HoraireProduction::class)->find($item['horaireProduction']) : NULL);
        $leadDetailOperation->setTarifHoraireCible($item['tarifHoraireCible'] ?? null);
        $leadDetailOperation->setVolumeATraite($item['volumeATraite'] ?? null);
        $leadDetailOperation->setNbHeureMensuel($item['nbHeureMensuel'] ?? null);
        $leadDetailOperation->setNbEtp($item['nbEtp'] ?? null);
        $leadDetailOperation->setTempsProductifs($item['tempsProductifs'] ?? null);
        $leadDetailOperation->setDmt($item['dmt'] ?? null);
        $leadDetailOperation->setPaysFacturation($this->getDoctrine()->getRepository(PaysFacturation::class)->find($item['paysFacturation']));
        $leadDetailOperation->setPaysProduction($this->getDoctrine()->getRepository(PaysProduction::class)->find($item['paysProduction']));
        $leadDetailOperation->setHno($item['hno'] ?? "Non");
        $leadDetailOperation->setPrixUnit($item['prixUnit'] ?? null);
        $leadDetailOperation->setProductiviteActe($item['productiviteActe'] ?? null);
        $leadDetailOperation->setPrixUnitaireActe($item['prixUnitaireActe'] ?? null);
        $leadDetailOperation->setPrixUnitaireHeure($item['prixUnitaireHeure'] ?? null);
        $leadDetailOperation->setDesignationActe(isset($item['designationActe']) ? $this->getDoctrine()->getRepository(Operation::class)->find($item['designationActe']) : null);

        if (!empty($item['coutHoraire'])) {
            $leadDetailOperation->setCoutHoraire($this->getDoctrine()->getRepository(CoutHoraire::class)->find($item['coutHoraire']));
        }

        if(!empty($item['objectifQuantitatif']))
        {
            foreach($item['objectifQuantitatif'] As $objectifQuantitatif)
            {
                $leadDetailOperation->addObjectifQuantitatif($this->getDoctrine()->getRepository(ObjectifQuantitatif::class)->find($objectifQuantitatif));
            }
        }

        if(!empty($item['objectifQualitatif']))
        {
            foreach($item['objectifQualitatif'] As $objectifQualitatif)
            {
                $leadDetailOperation->addObjectifQualitatif($this->getDoctrine()->getRepository(ObjectifQualitatif::class)->find($objectifQualitatif));
            }
        }

        return $item;
    }

    /**
     * @param $resumeLeadArray
     * @param $resumeLead
     * @return Bdc
     */
    private function newBonDeCommande($resumeLeadArray, $resumeLead): Bdc
    {
        $bdc = new Bdc();
        $bdc->setTitre('Titre bon de commande');
        $bdc->setDateFin(null);

        if (!empty($resumeLeadArray['resumePrestation'])) {
            $bdc->setResumePrestation($resumeLeadArray['resumePrestation']);
        }

        $bdc->setSocieteFacturation(null);
        $bdc->setDateCreate(new \DateTime());
        $bdc->setDateModification(null);
        $bdc->setResumeLead($resumeLead);
        $bdc->setStatutClient(null);

        # Liste de diffusion BDC
        $customer = $this->getDoctrine()->getRepository(Customer::class)->find($resumeLeadArray['customer']);
        $contacts = $customer->getContacts();
        $listeDiffusion = "";
        foreach ($contacts as $contact) {
            if ($contact->getIsCopieFacture()) {
                $listeDiffusion .= $contact->getEmail() . ";";
            }
        }

        $bdc->setDiffusions($listeDiffusion);
        return $bdc;
    }

    /**
     * @param $index
     * @param EntityManagerInterface $em
     * @param Bdc $bdc
     */
    private function newTarif($index, EntityManagerInterface $em, Bdc $bdc): void
    {
        $tarif = new Tarif();
        $tarif->setDateDebut(new \DateTime());
        $tarif->setBu($this->getDoctrine()->getRepository(Bu::class)->find($index['bu']));
        $tarif->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($index['typeFacturation']));
        $tarif->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($index['operation']));
        $tarif->setPaysProduction($this->getDoctrine()->getRepository(PaysProduction::class)->find($index['paysProduction']));
        $tarif->setLangueTraitement($this->getDoctrine()->getRepository(LangueTrt::class)->find($index['langueTrt']));
        $em->persist($tarif);

        # Ajout ligne de facturation
        $this->newLigneFacturation($index, $tarif, $bdc);
    }

    /**
     * @param $value
     * @param Tarif $tarif
     * @param Bdc $bdc
     */
    private function newLigneFacturation($value, Tarif $tarif, Bdc $bdc): void
    {
        $ligneFacturation = new BdcOperation();
        $ligneFacturation->setCategorieLead($value['categorieLead']);
        $ligneFacturation->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($value['operation']));
        $ligneFacturation->setLangueTrt($this->getDoctrine()->getRepository(LangueTrt::class)->find($value['langueTrt']));
        $ligneFacturation->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($value['typeFacturation']));

        $familleOperation = $this->getDoctrine()->getRepository(FamilleOperation::class)->find($value['familleOperation']);
        $ligneFacturation->setFamilleOperation($familleOperation);
        $ligneFacturation->setTarif($tarif);
        $ligneFacturation->setNbHeureMensuel($value['nbHeureMensuel'] ?? null);
        $ligneFacturation->setNbEtp($value['nbEtp'] ?? null);

        $ligneFacturation->setPrixUnit($value['prixUnit'] ?? null);
        $ligneFacturation->setTempsProductifs($value['tempsProductifs'] ?? null);
        $ligneFacturation->setTarifHoraireCible($value['tarifHoraireCible'] ?? null);
        $ligneFacturation->setDmt($value['dmt'] ?? null);
        $ligneFacturation->setIsParamPerformed(1);

        $ligneFacturation->setBu($this->getDoctrine()->getRepository(Bu::class)->find($value['bu']));
        $ligneFacturation->setValueHno($value['hno'] ?? null);
        $ligneFacturation->setUniqBdcFqOperation($value['uniq'] ?? null);
        $ligneFacturation->setProductiviteActe($value['productiviteActe'] ?? null);
        $ligneFacturation->setPrixUnitaireActe($value['prixUnitaireActe'] ?? null);
        $ligneFacturation->setPrixUnitaireHeure($value['prixUnitaireHeure'] ?? null);

        if (isset($value['designationActe'])){
            $ligneFacturation->setDesignationActe($this->getDoctrine()->getRepository(Operation::class)->find($value['designationActe']));
        }

        if (!empty($value['coutHoraire'])) {
            $ligneFacturation->setCoutHoraire($this->getDoctrine()->getRepository(CoutHoraire::class)->find($value['coutHoraire']));
        }

        if (isset($value['volumeATraite'])) {
            $ligneFacturation->setVolumeATraite(intval($value['volumeATraite']));
        }

        if(!empty($value['objectifQuantitatif']))
        {
            foreach($value['objectifQuantitatif'] As $objectifQuantitatif)
            {
                $ligneFacturation->addObjectifQuantitatif($this->getDoctrine()->getRepository(ObjectifQuantitatif::class)->find($objectifQuantitatif));
            }
        }

        if(!empty($value['objectifQualitatif']))
        {
            foreach($value['objectifQualitatif'] As $objectifQualitatif)
            {
                $ligneFacturation->addObjectifQualitatif($this->getDoctrine()->getRepository(ObjectifQualitatif::class)->find($objectifQualitatif));
            }
        }

        # Ajouter ses objectifs et ses indicateurs dans la table IndicatorQualitatif
        if (!empty($value['indicateurQl'])){
            foreach ($value['indicateurQl'] as $indicQl) {
                $indicatorQl = new IndicatorQualitatif();

                $indicatorQl->setObjectifQualitatif($this->getDoctrine()->getRepository(ObjectifQualitatif::class)->find($indicQl['objectifQl']));
                $indicatorQl->setIndicator($indicQl['indicator'] ?? null);
                $indicatorQl->setUniqBdcFqOperation($value['uniq'] ?? null);

                $ligneFacturation->addIndicatorQualitatif($indicatorQl);
            }
        }

        # Ajouter ses objectifs et ses indicateurs dans la table IndicatorQuantitatif
        if (!empty($value['indicateurQt'])){
            foreach ($value['indicateurQt'] as $indicQt) {
                $indicatorQt = new IndicatorQuantitatif();

                $indicatorQt->setObjectifQuantitatif($this->getDoctrine()->getRepository(ObjectifQuantitatif::class)->find($indicQt['objectifQt']));
                $indicatorQt->setIndicator($indicQt['indicator'] ?? null);
                $indicatorQt->setUniqBdcFqOperation($value['uniq'] ?? null);

                $ligneFacturation->addIndicatorQuantitatif($indicatorQt);
            }
        }

        $irm = 0;
        if (!empty($familleOperation->getIsIrm())) {
            $irm = $familleOperation->getIsIrm();
        }
        $ligneFacturation->setIrm($irm);

        $siRenta = 0;
        if (!empty($familleOperation->getIsSiRenta())) {
            $siRenta = $familleOperation->getIsSiRenta();
        }
        $ligneFacturation->setSiRenta($siRenta);

        $sage = 0;
        if (!empty($familleOperation->getIsSage())) {
            $sage = $familleOperation->getIsSage();
        }
        $ligneFacturation->setSage($sage);

        # Quantité
        $quantite = null;
        $quantiteActe = null;
        $quantiteHeure = null;

        $typeFacturation = intval($value['typeFacturation']) ?? null;

        if ($typeFacturation != null)  {
            switch($typeFacturation)
            {
                case 1: # Acte
                    # Si type de facturation = Acte, alors quantite = volume à traiter
                    $quantite = intval($value['volumeATraite']) ?? 1;
                    break;

                case 3: # A l'heure
                case 5: # Forfait
                    # Si type de facturation = A l'heure, alors quantite = nombre d'heure mensuel
                    $quantite = (intval($value['nbEtp']) * intval($value['nbHeureMensuel'])) ?? 1;
                    break;

                case 4: # En regie forfaitaire
                    # Si type de facturation = Acte, alors quantite = volume à traiter
                    $quantite = "1";
                    break;

                case 7: # Mixte (Heure/Acte)
                    # quantiteActe = (nbEtp * nbHeureMensuel) * productiviteActe
                    $quantiteActe = ($value['nbEtp'] * $value['nbHeureMensuel']) * $value['productiviteActe'];

                    # quantiteHeure = nbEtp * nbHeureMensuel
                    $quantiteHeure = $value['nbEtp'] * $value['nbHeureMensuel'];
                    break;
                default:
                    $quantite = 1;
                    break;
            }

            $ligneFacturation->setQuantite($quantite);
            $ligneFacturation->setQuantiteActe($quantiteActe);
            $ligneFacturation->setQuantiteHeure($quantiteHeure);
        }

        $bdc->addBdcOperation($ligneFacturation);
    }

    /**
     * @param $idBdc
     * @param $entityManager
     * Ajout automatique des opérations Panne technique DO, Panne technique outsourcia, Regule et .........
     */
    private function ajoutOperationAutomatique($idBdc, $entityManager, $leadDetailOperationsArray) {
        $tabIdOperationFormationAndPanneTech = $this->getParameter('param_id_operation_formation_panne_technique');

        # On a besoin d'ID du bon de commande à créer
        $dataBdc = $this->getDoctrine()->getRepository(Bdc::class)->find($idBdc);

        # Logique bonus et malus avant de faire l'ajout automtique
        $tabIdLangTrt = [];
        $tabIdBu = [];
        foreach ($leadDetailOperationsArray as $item) {
            $tabIdLangTrt[] = $item['langueTrt'];
            $tabIdBu[] = $item['bu'];
        }

        $unique = array_unique($tabIdLangTrt);

        # Ajout operation formation et panne technique uniquement pour langue de traiment ajouté
        if (!empty($unique)) {
            foreach ($tabIdOperationFormationAndPanneTech as $formationAndPanneTech) {
                foreach ($unique as $value) {
                    $ligneFacturation = new BdcOperation();
                    $ligneFacturation->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find(3));
                    $ligneFacturation->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($formationAndPanneTech));
                    $ligneFacturation->setLangueTrt($this->getDoctrine()->getRepository(LangueTrt::class)->find($value));
                    $ligneFacturation->setIsParamPerformed(0);
                    $dataBdc->addBdcOperation($ligneFacturation);
                }
            }
            $entityManager->persist($dataBdc);
            $entityManager->flush();
        }

        # Logique ajout des operations automatique
        $this->extractedOpAuto($this->getParameter('param_id_operation_automatique'), $dataBdc, $entityManager);
    }

    /**
     * @param $operation
     * @return string|void
     * Retourne le type facturation pour chaque ligne de facturation automatique
     */
    private function getTypeFactForLignFactAuto($operation){
        if (in_array($operation, $this->getParameter('param_lign_fact_auto_type_heure'))){
            return "Heure";
        } elseif (in_array($operation, $this->getParameter('param_lign_fact_auto_type_acte'))) {
            return "Acte";
        } elseif (in_array($operation, $this->getParameter('param_lign_fact_auto_type_forfait'))) {
            return "Forfait";
        }
    }

    /**
     * @param $idBdc
     * @param $manager
     * Ajout nouvelle ligne de facturation HNO (dimance et hors dimanche)
     */
    private function saveNewLigneFacturationHnoDimancheAndHorDimanche($idBdc, $manager) {

        # On a besoin l' id du bon de commande en question
        $bonDeCommande = $this->getDoctrine()->getRepository(Bdc::class)->find($idBdc);

        # On va ajouter dans notre variable idOperation s'il y a ajout hno est egal Oui
        $bdcOperationWithHno = [];
        foreach ($bonDeCommande->getBdcOperations() as $item) {
            if ($item->getValueHno() == "Oui") {
                $bdcOperationWithHno[] = $item;
            }
        }

        # On va ajouter la nouvelle ligne facturation hno dimanche et hors dimanche si idOperation est différent de null
        $tabVerif = [1,2];
        if (!empty($bdcOperationWithHno)) {
            foreach ($bdcOperationWithHno as $value) {
                $nbLignFactHno = ($value->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_mixte')) ? 4 : 2;
                if ($value->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_mixte')) {
                    # Ajout des lignes des facturations HNO pour typeFact mixte (nb = 4)
                    for ($j = 0; $j < $nbLignFactHno; $j++) {
                        if (!empty($value->getOperation())) {
                            list($facturationType, $hnoHorsDimanche, $hnoDimanche) = $this->typeFactValueForHnoLigneFact($j);

                            $operationBdc = new BdcOperation();
                            $operationBdc->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($value->getOperation()->getId()));
                            $operationBdc->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($facturationType ?? null));
                            $operationBdc->setBu($this->getDoctrine()->getRepository(Bu::class)->find($value->getBu()->getId() ?? null));
                            $operationBdc->setLangueTrt($this->getDoctrine()->getRepository(LangueTrt::class)->find($value->getLangueTrt()->getId() ?? null));
                            $operationBdc->setCategorieLead($value->getCategorieLead() ?? null);
                            $operationBdc->setIsHnoDimanche($hnoDimanche ?? null);
                            $operationBdc->setIsHnoHorsDimanche($hnoHorsDimanche ?? null);
                            $operationBdc->setIsParamPerformed(0);

                            $bonDeCommande->addBdcOperation($operationBdc);
                        }
                    }
                } else {
                    # Ajout des lignes des facturations HNO pour typeFact classique (nb = 2)
                    for ($j = 0; $j < sizeof($tabVerif); $j++) {
                        if ($value->getOperation()->getId() !== null) {
                            $operationBdc = new BdcOperation();
                            $operationBdc->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($value->getOperation()->getId()));
                            $operationBdc->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($value->getTypeFacturation()->getId() ?? null));
                            $operationBdc->setBu($this->getDoctrine()->getRepository(Bu::class)->find($value->getBu()->getId() ?? null));
                            $operationBdc->setLangueTrt($this->getDoctrine()->getRepository(LangueTrt::class)->find($value->getLangueTrt()->getId() ?? null));
                            $operationBdc->setCategorieLead($value->getCategorieLead() ?? null);
                            if ($tabVerif[$j] == 1) {
                                $operationBdc->setIsHnoDimanche(1);
                            } else {
                                $operationBdc->setIsHnoHorsDimanche(1);
                            }
                            $operationBdc->setIsParamPerformed(0);

                            $bonDeCommande->addBdcOperation($operationBdc);
                        }
                    }
                }
            }
            $manager->persist($bonDeCommande);
            $manager->flush();
        }
    }

    /**
     * @param int|null $elem
     * @return array
     * Retourne les valeurs de type de facturation HNO
     * et determine s'il est hno hors dimanche ou dimanche
     */
    private function typeFactValueForHnoLigneFact(int $elem = null): array
    {
        $facturationType = null;
        $hnoHorsDimanche = null;
        $hnoDimanche = null;

        switch ($elem)
        {
            case 0: # HNO hors dimanche type Acte
                $facturationType = $this->getParameter('param_id_type_fact_acte');
                $hnoHorsDimanche = 1;
                break;
            case 1: # HNO dimanche type Acte
                $facturationType = $this->getParameter('param_id_type_fact_acte');
                $hnoDimanche = 1;
                break;
            case 2: # HNO hors dimanche type heure
                $facturationType = $this->getParameter('param_id_type_fact_heure');
                $hnoHorsDimanche = 1;
                break;
            case 3: # HNO dimanche type Acte
                $facturationType = $this->getParameter('param_id_type_fact_heure');
                $hnoDimanche = 1;
                break;
        }

        return array($facturationType, $hnoHorsDimanche, $hnoDimanche);
    }

    /**
     * @param $tabIdOperation
     * @param $dataBdc
     * @param $entityManager
     */
    private function extractedOpAuto($tabIdOperation, $dataBdc, $entityManager): void
    {
        for ($k = 0; $k < sizeof($tabIdOperation); $k++) {

            $newLigneFacturation = new BdcOperation();

            # On va faire de switch ici pour connaitre le type facturation de l'opération
            $typeFact = $this->getTypeFactForLignFactAuto($tabIdOperation[$k]);
            $newLigneFacturation->setIsParamPerformed(0);
            switch ($typeFact) {
                case "Heure":
                    $newLigneFacturation->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find(3));
                    $newLigneFacturation->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($tabIdOperation[$k]));
                    $dataBdc->addBdcOperation($newLigneFacturation);
                    break;
                case "Acte":
                    $newLigneFacturation->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find(1));
                    $newLigneFacturation->setPrixUnit(1);
                    $newLigneFacturation->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($tabIdOperation[$k]));
                    $dataBdc->addBdcOperation($newLigneFacturation);
                    break;
                case "Forfait":
                    $newLigneFacturation->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find(5));
                    $newLigneFacturation->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($tabIdOperation[$k]));
                    $dataBdc->addBdcOperation($newLigneFacturation);
                    break;
                default:
                    null;
            }
        }

        $entityManager->persist($dataBdc);
        $entityManager->flush();
    }

    private function timeToInt($time){
        $timestamp = strtotime($time);

        $hours = date('h', $timestamp);
        $minutes = date('i', $timestamp);
        $seconds = date('s', $timestamp);

        return (intval($hours) + (intval($minutes) / 60) + (intval($seconds) / 3600));
    }
}
