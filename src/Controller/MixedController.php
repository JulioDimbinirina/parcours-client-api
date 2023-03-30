<?php

namespace App\Controller;

use App\Entity\Bdc;
use App\Entity\BdcOperation;
use App\Entity\Bu;
use App\Entity\ContactHasProfilContact;
use App\Entity\CoutHoraire;
use App\Entity\Customer;
use App\Entity\Devise;
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
use App\Entity\PaysFacturation;
use App\Entity\PaysProduction;
use App\Entity\PotentielTransformation;
use App\Entity\ResumeLead;
use App\Entity\SocieteFacturation;
use App\Entity\Tarif;
use App\Entity\Tva;
use App\Entity\TypeFacturation;
use App\Repository\BdcRepository;
use App\Repository\CustomerRepository;
use App\Repository\PaysProductionRepository;
use App\Repository\UserRepository;
use App\Service\Lead;
use App\Service\MixedService;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpParser\Node\Stmt\Switch_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileManipulate;
use App\Service\SendMailTo;
use Symfony\Component\HttpFoundation\Request;

class MixedController extends AbstractController
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * @var FileManipulate
     */
    private $fileManipulate;

    /**
     * @param CustomerRepository $customerRepository
     */
    public function __construct(CustomerRepository $customerRepository){
        $this->customerRepository = $customerRepository;

        # Service pour manipulation fichier
        $this->fileManipulate = new FileManipulate();
    }

     /**
     * @Route("/mixed/relance/validation", name="relanceValidation", methods={"GET"})
     */
    public function relanceValidation(BdcRepository $repoBdc, PaysProductionRepository $repoPays, UserRepository $repoUser,SendMailTo $sendMailTo): Response
    {
        $paysAll = $repoPays->findAll();
        $userAll= $repoUser->findAll();
         #Send Email All Pays de Production France,Maroc,Mada
        foreach($paysAll as $pays){
            $this->getAndSendEmail("DIRPROD",$pays->getId(),$repoBdc,$repoUser,$sendMailTo,$userAll);   
        }
         #Send Email Daf
        $this->getAndSendEmail("DIRFINANCE",-1,$repoBdc,$repoUser,$sendMailTo,$userAll);
         #Send Email Dg
        $this->getAndSendEmail("DIRDG",-1,$repoBdc,$repoUser,$sendMailTo,$userAll);
        return $this->json("OK", 200, [], []);
    }

   
    /**
     * @Route("/set/refdata/to/file", name="set_refdata_tofile", methods={"POST"})
     */
    public function setFileRefDafa(Request $request): Response
    {
        try {
            # Import le fichier
            $file = $this->fileManipulate->uploadFile($this->getParameter('bdc_dir'), $request);

            if (file_exists($file)){
                $spreadsheet = IOFactory::load($file);

                // $spreadsheet->getSheet(0)->getCell("A2")->setValue("kdfsbfhjbf");

                ######################### Modification contenu du 1er onglet ###########################
                $sheet1 = $spreadsheet->getSheet(0);

                # Donner la largeur automatique pour chaque colonne
                $tabColumn1 = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];
                $this->setAutoWidthForEachColumn($sheet1, $tabColumn1);

                # Recuperer tout les clients qui existe dans la base de donnée parcours client
                $allCustomers = $this->customerRepository->findAll();

                if ($allCustomers){
                    foreach ($allCustomers as $index => $val){
                        if (!empty($val) && $val != ""){
                            # Cellule Ex: A2 à En
                            $cell = "A" . ($index + 2);
                            $sheet1->setCellValue($cell, $val->getRaisonSocial());
                        }
                    }
                }

                ############################# Modification contenu du 2eme onglet ##################################
                $sheet2 = $spreadsheet->getSheet(1);

                $tabColumn2 = ['A', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q'];
                $this->setAutoWidthForEachColumn($sheet2, $tabColumn2);
                $sheet2->getColumnDimension("B")->setWidth(20);
                $sheet2->getColumnDimension("C")->setWidth(35);
                $sheet2->getColumnDimension("D")->setWidth(35);

                ############################# Modification contenu du 3eme onglet ##################################
                $sheet3 = $spreadsheet->getSheet(2);

                # Donner la largeur automatique pour chaque colonne
                $tabColumn3 = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U'];
                $this->setAutoWidthForEachColumn($sheet3, $tabColumn3);

                # Set Ref Origin lead
                $allOriginLeads = $this->getDoctrine()->getRepository(OriginLead::class)->findAll();
                if ($allOriginLeads){
                    $this->setRefValue($sheet3, $allOriginLeads, "A");
                }

                # Set Ref Potentiel de transformation
                $potentielTrans = $this->getDoctrine()->getRepository(PotentielTransformation::class)->findAll();
                if ($potentielTrans){
                    $this->setRefValue($sheet3, $potentielTrans, "B");
                }

                # Set Ref Potentiel de transformation
                $dureeTrt = $this->getDoctrine()->getRepository(DureeTrt::class)->findAll();
                if ($dureeTrt){
                    $this->setRefValue($sheet3, $dureeTrt, "C");
                }

                # Set Ref Niveau d'urgence
                $tabNiveauUrgences = ["Urgent", "Normal", "Pas urgent"];
                foreach ($tabNiveauUrgences as $index => $val){
                    $cell = "D" . ($index + 2);
                    $sheet3->setCellValue($cell, $val);
                }

                # Set Ref Pays de production
                $paysProds = $this->getDoctrine()->getRepository(PaysProduction::class)->findAll();
                if ($paysProds){
                    $this->setRefValue($sheet3, $paysProds, "I");
                }

                # Set Ref Pays de facturation
                $paysFacts = $this->getDoctrine()->getRepository(PaysFacturation::class)->findAll();
                if ($paysFacts){
                    $x = 2;
                    foreach ($paysFacts as $paysFact){
                        # Set Ref Societe de facturation
                        $societeFacts = $this->getDoctrine()->getRepository(SocieteFacturation::class)->findBy([
                            "paysFacturation" => $paysFact
                        ]);

                        if ($societeFacts){
                            foreach ($societeFacts as $societeFact){
                                # Set Ref devise
                                $devise = $this->getDoctrine()->getRepository(Devise::class)->findOneBy([
                                    "paysFacturation" => $paysFact
                                ]);
                                if ($devise){
                                    $cell = "F" . $x;
                                    $sheet3->setCellValue($cell, $devise->getLibelle());
                                }

                                # Set Ref TVA
                                $tva = $this->getDoctrine()->getRepository(Tva::class)->findOneBy([
                                    "paysFacturation" => $paysFact
                                ]);
                                if ($tva){
                                    $cell = "G" . $x;
                                    $sheet3->setCellValue($cell, $tva->getLibelle());
                                }

                                # Set Ref Societe de facturation
                                if ($societeFact->getLibelle()){
                                    $cell = "E" . $x;
                                    $sheet3->setCellValue($cell, $societeFact->getLibelle());
                                }

                                # Set Ref Pays de facturation
                                $cellPaysFact = "J" . $x;
                                $sheet3->setCellValue($cellPaysFact, $paysFact->getLibelle());

                                $x += 1;
                            }
                        }
                    }
                }

                # Set Ref Bu
                $bus = $this->getDoctrine()->getRepository(Bu::class)->findAll();
                if ($bus){
                    $this->setRefValue($sheet3, $bus, "K");
                }

                # Set Ref famille operation et operation
                $familleOperations = $this->getDoctrine()->getRepository(FamilleOperation::class)->findAll();
                if ($familleOperations){
                    $i = 2;
                    foreach ($familleOperations as $familleOperation){
                        $operations = $this->getDoctrine()->getRepository(Operation::class)->getOperationWithoutHno($familleOperation->getId());

                        if ($operations){
                            foreach ($operations as $operation){
                                # Remplissage du colonne L (Famille operation)
                                $cellFamilleOperation = "L" . ($i);
                                $value = $familleOperation->getLibelle();
                                $sheet3->setCellValue($cellFamilleOperation, $value);

                                # Remplissage du colonne M (Operation)
                                $cellOperation = "M" . ($i);
                                $value = $operation->getLibelle();
                                $sheet3->setCellValue($cellOperation, $value);

                                $i += 1;
                            }
                        }
                    }
                }

                # Set Ref Langue de traitement
                $lngTrts = $this->getDoctrine()->getRepository(LangueTrt::class)->findAll();
                if ($lngTrts){
                    $this->setRefValue($sheet3, $lngTrts, "N");
                }

                # Set Ref type de facturation
                $typeFacts = $this->getDoctrine()->getRepository(TypeFacturation::class)->findAll();
                if ($typeFacts){
                    $this->setRefValue($sheet3, $typeFacts, "O");
                }

                # Set Ref Objectif Qual
                $objQuals = $this->getDoctrine()->getRepository(ObjectifQualitatif::class)->findAll();
                if ($objQuals){
                    $this->setRefValue($sheet3, $objQuals, "P");
                }

                # Set Ref Objectif Quant
                $objQuants = $this->getDoctrine()->getRepository(ObjectifQuantitatif::class)->findAll();
                if ($objQuants){
                    $this->setRefValue($sheet3, $objQuants, "Q");
                }

                # Set Ref Profil Agent
                $profilAgents = $this->getDoctrine()->getRepository(CoutHoraire::class)->findAll();
                if ($profilAgents){
                    foreach ($profilAgents as $index => $val){
                        $cell = "R" . ($index + 2);
                        $value = $val->getPays(). " - " .$val->getBu(). " - " .$val->getLangueSpecialite();
                        $sheet3->setCellValue($cell, $value);
                    }
                }

                $writer = new Xlsx($spreadsheet);

                # Le nom du fichier à exporter
                $newFile = $this->getParameter('bdc_dir').'Canvas Import BDC.xlsx';

                $writer->save($newFile);

                # Supprime le fichier importé au paravant
                $this->fileManipulate->deleteFile($file);

                return $this->json("OK", 200, [], []);
            }

            return $this->json("File not found", 200, [], []);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/import/exist/bdc", name="import_exist_bdc", methods={"POST"})
     */
    public function importExistBdcs(Request $request): Response
    {
        try {
            # Import le fichier
            $file = $this->fileManipulate->uploadFile($this->getParameter('bdc_dir'), $request);

            if (file_exists($file)) {
                $spreadsheet = IOFactory::load($file);

                # Recupère les données dans l'onglet bdc et operation
                list($dataBdc, $dataOperation) = $this->getBdcAndOperationData($spreadsheet);

                $datas = $this->groupedOperationByBdc($dataBdc, $dataOperation);

                if (!empty($datas)){
                    foreach ($datas as $data){
                        $raisonSocialeClient = $data["client"]["A"];

                        # Recuperation du client
                        $customer = $this->getDoctrine()->getRepository(Customer::class)->findOneBy([
                            "raisonSocial" => $raisonSocialeClient
                        ]);

                        if (!empty($customer)){
                            # Creaction du fiche qualification
                            $resumeLead = new ResumeLead();

                            # Mis à jour des propriétés dans FQ
                            $this->createResumeLead($customer, $resumeLead, $data["client"]);

                            /**
                             * Creation des lead detail operation
                             * et des lignes de facturations
                             */
                            $idForCurrentBdc = $this->createLeadDetailOperationAndLignFact($customer, $resumeLead, $dataBdc, $data);

                            $actualBdc = $this->getDoctrine()->getRepository(Bdc::class)->find($idForCurrentBdc);

                            if (!empty($actualBdc)){
                                # Creation des lignes de facturation Automatique
                                // $this->ajoutOperationAutomatique($actualBdc, $data["operation"]);

                                # Mis à jour des certains attribut dans le nouvelle bdc
                                $this->updateSomeAttributeValueOfBdc($actualBdc);

                                # Generation du pdf
                                $this->setPdf($actualBdc, "client");
                            }
                        }
                    }
                }

                return $this->json("Done !", 200, [], []);
            }

            return $this->json("File not found", 200, [], []);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @param $dataBdc
     * @param $dataOperation
     */
    private function groupedOperationByBdc($dataBdc, $dataOperation): array
    {
        $datas = [];
        foreach ($dataBdc as $index => $bdc){
            $tabOperation = [];

            foreach ($dataOperation as $operation){
                if ($operation["B"] == $bdc["A"] && ($operation["A"] == ($index + 1))){
                    $tabOperation[] = $operation;
                }
            }

            $tab = [
                "client" => $bdc,
                "operation" => $tabOperation
            ];

            !empty($tabOperation) && $datas[] = $tab;
        }

        return $datas;
    }

    /**
     * Ajout automatique des opérations Panne technique DO, Panne technique outsourcia, Regule et .........
     */
    /* private function ajoutOperationAutomatique(Bdc $actualBdc, $leadDetailOperationsArray){

        $tabIdOperationFormationAndPanneTech = $this->getParameter('param_id_operation_formation_panne_technique');

        # Logique bonus et malus avant de faire l'ajout automtique
        $tabIdLangTrt = [];
        foreach ($leadDetailOperationsArray as $item) {
            $tabIdLangTrt[] = $item['F'];
        }

        $distinctLangTrt = array_unique($tabIdLangTrt);

        # Ajout operation formation et panne technique uniquement pour langue de traiment ajouté
        if (!empty($distinctLangTrt)) {
            foreach ($tabIdOperationFormationAndPanneTech as $formationAndPanneTech) {
                foreach ($distinctLangTrt as $value) {
                    $ligneFacturation = new BdcOperation();

                    $ligneFacturation->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find(3));
                    $ligneFacturation->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($formationAndPanneTech));

                    $lngTrt = $this->getDoctrine()->getRepository(LangueTrt::class)->findOneBy([
                        'libelle' => $value
                    ]);
                    $lngTrt && $ligneFacturation->setLangueTrt($lngTrt);

                    $ligneFacturation->setIsParamPerformed(0);
                    $actualBdc->addBdcOperation($ligneFacturation);
                }
            }
            $this->getDoctrine()->getManager()->persist($actualBdc);
            $this->getDoctrine()->getManager()->flush();
        }

        # Logique ajout des operations automatique
        $this->extractedOpAuto($actualBdc);
    }*/

    /**
     * @param $actualBdc
     */
    /* private function extractedOpAuto($actualBdc): void
    {
        $tabIdOperation = $this->getParameter('param_id_operation_automatique');

        $nbLignFactAuto = sizeof($tabIdOperation);

        for ($k = 0; $k < $nbLignFactAuto; $k++) {

            $newLigneFacturation = new BdcOperation();

            # On va faire de switch ici pour connaitre le type facturation de l'opération
            $typeFact = $this->getTypeFactForLignFactAuto($tabIdOperation[$k]);

            $typeFactId = null;
            switch ($typeFact) {
                case "Heure":
                    $typeFactId = $this->getParameter('param_id_type_fact_heure');
                    break;
                case "Acte":
                    $typeFactId = $this->getParameter('param_id_type_fact_acte');
                    $newLigneFacturation->setPrixUnit(1);
                    break;
                case "Forfait":
                    $typeFactId = $this->getParameter('param_id_type_fact_forfait');
                    break;
            }

            $newLigneFacturation->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($typeFactId));
            $newLigneFacturation->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($tabIdOperation[$k]));
            $newLigneFacturation->setIsParamPerformed(0);

            $actualBdc->addBdcOperation($newLigneFacturation);
        }

        $this->getDoctrine()->getManager()->persist($actualBdc);
        $this->getDoctrine()->getManager()->flush();
    } */

    /**
     * @param $operation
     * @return string|void
     * Retourne le type facturation pour chaque ligne de facturation automatique
     */
    /* private function getTypeFactForLignFactAuto($operation){
        if (in_array($operation, $this->getParameter('param_lign_fact_auto_type_heure'))){
            return "Heure";
        } elseif (in_array($operation, $this->getParameter('param_lign_fact_auto_type_acte'))) {
            return "Acte";
        } elseif (in_array($operation, $this->getParameter('param_lign_fact_auto_type_forfait'))) {
            return "Forfait";
        }
    } */

    private function updateSomeAttributeValueOfBdc($createdBdc){
        # Set id mère
        $createdBdc->setIdMere($createdBdc->getId());

        # Set numero du bon de commande
        $numBdc = $createdBdc->getPaysProduction()->getId().".".$createdBdc->getPaysFacturation()->getId().".".$createdBdc->getBdcOperations()[0]->getBu()->getId().".".$createdBdc->getResumeLead()->getCustomer()->getUser()->getId().".".$createdBdc->getId();
        $createdBdc->setNumBdc($numBdc);

        # Set numero version
        $numeroVersion = $createdBdc->getId() . '_' . 'V' . 1 . '_' . date("Y-m-d");
        $createdBdc->setNumVersion($numeroVersion);

        $this->getDoctrine()->getManager()->persist($createdBdc);

        $this->getDoctrine()->getManager()->flush();
    }

    private function createLeadDetailOperationAndLignFact($customer, $resumeLead, $dataBdc, $data): int
    {
        $idForCurrentBdc = null;

        foreach ($data["operation"] as $operationArray){
            $currentBdc = null;

            $numRowBdc = $operationArray["A"] - 1;

            $bdc = $this->getDoctrine()->getRepository(Bdc::class)
                ->getBdcViaCustomerAndPaysProd($dataBdc[$numRowBdc]["A"], $dataBdc[$numRowBdc]["G"]);

            if (!empty($bdc)){
                $currentBdc = $bdc[0];
            } else {
                # Creation d'un nouveau bon de commande
                $uniqIdBdc = $this->createBdcForThisCustomer($data["client"], $customer, $resumeLead);

                $createdBdc = $this->getDoctrine()->getRepository(Bdc::class)->findOneBy([
                    "uniqId" => $uniqIdBdc
                ]);

                # On utilise la nouvelle bdc créé pour la creation des lignes de facturation
                $currentBdc = $createdBdc;
            }

            $idForCurrentBdc = $currentBdc->getId();

            # Creation des leads details Operations
            $uniqIdOperationLignFact = $this->createLeadDetailOperation($resumeLead, $dataBdc, $operationArray);

            # Creation des lignes de facturation manuelles
            $typeFacturation = $this->createManualLignFacturation($currentBdc, $operationArray, $dataBdc, $uniqIdOperationLignFact);

            # Creation des lignes de facturation HNO
            if ($operationArray["S"] == "Oui"){
                $this->createLignFacturationHNO($currentBdc, $operationArray, $typeFacturation, $dataBdc);
            }
        }

        return $idForCurrentBdc;
    }

    private function createLignFacturationHNO($currentBdc, $operationArray, $typeFacturation, $bdcArray){
        # Determine le nombre de ligne de facturation Hno à ajouter
        $nbLignFactHno = ($typeFacturation == $this->getParameter('param_id_type_fact_mixte')) ? 4 : 2;

        for ($x = 0; $x < $nbLignFactHno; $x++) {
            if (!empty($operationArray["D"])) {
                list($facturationType, $hnoHorsDimanche, $hnoDimanche, $majoriteDimanche, $majoriteHorsDimanche) = $this->typeFactValueForHnoLigneFact($operationArray, $nbLignFactHno, $typeFacturation, $x);

                $operationBdc = new BdcOperation();

                # Set familleOperation et operation
                $familleOperation = $this->getDoctrine()->getRepository(FamilleOperation::class)->findOneBy([
                    'libelle' => $operationArray["D"]
                ]);

                if ($familleOperation){
                    $operationBdc->setFamilleOperation($familleOperation);

                    if ($operationArray["E"]){
                        $operation = $this->getDoctrine()->getRepository(Operation::class)->findOneBy([
                            'libelle' => $operationArray["E"],
                            'familleOperation' => $familleOperation
                        ]);

                        $operation && $operationBdc->setOperation($operation);
                    }
                }

                # Set type facturation
                $operationBdc->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($facturationType));

                # Set Business unit
                if ($operationArray["C"]){
                    $bu = $this->getDoctrine()->getRepository(Bu::class)->findOneBy([
                        'libelle' => $operationArray["C"]
                    ]);

                    $bu && $operationBdc->setBu($bu);
                }

                # Set Business langue de traitement
                if ($operationArray["F"]){
                    $lngTrt = $this->getDoctrine()->getRepository(LangueTrt::class)->findOneBy([
                        'libelle' => $operationArray["F"]
                    ]);

                    $lngTrt && $operationBdc->setLangueTrt($lngTrt);
                }

                if ($operationArray["G"]){
                    $numRowBdc = $operationArray["A"] - 1;
                    $pays = $bdcArray[$numRowBdc]["G"];
                    $coutHoraire = $this->getDoctrine()->getRepository(CoutHoraire::class)->findOneBy([
                        'pays' => $pays,
                        'bu' => $operationArray["C"],
                        'langueSpecialite' => $operationArray["F"],
                    ]);

                    $coutHoraire && $operationBdc->setCoutHoraire($coutHoraire);
                }

                $operationArray["R"] && $operationBdc->setCategorieLead($operationArray["R"]);
                $hnoDimanche && $operationBdc->setIsHnoDimanche($hnoDimanche);
                $hnoHorsDimanche && $operationBdc->setIsHnoHorsDimanche($hnoHorsDimanche);

                $prixUnitMere = null;
                if ($typeFacturation == $this->getParameter('param_id_type_fact_mixte')){
                    if ($facturationType == $this->getParameter('param_id_type_fact_acte')){
                        $prixUnitMere = $operationArray["X"];
                    }
                    if ($facturationType == $this->getParameter('param_id_type_fact_heure')){
                        $prixUnitMere = $operationArray["W"];
                    }
                } else {
                    $prixUnitMere = $operationArray["P"];
                }

                ######################### Calcul prix unitaire ligne facturation HNO #######################
                $prixUnitaireHno = null;
                if (!empty($majoriteHorsDimanche)) {
                    # Majoration Hors dimanche
                    $operationBdc->setMajoriteHnoHorsDimanche($majoriteHorsDimanche);

                    $prixUnitaireHno = round(((($majoriteHorsDimanche * $prixUnitMere) / 100) + $prixUnitMere), 2);
                }

                if (!empty($majoriteDimanche)) {
                    # Majoration dimanche
                    $operationBdc->setMajoriteHnoDimanche($majoriteDimanche);

                    $prixUnitaireHno = round(((($majoriteDimanche * $prixUnitMere) / 100) + $prixUnitMere), 2);
                }

                $operationBdc->setPrixUnit($prixUnitaireHno);
                ##############################################################################################################

                $operationBdc->setProdParHeure("");

                $operationBdc->setOffert(0);

                $operationBdc->setIsParamPerformed(1);

                $currentBdc->addBdcOperation($operationBdc);

                $this->getDoctrine()->getManager()->persist($operationBdc);
                $this->getDoctrine()->getManager()->flush();
            }
        }
    }

    /**
     * @param int|null $elem
     * @return array
     * Retourne les valeurs de type de facturation HNO
     * et determine s'il est hno hors dimanche ou dimanche
     */
    private function typeFactValueForHnoLigneFact($operationArray, $nbLignFactHno, int $typeFacturation = null, int $elem = null): array
    {
        $facturationType = null;
        $hnoHorsDimanche = null;
        $hnoDimanche = null;
        $majoriteDimanche = null;
        $majoriteHorsDimanche = null;

        if ($nbLignFactHno == 4){
            switch ($elem)
            {
                case 0: # HNO hors dimanche type Acte
                    $facturationType = $this->getParameter('param_id_type_fact_acte');
                    $majoriteHorsDimanche = $operationArray["AA"] ?? null;
                    $hnoHorsDimanche = 1;
                    break;
                case 1: # HNO dimanche type Acte
                    $facturationType = $this->getParameter('param_id_type_fact_acte');
                    $majoriteDimanche = $operationArray["Y"] ?? null;
                    $hnoDimanche = 1;
                    break;
                case 2: # HNO hors dimanche type heure
                    $facturationType = $this->getParameter('param_id_type_fact_heure');
                    $majoriteHorsDimanche = $operationArray["AB"] ?? null;
                    $hnoHorsDimanche = 1;
                    break;
                case 3: # HNO dimanche type heure
                    $facturationType = $this->getParameter('param_id_type_fact_heure');
                    $majoriteDimanche = $operationArray["Z"] ?? null;
                    $hnoDimanche = 1;
                    break;
            }
        } else {
            switch ($elem)
            {
                case 0: # HNO dimanche
                    $hnoDimanche = 1;
                    $facturationType = $typeFacturation;
                    if ($typeFacturation == $this->getParameter('param_id_type_fact_acte')){
                        $majoriteDimanche = $operationArray["Y"] ?? null;
                    } else {
                        $majoriteDimanche = $operationArray["Z"] ?? null;
                    }
                    break;
                case 1: # HNO hors dimanche
                    $hnoHorsDimanche = 1;
                    $facturationType = $typeFacturation;
                    if ($typeFacturation == $this->getParameter('param_id_type_fact_acte')){
                        $majoriteHorsDimanche = $operationArray["AA"] ?? null;
                    } else {
                        $majoriteHorsDimanche = $operationArray["AB"] ?? null;
                    }
                    break;
            }
        }

        return array($facturationType, $hnoHorsDimanche, $hnoDimanche, $majoriteDimanche, $majoriteHorsDimanche);
    }

    private function createManualLignFacturation(Bdc $bdc, $operationArray, $bdcArray, $uniqIdOperationLignFact): int
    {
        $businessUnit = $operationArray["C"];
        $bu = null;
        if ($businessUnit){
            $bu = $this->getDoctrine()->getRepository(Bu::class)->findOneBy([
                'libelle' => $businessUnit
            ]);
        }

        $familleOperation = null;
        $operation = null;
        # Famille operation et opération
        if ($operationArray["D"]){
            $familleOperation = $this->getDoctrine()->getRepository(FamilleOperation::class)->findOneBy([
                'libelle' => $operationArray["D"]
            ]);

            if ($familleOperation){
                if ($operationArray["E"]){
                    $operation = $this->getDoctrine()->getRepository(Operation::class)->findOneBy([
                        'libelle' => $operationArray["E"],
                        'familleOperation' => $familleOperation
                    ]);
                }
            }
        }

        $typeFacturation = null;
        $typeFact = null;
        if ($operationArray["H"]){
            $typeFact = $this->getDoctrine()->getRepository(TypeFacturation::class)->findOneBy([
                'libelle' => $operationArray["H"]
            ]);

            $typeFact && $typeFacturation = $typeFact->getId();
        }

        $lngSpecialite = $operationArray["F"];
        $lngTrt = null;
        if ($lngSpecialite){
            $lngTrt = $this->getDoctrine()->getRepository(LangueTrt::class)->findOneBy([
                'libelle' => $lngSpecialite
            ]);
        }

        $paysProd = null;
        $numRowBdc = $operationArray["A"] - 1;
        $pays = $bdcArray[$numRowBdc]["G"];
        if ($pays){
            $paysProd = $this->getDoctrine()->getRepository(PaysProduction::class)->findOneBy([
                'libelle' => $pays
            ]);
        }

        # Ajout dans la table Tarif
        $tarif = new Tarif();
        $tarif->setDateDebut(new \DateTime());
        $tarif->setBu($bu);
        $tarif->setTypeFacturation($typeFact);
        $tarif->setOperation($operation);
        $tarif->setPaysProduction($paysProd);
        $tarif->setLangueTraitement($lngTrt);

        $this->getDoctrine()->getManager()->persist($tarif);

        $ligneFacturation = new BdcOperation();

        $operationArray["R"] && $ligneFacturation->setCategorieLead($operationArray["R"]);

        if ($familleOperation){
            $ligneFacturation->setFamilleOperation($familleOperation);

            $ligneFacturation->setIrm($familleOperation->getIsIrm() ?? 0);

            $ligneFacturation->setSiRenta($familleOperation->getIsSiRenta() ?? 0);

            $ligneFacturation->setSage($familleOperation->getIsSage() ?? 0);
        }

        $operation && $ligneFacturation->setOperation($operation);
        $lngTrt && $ligneFacturation->setLangueTrt($lngTrt);
        $bu && $ligneFacturation->setBu($bu);
        $typeFact && $ligneFacturation->setTypeFacturation($typeFact);

        $operationArray["M"] && $ligneFacturation->setNbHeureMensuel($operationArray["M"]);
        $operationArray["N"] && $ligneFacturation->setNbEtp($operationArray["N"]);
        $operationArray["O"] && $ligneFacturation->setVolumeATraite($operationArray["O"]);
        $operationArray["S"] && $ligneFacturation->setTarifHoraireCible($operationArray["J"]);

        if ($operationArray["K"]){
            $tempProdVal = str_replace("-",":", $operationArray["K"]);
            $ligneFacturation->setTempsProductifs($tempProdVal);
        }

        if ($operationArray["L"]){
            $dmtVal = str_replace("-",":", $operationArray["L"]);
            $ligneFacturation->setDmt($dmtVal);
        }

        $ligneFacturation->setValueHno($operationArray["S"] ?? "Non");
        $operationArray["P"] && $ligneFacturation->setPrixUnit($operationArray["P"]);
        $operationArray["V"] && $ligneFacturation->setProductiviteActe($operationArray["V"]);
        $operationArray['W'] && $ligneFacturation->setPrixUnitaireHeure($operationArray['W']);
        $operationArray['X'] && $ligneFacturation->setPrixUnitaireActe($operationArray['X']);

        if ($operationArray["I"]){
            $designationActe = $this->getDoctrine()->getRepository(Operation::class)->findOneBy([
                'libelle' => $operationArray["I"]
            ]);

            $designationActe && $ligneFacturation->setDesignationActe($designationActe);
        }

        if ($operationArray["G"]){
            $coutHoraire = $this->getDoctrine()->getRepository(CoutHoraire::class)->findOneBy([
                'pays' => $pays,
                'bu' => $businessUnit,
                'langueSpecialite' => $lngSpecialite,
            ]);

            $coutHoraire && $ligneFacturation->setCoutHoraire($coutHoraire);
        }

        # Quantité
        list($quantite, $quantiteActe, $quantiteHeure) = $this->getQuantityOfLignFact($operation->getId(), $typeFacturation, $operationArray);

        $quantite && $ligneFacturation->setQuantite($quantite);
        $quantiteActe && $ligneFacturation->setQuantiteActe($quantiteActe);
        $quantiteHeure && $ligneFacturation->setQuantiteHeure($quantiteHeure);

        $uniqIdOperationLignFact && $ligneFacturation->setUniqBdcFqOperation($uniqIdOperationLignFact);

        $ligneFacturation->setTarif($tarif);

        $ligneFacturation->setIsParamPerformed(1);

        # Objectif et indicateur Quantitatif
        $this->setObjectifAndIndicator($ligneFacturation, $operationArray);

        $bdc->addBdcOperation($ligneFacturation);

        $this->getDoctrine()->getManager()->persist($ligneFacturation);
        $this->getDoctrine()->getManager()->flush();

        return $typeFacturation;
    }

    private function createBdcForThisCustomer($dataBdc, Customer $customer, ResumeLead $resumeLead): string
    {
        $bdc = new Bdc();

        $bdc->setTitre('Titre bon de commande');

        // $bdc->setAdresseFacturation();

        // $dataBdc["F"] && $bdc->setDateDebut(new \DateTime($dataBdc["F"]));

        // $bdc->setDateFin();
        // $bdc->setCdc();
        // $bdc->setResumePrestation();

        $bdc->setDateCreate(new \DateTime());

        $bdc->setResumeLead($resumeLead);

        // $bdc->setStatutClient();

        if ($dataBdc["G"]){
            $paysProd = $this->getDoctrine()->getRepository(PaysProduction::class)->findOneBy([
                'libelle' => $dataBdc["G"]
            ]);
            $paysProd && $bdc->setPaysProduction($paysProd);
        }

        if ($dataBdc["H"]){
            $paysFact = $this->getDoctrine()->getRepository(PaysFacturation::class)->findOneBy([
                'libelle' => $dataBdc["H"]
            ]);
            $paysFact && $bdc->setPaysFacturation($paysFact);

            if ($dataBdc["I"]){
                $sociFact = $this->getDoctrine()->getRepository(SocieteFacturation::class)->findOneBy([
                    'libelle' => $dataBdc["I"],
                    'paysFacturation' => $paysFact
                ]);
                $sociFact && $bdc->setSocieteFacturation($sociFact);
            }

            if ($dataBdc["K"]){
                $tva = $this->getDoctrine()->getRepository(Tva::class)->findOneBy([
                    'libelle' => $dataBdc["K"],
                    'paysFacturation' => $paysFact
                ]);
                $tva && $bdc->setTva($tva);
            }

            if ($dataBdc["J"]){
                $devise = $this->getDoctrine()->getRepository(Devise::class)->findOneBy([
                    'libelle' => $dataBdc["J"],
                    'paysFacturation' => $paysFact
                ]);
                $devise && $bdc->setDevise($devise);
            }
        }

        $dataBdc["L"] && $bdc->setModeReglement($dataBdc["L"]);

        $dataBdc["M"] && $bdc->setDelaisPaiment($dataBdc["M"]);

        // $bdc->setMargeCible();

        $bdc->setStatutLead($this->getParameter('statut_lead_bdc_signe_client'));

        $uniqId = uniqid();
        $bdc->setUniqId($uniqId);

        // $bdc->setIdMere();

        # Ajout destinataire signataire................
        # Ajout destinataire facture................
        # Liste de diffusion BDC
        if (!empty($customer->getContacts())) {
            $listeDiffusion = "";
            $tabDestinateur = [];

            $contacts = $customer->getContacts();
            $tabDestinateur[] = $contacts[0]->getId();
            $listeDiffusion .= $contacts[0]->getEmail() . ";";

            /* foreach ($customer->getContacts() as $contact) {
                $tabDestinateur[] = $contact->getId();

                $listeDiffusion .= $contact->getEmail() . ";";
            }*/

            $bdc->setDestinataireSignataire($tabDestinateur);
            $bdc->setDestinataireFacture($tabDestinateur);
            $bdc->setDiffusions($listeDiffusion);
        }

        // $bdc->setClientIrmId();

        $dataBdc["O"] && $bdc->setDescriptionGlobale($dataBdc["O"]);

        $this->getDoctrine()->getManager()->persist($bdc);

        $this->getDoctrine()->getManager()->flush();

        return $uniqId;
    }

    private function createResumeLead(Customer $customer, ResumeLead $resumeLead, $dataResumeLead):void
    {
        $dataResumeLead["F"] && $resumeLead->setDateDebut(new \DateTime($dataResumeLead["F"]));
        $dataResumeLead["N"] && $resumeLead->setTypeOffre($dataResumeLead["N"]);
        // $resumeLead->setResumePrestation();
        // $resumeLead->setPotentielCA();
        // $resumeLead->setSepContactClient();
        $dataResumeLead["E"] && $resumeLead->setNiveauUrgence($dataResumeLead["E"]);
        // $resumeLead->setIsFormationFacturable();
        // $resumeLead->setDelaiRemiseOffre();
        // $resumeLead->setDateDemarrage();
        // $resumeLead->setIsOutilFournis();
        // $resumeLead->setPercisionClient();
        // $resumeLead->setPointVigilance();
        // $resumeLead->setPiecesJointes();

        if ($dataResumeLead["B"]){
            $originLead = $this->getDoctrine()->getRepository(OriginLead::class)->findOneBy([
                'libelle' => $dataResumeLead["B"]
            ]);

            $originLead && $resumeLead->setOriginLead($originLead);
        }

        if ($dataResumeLead["D"]){
            $dureeTrt = $this->getDoctrine()->getRepository(DureeTrt::class)->findOneBy([
                'libelle' => $dataResumeLead["D"]
            ]);

            $dureeTrt && $resumeLead->setDureeTrt($dureeTrt);
        }

        if ($dataResumeLead["C"]){
            $potTrans = $this->getDoctrine()->getRepository(PotentielTransformation::class)->findOneBy([
                'libelle' => $dataResumeLead["C"]
            ]);

            $potTrans && $resumeLead->setPotentielTransformation($potTrans);
        }

        # Set interlocuteur
        $tabIdContatcs = [];
        $contacts = $customer->getContacts();

        /* foreach ($customer->getContacts() as $contact){
            $tabIdContatcs[] = $contact->getId();
        } */

        $tabIdContatcs[] = $contacts[0]->getId();

        $resumeLead->setInterlocuteur($tabIdContatcs);

        $customer->addResumeLead($resumeLead);
    }

    private function createLeadDetailOperation(ResumeLead $resumeLead, $dataBdc, $operationArray): string
    {
        $leadDetailOperation = new LeadDetailOperation();

        $operationArray["R"] && $leadDetailOperation->setCategorieLead($operationArray["R"]);

        $leadDetailOperation->setDateDebutCross(new \DateTime());

        $typeFacturation = null;
        if ($operationArray["H"]){
            $typeFact = $this->getDoctrine()->getRepository(TypeFacturation::class)->findOneBy([
                'libelle' => $operationArray["H"]
            ]);

            if ($typeFact){
                $leadDetailOperation->setTypeFacturation($typeFact);
                $typeFacturation = $typeFact->getId();
            }
        }

        $lngSpecialite = "";
        if ($operationArray["F"]){
            $lngSpecialite = $operationArray["F"];
            $lngTrt = $this->getDoctrine()->getRepository(LangueTrt::class)->findOneBy([
                'libelle' => $lngSpecialite
            ]);

            $lngTrt && $leadDetailOperation->setLangueTrt($lngTrt);
        }

        $businessUnit = "";
        if ($operationArray["C"]){
            $businessUnit = $operationArray["C"];
            $bu = $this->getDoctrine()->getRepository(Bu::class)->findOneBy([
                'libelle' => $businessUnit
            ]);

            $bu && $leadDetailOperation->setBu($bu);
        }

        # Famille operation et opération
        $operationId = null;
        if ($operationArray["D"]){
            $familleOperation = $this->getDoctrine()->getRepository(FamilleOperation::class)->findOneBy([
                'libelle' => $operationArray["D"]
            ]);

            if ($familleOperation){
                $leadDetailOperation->setFamilleOperation($familleOperation);

                if ($operationArray["E"]){
                    $operation = $this->getDoctrine()->getRepository(Operation::class)->findOneBy([
                        'libelle' => $operationArray["E"],
                        'familleOperation' => $familleOperation
                    ]);

                    if ($operation){
                        $leadDetailOperation->setOperation($operation);
                        $operationId = $operation->getId();
                    }
                }
            }
        }

        if ($operationArray["Q"]){
            $horaireProd = $this->getDoctrine()->getRepository(HoraireProduction::class)->findOneBy([
                'libelle' => $operationArray["Q"]
            ]);

            $horaireProd && $leadDetailOperation->setHoraireProduction($horaireProd);
        }

        # Pays de production
        $pays = "";
        $numRowBdc = $operationArray["A"] - 1;

        if ($dataBdc[$numRowBdc]["G"]){
            $pays = $dataBdc[$numRowBdc]["G"];

            $paysProd = $this->getDoctrine()->getRepository(PaysProduction::class)->findOneBy([
                'libelle' => $pays
            ]);
            $paysProd && $leadDetailOperation->setPaysProduction($paysProd);
        }

        # Pays de facturation
        if ($dataBdc[$numRowBdc]["H"]){
            $paysFact = $this->getDoctrine()->getRepository(PaysFacturation::class)->findOneBy([
                'libelle' => $dataBdc[$numRowBdc]["H"]
            ]);

            $paysFact && $leadDetailOperation->setPaysFacturation($paysFact);
        }

        // $leadDetailOperation->setHeureJourOuvrable();

        // $leadDetailOperation->setHeureWeekEnd();

        $leadDetailOperation->setHno($operationArray["S"] ?? "Non");

        $operationArray["J"] && $leadDetailOperation->setTarifHoraireCible($operationArray["J"]);

        if ($operationArray["K"]){
            $tempProdVal = str_replace("-",":", $operationArray["K"]);
            $leadDetailOperation->setTempsProductifs($tempProdVal);
        }

        if ($operationArray["L"]){
            $dmtVal = str_replace("-",":", $operationArray["L"]);
            $leadDetailOperation->setDmt($dmtVal);
        }

        # Mis à jour Prix unitaire et Quantité
        $this->updatePrixUnitOfLigneFact($leadDetailOperation, $operationId,$typeFacturation, $operationArray);

        $operationArray["M"] && $leadDetailOperation->setNbHeureMensuel($operationArray["M"]);

        $operationArray["N"] && $leadDetailOperation->setNbEtp($operationArray["N"]);

        $operationArray["O"] && $leadDetailOperation->setVolumeATraite($operationArray["O"]);

        if ($operationArray["G"]){
            $coutHoraire = $this->getDoctrine()->getRepository(CoutHoraire::class)->findOneBy([
                'pays' => $pays,
                'bu' => $businessUnit,
                'langueSpecialite' => $lngSpecialite,
            ]);

            $coutHoraire && $leadDetailOperation->setCoutHoraire($coutHoraire);
        }

        $uniqIdOperationLignFact = uniqid();
        $leadDetailOperation->setUniqBdcFqOperation($uniqIdOperationLignFact);

        # Objectif et indicateur Quantitatif
        $this->setObjectifAndIndicator($leadDetailOperation, $operationArray);

        if ($operationArray["I"]){
            $operation = $this->getDoctrine()->getRepository(Operation::class)->findOneBy([
                'libelle' => $operationArray["I"]
            ]);

            $operation && $leadDetailOperation->setDesignationActe($operation);
        }

        $resumeLead->addLeadDetailOperation($leadDetailOperation);

        $this->getDoctrine()->getManager()->persist($leadDetailOperation);
        $this->getDoctrine()->getManager()->flush();

        return $uniqIdOperationLignFact;
    }

    /**
     * Mis à jour le prix unitaire d'une ligne de facturation
     */
    private function updatePrixUnitOfLigneFact(LeadDetailOperation $leadDetailOperation, $operationId, $idTypeFacturation, $arrayData) {
        list($quantite, $quantiteActe, $quantiteHeure) = $this->getQuantityOfLignFact($operationId, $idTypeFacturation, $arrayData);

        # Si type de facturation est égal à acte, alors on refait le calcul du prix unit à partir du tarifHoraireCible, tempsProductifs, et dmt
        if ($idTypeFacturation == $this->getParameter('param_id_type_fact_mixte')) {
            /**
             * quantiteActe = (nbEtp * nbHeureMensuel) * productiviteActe
             */
            $quantiteActe && $leadDetailOperation->setQuantiteActe($quantiteActe);

            /**
             * quantiteHeure = nbEtp * nbHeureMensuel
             */
            $leadDetailOperation->setQuantiteHeure($quantiteHeure);

            $leadDetailOperation->setProductiviteActe($arrayData['V'] ?? null);
            $leadDetailOperation->setPrixUnitaireHeure($arrayData['W'] ?? null);
            $leadDetailOperation->setPrixUnitaireActe($arrayData['X'] ?? null);
        } else {
            # Prix unitaire
            $arrayData['P'] && $leadDetailOperation->setPrixUnit($arrayData['P']);
        }
    }

    /**
     * @return array
     */
    private function getQuantityOfLignFact($operationId, $idTypeFacturation, $arrayData): array
    {
        $quantite = null;
        $quantiteActe = null;
        $quantiteHeure = null;

        $duree = $arrayData['T'] ?? null;
        $ressourceFormer = $arrayData['U'] ?? null;
        $nbHeureMensuel = $arrayData['M'] ?? null;
        $nbEtp = $arrayData['N'] ?? null;
        $volumeMensuel = $arrayData['O'] ?? null;
        $productiviteActe = $arrayData['V'] ?? null;

        switch($idTypeFacturation)
        {
            case 1: # Acte
                # Si type de facturation = Acte, alors quantite = volume à traiter
                $quantite = $volumeMensuel;
                break;

            case 3: # A l'heure
            case 5: # Forfait
                /*
                * Si operation = formation
                * alors quantité = durée de formation x nombre de ressource à former
                * Sinon, quantité = nbHeureMensuel x nbEtp
                */
                $quantite = $this->getQuantityForAlheureTypeFact($operationId, $duree, $ressourceFormer, $nbHeureMensuel, $nbEtp);
                break;

            case 4: # En regie forfaitaire
                # Si type de facturation = Acte, alors quantite = volume à traiter
                $quantite = 1;
            case 7: # Mixte
                $quantiteActe = $nbEtp * $nbHeureMensuel * $productiviteActe;
                $quantiteHeure = $nbEtp * $nbHeureMensuel;
                break;
            default:
                $quantite = 1;
                break;
        }

        return [$quantite, $quantiteActe, $quantiteHeure];
    }

    private function getQuantityForAlheureTypeFact($idOperation, $duree, $ressourceFormer, $nbHeureMensuel, $nbEtp) {
        if (in_array($idOperation, $this->getParameter('param_id_operation_formation'))) {
            if (!empty($duree) && !empty($ressourceFormer)){
                return $duree * $ressourceFormer;
            }
        } else {
            if (!empty($nbEtp) && !empty($nbHeureMensuel)){
                return $nbEtp * $nbHeureMensuel;
            }
        }
    }

    /**
     * @param $operation
     * @param $operationArray
     * Parametre les objectifs et ses indicateurs
     */
    private function setObjectifAndIndicator($operation, $operationArray): void
    {
        # Objectif et indicateur Quantitatif
        if ($operationArray["AE"] && $operationArray["AF"]){
            $decoupeQt = explode("; ", $operationArray["AE"]);
            $decoupeIndicateurQt = explode(";", $operationArray["AF"]);

            if (count($decoupeQt) == count($decoupeIndicateurQt)){
                foreach($decoupeQt As $index => $objectifQuantitatif)
                {
                    # Objectif
                    $objectifQt = $this->getDoctrine()->getRepository(ObjectifQuantitatif::class)->findOneBy([
                        "libelle" => $objectifQuantitatif
                    ]);

                    # Indicateur
                    if ($objectifQt){
                        $operation->addObjectifQuantitatif($objectifQt);

                        $indicatorQt = new IndicatorQuantitatif();

                        $indicatorQt->setObjectifQuantitatif($objectifQt);
                        $indicatorQt->setIndicator($decoupeIndicateurQt[$index] ?? null);
                        $indicatorQt->setUniqBdcFqOperation($uniqIdOperationLignFact ?? null);

                        $operation->addIndicatorQuantitatif($indicatorQt);
                    }
                }
            }
        }

        # Objectif et indicateur Qualitatif
        if ($operationArray["AC"] && $operationArray["AD"]){
            $decoupeQl = explode("; ", $operationArray["AC"]);
            $decoupeIndicateurQl = explode(";", $operationArray["AD"]);


            if (count($decoupeQl) == count($decoupeIndicateurQl)){
                foreach($decoupeQl As $index => $objectifQualitatif)
                {
                    # Objectif
                    $objectifQl = $this->getDoctrine()->getRepository(ObjectifQualitatif::class)->findOneBy([
                        "libelle" => $objectifQualitatif
                    ]);

                    # Indicateur
                    if ($objectifQl){
                        $operation->addObjectifQualitatif($objectifQl);

                        $indicatorQl = new IndicatorQualitatif();

                        $indicatorQl->setObjectifQualitatif($objectifQl);
                        $indicatorQl->setIndicator($decoupeIndicateurQl[$index] ?? null);
                        $indicatorQl->setUniqBdcFqOperation($uniqIdOperationLignFact ?? null);

                        $operation->addIndicatorQualitatif($indicatorQl);
                    }
                }
            }
        }
    }

    /**
     * @param $spreadsheet
     * @return array
     */
    private function getBdcAndOperationData($spreadsheet):array
    {
        # Supprime le premier ligne
        $spreadsheet->getSheet(0)->removeRow(1);
        $dataBdc = $spreadsheet->getSheet(0)->toArray(null, true, true, true);

        # Supprime le premier ligne
        $spreadsheet->getSheet(1)->removeRow(1);
        $dataOperation = $spreadsheet->getSheet(1)->toArray(null, true, true, true);

        return [$dataBdc, $dataOperation];
    }

    /**
     * @param $sheet
     * @param $tabColumn
     * Donne une largeur automatique pour chaque colonne
     */
    public function setAutoWidthForEachColumn($sheet, $tabColumn): void
    {
        foreach ($tabColumn as $columnLetter){
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
        }
    }

    /**
     * @param $sheet
     * @param $dataRef
     */
    public function setRefValue($sheet, $dataRef, $columnLetter): void
    {
        foreach ($dataRef as $index => $val){
            if ($val->getLibelle()){
                # Cellule Ex: A2 à En
                $cell = $columnLetter . ($index + 2);
                $value = $val->getLibelle();
                $sheet->setCellValue($cell, $value);
            }
        }
    }

    /**
     * @return array[]
     * Read the excel file
     */
    private function getFileDatas($spreadsheet): array
    {
        $nbFileTab = $spreadsheet->getSheetCount();

        $sheetDatas = [];

        for ($i = 0; $i < $nbFileTab; $i++) {
            # Supprime le premier ligne
            # $spreadsheet->getSheet($i)->removeRow(1);

            # Prend tout les cellules fusionnés
            $sheetDataMerge = $spreadsheet->getSheet($i)->getMergeCells();

            foreach ($sheetDataMerge as $index => $val) {
                # Decoupe chaque valeur dans le tableau (Ex: "A19:A54" en "A19" et "A54")
                $cellIndex = explode(":", $val);

                # Prend la premiere index (Ex: 19 pour "A19:A54")
                $startIndex = (int) substr($cellIndex[0],1);

                # Prend la derniere index (Ex: 54 pour "A19:A54")
                $endIndex = (int) substr($cellIndex[1],1);

                # Prend la lettre correspondant au colonne (Ex: "A" pour "A19:A54")
                $columnName = substr($cellIndex[0],0,1);

                # Contient la valeur du cellule fusionné
                $mainvalue = "";
                for ($j = $startIndex; $j<= $endIndex; $j++) {
                    # $res = $spreadsheet->getActiveSheet()->getCell($columnName.$j)->getValue();
                    $res = $spreadsheet->getSheet($i)->getCell($columnName.$j)->getValue();

                    if ($res != null) {
                        $mainvalue = $res;
                    }
                }

                # Copie le valeur du cellule fusionné dans chaque cellule qui a pour valeur égal null
                for ($k = $startIndex; $k<= $endIndex; $k++) {
                    # $res = $spreadsheet->getActiveSheet()->getCell($columnName.$k)->getValue();
                    $res = $spreadsheet->getSheet($i)->getCell($columnName.$k)->getValue();
                    if ($res == null) {
                        # $spreadsheet->getActiveSheet()->setCellValue($columnName.$k, $mainvalue);
                        $spreadsheet->getSheet($i)->setCellValue($columnName.$k, $mainvalue);
                    }
                }
            }

            # Supprime les valeurs null dans le tableau
            # $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $sheetData = $spreadsheet->getSheet($i)->toArray(null, true, true, true);
            $sheetData = array_values(array_map('array_filter', $sheetData));

            $sheetDatas[] = $sheetData;
        }

        return $sheetDatas;
    }

    /**
     * @param $sheet
     * @param $noCorrespondanceRasisonSocials
     * Ajouter tout les raisons sociales qui n'ont pas
     * de correspondance dans la colonne E de l'excel
     */
    private function setValueOfColumn($sheet, $datas, $column): void
    {
        foreach ($datas as $index => $val){
            # Cellule Ex: A2 à En
            $cell = $column . ($index + 2);
            $sheet->setCellValue($cell, $val);
        }
    }

    private function getTimeToNumber($time){
        $result = 0;
        if (!empty($time)){
            $decoupedTime = explode(":", $time);
            $result = $decoupedTime[0];
            $result += $decoupedTime[1] / 60;
            $result += $decoupedTime[2] / 3600;
        }

        return $result;
    }

    /**
     * Géneration d'un PDF pour le bon de commande en question
     * @param $bdc
     * @param $type
     * @param $avenant
     * Generation pdf bdc
     */
    private function setPdf(Bdc $bdc, $type, string $avenant = null, int $isBdcEnProd = null)
    {
        # Configure Dompdf according to your needs
        $pdfOptions = new Options();

        # $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->set('defaultFont', 'Arial');

        $textNmoins1Version = null;

        if ($isBdcEnProd == 1 && !empty($bdc->getNumVersion())){
            # Recuperer la version actuel
            $actualVersion = $this->getLastVersionOfBdc($bdc->getNumVersion());

            if ($actualVersion > 1){
                $nmoinsVersion = $actualVersion - 1;
                $textNmoins1Version = "Avenant qui annule et remplace le BDC numéro ". $bdc->getNumBdc() . " version " . "V" . $nmoinsVersion . " daté du : " . date_format($bdc->getDateCreate(), 'd/m/Y');
            }
        }

        # Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        # Retrieve the HTML generated in our twig file
        $totalHT = 0;
        $totalHT2 = 0;
        foreach($bdc->getBdcOperations() As $operation)
        {
            if ($operation->getOffert() != 1) {
                if (in_array($operation->getOperation()->getId(), $this->getParameter('param_id_operation_frais_mise_en_place_and_formation'))) {
                    if ($operation->getOffert() != 1) {
                        $totalHT += $operation->getPrixUnit() * $operation->getQuantite();
                    }
                } else {
                    if (!in_array($operation->getOperation()->getId(), $this->getParameter('param_id_operation_bonus_malus_frais_telecoms'))) {
                        /**
                         * Si ligne fact est mixte,
                         * $totalHT2 = (prixActe * qteActe) + (prixHeure * qteHeure)
                         * sinon, $totalHT2 = prixUnitaire * quantite
                         */
                        if ($operation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_mixte')){
                            $totalHT2 += ($operation->getPrixUnitaireActe() * $operation->getQuantiteActe()) + ($operation->getPrixUnitaireHeure() * $operation->getQuantiteHeure());
                        } else {
                            $totalHT2 += $operation->getPrixUnit() * $operation->getQuantite();
                        }
                    }
                }
            }
        }

        $tva = $bdc->getTva()->getLibelle();
        $tabTva = explode("%", $tva);
        $montantTva = $totalHT * $tabTva[0] / 100;
        $montantTva2 = $totalHT2 * $tabTva[0] / 100;

        # Logique avenant avant de faire renderView
        $valueAvenant = null;
        if ($avenant == "avenant") {
            $valueAvenant = "Avenant";
        }

        # Logique budget de frais de mise en place et budget de production mensuel
        $tabBudgetMisePlace = [];
        $tabBudgetProduction = [];
        foreach ($bdc->getBdcOperations() as $index) {
            if (in_array($index->getOperation()->getId(), $this->getParameter('param_id_operation_frais_mise_en_place_and_formation'))) {
                $tabBudgetMisePlace[] = $index;
            } else {
                if ($index->getOperation()->getId() != $this->getParameter('param_id_operation_malbon')){
                    $res = $this->getTypeFactMereForHno($bdc, $index->getOperation()->getId());
                    $data = [
                        "id" => $index->getId(),
                        "quantite" => $index->getQuantite(),
                        "prixUnit" => $index->getPrixUnit(),
                        "tarifHoraireCible" => $index->getTarifHoraireCible(),
                        "objectif" => $index->getObjectif(),
                        "tempsProductifs" => $index->getTempsProductifs(),
                        "dmt" => $index->getDmt(),
                        "tarifHoraireFormation" => $index->getTarifHoraireFormation(),
                        "volumeATraite" => $index->getVolumeATraite(),
                        "categorieLead" => $index->getCategorieLead(),
                        "operation" => $index->getOperation(),
                        "typeFacturation" => $index->getTypeFacturation(),
                        "familleOperation" => $index->getFamilleOperation(),
                        "bu" => $index->getBu(),
                        "objectifQualitatif" => $index->getObjectifQualitatif(),
                        "objectifQuantitatif" => $index->getObjectifQuantitatif(),
                        "coutHoraire" => $index->getCoutHoraire(),
                        "prodParHeure" => $index->getProdParHeure(),
                        "tarif" => $index->getTarif(),
                        "avenant" => $index->getAvenant(),
                        "description" => $index->getDescription(),
                        "isHnoDimanche" => $index->getIsHnoDimanche(),
                        "isHnoHorsDimanche" => $index->getIsHnoHorsDimanche(),
                        "majoriteHnoDimanche" => $index->getMajoriteHnoDimanche(),
                        "majoriteHnoHorsDimanche" => $index->getMajoriteHnoHorsDimanche(),
                        "valueHno" => $index->getValueHno(),
                        "offert" => $index->getOffert(),
                        "Duree" => $index->getDuree(),
                        "ressourceFormer" => $index->getRessourceFormer(),
                        "nbHeureMensuel" => $index->getNbHeureMensuel(),
                        "nbEtp" => $index->getNbEtp(),
                        "uniqBdcFqOperation" => $index->getUniqBdcFqOperation(),
                        "indicatorQuantitatifs" => $index->getIndicatorQuantitatifs(),
                        "indicatorQualitatifs" => $index->getIndicatorQualitatifs(),
                        "oldPrixUnit" => $index->getOldPrixUnit(),
                        "encodedImage" => $index->getEncodedImage(),
                        "productiviteActe" => $index->getProductiviteActe(),
                        "quantiteActe" => $index->getQuantiteActe(),
                        "quantiteHeure" => $index->getQuantiteHeure(),
                        "prixUnitaireActe" => $index->getPrixUnitaireActe(),
                        "prixUnitaireHeure" => $index->getPrixUnitaireHeure(),
                        "designationActe" => $index->getDesignationActe(),
                        "typeFactHnoMere" => $res ?? null
                    ];
                    $tabBudgetProduction[] = $data;
                }
            }
        }

        # Logique description si ligne de facturation = HNO
        $description = '';
        $bonusMalusImg = '';
        $tabHnoDescription =[];
        $tabHnoDescription [1] = "Tarif à l’acte de l'appel traité majorité de :";
        $tabHnoDescription [3] = "Tarif horaire de l'appel traité majorité de :";
        $tabHnoDescription [4] = "Tarif de l'appel traité majorité de :";
        foreach ($bdc->getBdcOperations() as $indice) {
            if ($indice->getIsHnoDimanche() == 1 || $indice->getIsHnoHorsDimanche() == 1) {
                switch ($indice->getTypeFacturation()->getId()) {
                    case 1:
                        # Type de facturation à l'acte
                        $description = "Tarif à l’acte de l'appel traité majorité de : ";
                        break;
                    case 3:
                        # Type à l'heure
                        $description = "Tarif horaire de l'appel traité majorité de : ";
                        break;
                    default:
                        $description = "Tarif de l'appel traité majorité de : ";
                }
            }

            if (!empty($indice->getEncodedImage())){
                $bonusMalusImg = $indice->getEncodedImage();
            }
        }

        if ($type == "client") {
            $html = $this->renderView('bdc.html.twig', [
                'bdc' => $bdc,
                'totalHT' => $totalHT,
                'totalHT2' => $totalHT2,
                'montantTva' => $montantTva,
                'montantTva2' => $montantTva2,
                'date_edit' => date("d/m/Y"),
                'textNmoins1Version' => $textNmoins1Version,
                'avenant' => $valueAvenant,
                'lignFactAll' => $bdc->getBdcOperations(),
                'tabBudgetMisePlace' => $tabBudgetMisePlace,
                'tabBudgetProduction' => $tabBudgetProduction,
                'descriptionLigneHno' => $description,
                'bonusMalusImg' => $bonusMalusImg ? ("data:image/jpeg;base64,".$bonusMalusImg) : null,
                'tabHnoDescription' => $tabHnoDescription,
                'nombre' => 0,
            ]);
        } else {
            $html = $this->renderView('bdc_interne.html.twig', [
                'bdc' => $bdc,
                'totalHT' => $totalHT,
                'montantTva' => $montantTva,
                'date_edit' => date("d/m/Y")
            ]);
        }
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');
        // Render the HTML as PDF
        $dompdf->render();

        $output = $dompdf->output();

        if ($type == "client") {
            if ($avenant != null) {
                file_put_contents($this->getParameter('bdc_dir') . 'bdc_avenant_' . $bdc->getIdMere() . '.pdf', $output);
            } else {
                file_put_contents($this->getParameter('bdc_dir') . 'bdc_' . $bdc->getIdMere() . '.pdf', $output);
            }
        } else {
            if ($avenant != null) {
                file_put_contents($this->getParameter('bdc_dir') . 'bdc_interne_avenant_' . $bdc->getIdMere() . '.pdf', $output);
            } else {
                file_put_contents($this->getParameter('bdc_dir') . 'bdc_interne_' . $bdc->getIdMere() . '.pdf', $output);
            }
        }

        $pdfOptions = new Options();

        # $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->set('defaultFont', 'Arial');

        $textNmoins1Version = null;

        if ($isBdcEnProd == 1 && !empty($bdc->getNumVersion())){
            # Recuperer la version actuel
            $actualVersion = $this->getLastVersionOfBdc($bdc->getNumVersion());

            if ($actualVersion > 1){
                $nmoinsVersion = $actualVersion - 1;
                $textNmoins1Version = "Avenant qui annule et remplace le BDC numéro ". $bdc->getNumBdc() . " version " . "V" . $nmoinsVersion . " daté du : " . date_format($bdc->getDateCreate(), 'd/m/Y');
            }
        }

        # Instantiate Dompdf with our options
        $dompdf2 = new Dompdf($pdfOptions);

        # Retrieve the HTML generated in our twig file
        $totalHT = 0;
        $totalHT2 = 0;
        foreach($bdc->getBdcOperations() As $operation)
        {
            if ($operation->getOffert() != 1) {
                if (in_array($operation->getOperation()->getId(), $this->getParameter('param_id_operation_frais_mise_en_place_and_formation'))) {
                    if ($operation->getOffert() != 1) {
                        $totalHT += $operation->getPrixUnit() * $operation->getQuantite();
                    }
                } else {
                    if (!in_array($operation->getOperation()->getId(), $this->getParameter('param_id_operation_bonus_malus_frais_telecoms'))) {
                        /**
                         * Si ligne fact est mixte,
                         * $totalHT2 = (prixActe * qteActe) + (prixHeure * qteHeure)
                         * sinon, $totalHT2 = prixUnitaire * quantite
                         */
                        if ($operation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_mixte')){
                            $totalHT2 += ($operation->getPrixUnitaireActe() * $operation->getQuantiteActe()) + ($operation->getPrixUnitaireHeure() * $operation->getQuantiteHeure());
                        } else {
                            $totalHT2 += $operation->getPrixUnit() * $operation->getQuantite();
                        }
                    }
                }
            }
        }

        $tva = $bdc->getTva()->getLibelle();
        $tabTva = explode("%", $tva);
        $montantTva = $totalHT * $tabTva[0] / 100;
        $montantTva2 = $totalHT2 * $tabTva[0] / 100;

        # Logique avenant avant de faire renderView
        $valueAvenant = null;
        if ($avenant == "avenant") {
            $valueAvenant = "Avenant";
        }

        # Logique budget de frais de mise en place et budget de production mensuel
        $tabBudgetMisePlace = [];
        $tabBudgetProduction = [];
        foreach ($bdc->getBdcOperations() as $index) {
            if (in_array($index->getOperation()->getId(), $this->getParameter('param_id_operation_frais_mise_en_place_and_formation'))) {
                $tabBudgetMisePlace[] = $index;
            } else {
                if ($index->getOperation()->getId() != $this->getParameter('param_id_operation_malbon')){
                    $res = $this->getTypeFactMereForHno($bdc, $index->getOperation()->getId());
                    $data = [
                        "id" => $index->getId(),
                        "quantite" => $index->getQuantite(),
                        "prixUnit" => $index->getPrixUnit(),
                        "tarifHoraireCible" => $index->getTarifHoraireCible(),
                        "objectif" => $index->getObjectif(),
                        "tempsProductifs" => $index->getTempsProductifs(),
                        "dmt" => $index->getDmt(),
                        "tarifHoraireFormation" => $index->getTarifHoraireFormation(),
                        "volumeATraite" => $index->getVolumeATraite(),
                        "categorieLead" => $index->getCategorieLead(),
                        "operation" => $index->getOperation(),
                        "typeFacturation" => $index->getTypeFacturation(),
                        "familleOperation" => $index->getFamilleOperation(),
                        "bu" => $index->getBu(),
                        "objectifQualitatif" => $index->getObjectifQualitatif(),
                        "objectifQuantitatif" => $index->getObjectifQuantitatif(),
                        "coutHoraire" => $index->getCoutHoraire(),
                        "prodParHeure" => $index->getProdParHeure(),
                        "tarif" => $index->getTarif(),
                        "avenant" => $index->getAvenant(),
                        "description" => $index->getDescription(),
                        "isHnoDimanche" => $index->getIsHnoDimanche(),
                        "isHnoHorsDimanche" => $index->getIsHnoHorsDimanche(),
                        "majoriteHnoDimanche" => $index->getMajoriteHnoDimanche(),
                        "majoriteHnoHorsDimanche" => $index->getMajoriteHnoHorsDimanche(),
                        "valueHno" => $index->getValueHno(),
                        "offert" => $index->getOffert(),
                        "Duree" => $index->getDuree(),
                        "ressourceFormer" => $index->getRessourceFormer(),
                        "nbHeureMensuel" => $index->getNbHeureMensuel(),
                        "nbEtp" => $index->getNbEtp(),
                        "uniqBdcFqOperation" => $index->getUniqBdcFqOperation(),
                        "indicatorQuantitatifs" => $index->getIndicatorQuantitatifs(),
                        "indicatorQualitatifs" => $index->getIndicatorQualitatifs(),
                        "oldPrixUnit" => $index->getOldPrixUnit(),
                        "encodedImage" => $index->getEncodedImage(),
                        "productiviteActe" => $index->getProductiviteActe(),
                        "quantiteActe" => $index->getQuantiteActe(),
                        "quantiteHeure" => $index->getQuantiteHeure(),
                        "prixUnitaireActe" => $index->getPrixUnitaireActe(),
                        "prixUnitaireHeure" => $index->getPrixUnitaireHeure(),
                        "designationActe" => $index->getDesignationActe(),
                        "typeFactHnoMere" => $res ?? null
                    ];
                    $tabBudgetProduction[] = $data;
                }
            }
        }

        # Logique description si ligne de facturation = HNO
        $description = '';
        $bonusMalusImg = '';
        $tabHnoDescription =[];
        $tabHnoDescription [1] = "Tarif à l’acte de l'appel traité majorité de :";
        $tabHnoDescription [3] = "Tarif horaire de l'appel traité majorité de :";
        $tabHnoDescription [4] = "Tarif de l'appel traité majorité de :";
        foreach ($bdc->getBdcOperations() as $indice) {
            if ($indice->getIsHnoDimanche() == 1 || $indice->getIsHnoHorsDimanche() == 1) {
                switch ($indice->getTypeFacturation()->getId()) {
                    case 1:
                        # Type de facturation à l'acte
                        $description = "Tarif à l’acte de l'appel traité majorité de : ";
                        break;
                    case 3:
                        # Type à l'heure
                        $description = "Tarif horaire de l'appel traité majorité de : ";
                        break;
                    default:
                        $description = "Tarif de l'appel traité majorité de : ";
                }
            }

            if (!empty($indice->getEncodedImage())){
                $bonusMalusImg = $indice->getEncodedImage();
            }
        }

        if ($type == "client") {
            $html = $this->renderView('bdc.html.twig', [
                'bdc' => $bdc,
                'totalHT' => $totalHT,
                'totalHT2' => $totalHT2,
                'montantTva' => $montantTva,
                'montantTva2' => $montantTva2,
                'date_edit' => date("d/m/Y"),
                'textNmoins1Version' => $textNmoins1Version,
                'avenant' => $valueAvenant,
                'lignFactAll' => $bdc->getBdcOperations(),
                'tabBudgetMisePlace' => $tabBudgetMisePlace,
                'tabBudgetProduction' => $tabBudgetProduction,
                'descriptionLigneHno' => $description,
                'tabHnoDescription' => $tabHnoDescription,
                'bonusMalusImg' => $bonusMalusImg ? ("data:image/jpeg;base64,".$bonusMalusImg) : null,
                'nombre' => $dompdf->getCanvas()->get_page_count()
            ]);
        } else {
            $html = $this->renderView('bdc_interne.html.twig', [
                'bdc' => $bdc,
                'totalHT' => $totalHT,
                'montantTva' => $montantTva,
                'date_edit' => date("d/m/Y")
            ]);
        }
        // Load HTML to Dompdf
        $dompdf2->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf2->setPaper('A4', 'portrait');
        // Render the HTML as PDF
        $dompdf2->render();

        $output = $dompdf2->output();

        if ($type == "client") {
            if ($avenant != null) {
                file_put_contents($this->getParameter('bdc_dir') . 'bdc_avenant_' . $bdc->getIdMere() . '.pdf', $output);
            } else {
                file_put_contents($this->getParameter('bdc_dir') . 'bdc_' . $bdc->getIdMere() . '.pdf', $output);
            }
        } else {
            if ($avenant != null) {
                file_put_contents($this->getParameter('bdc_dir') . 'bdc_interne_avenant_' . $bdc->getIdMere() . '.pdf', $output);
            } else {
                file_put_contents($this->getParameter('bdc_dir') . 'bdc_interne_' . $bdc->getIdMere() . '.pdf', $output);
            }
        }
    }

    private function getTypeFactMereForHno(Bdc $bdc, $idOperationHno)
    {
        $hnoMere = null;
        foreach ($bdc->getBdcOperations() as $lignFact){
            if ($lignFact->getOperation()->getId() == $idOperationHno && $lignFact->getValueHno() == "Oui"){
                $hnoMere = $lignFact->getTypeFacturation()->getId();
            }
        }

        return $hnoMere;
    }

    private function getLastVersionOfBdc(string $numVersion = null): int
    {
        $tmpNum = explode("_V", $numVersion);

        $tmpLastVersion = explode("_", $tmpNum[1]);

        # Dernière version
        return $tmpLastVersion[0];
    }

    private function getAndSendEmail($validator,$paysId,BdcRepository $repoBdc,UserRepository $repoUser,SendMailTo $sendMailTo,$userAll){
        $titre="Demande de validation des Bon De Commandes ";
        $allIdBdcNumBdc= $repoBdc->getBdcByValidator($validator,$paysId);
        if ($allIdBdcNumBdc){
            $message=$this->getTextMessage($allIdBdcNumBdc);
            if($paysId == -1){
                if($validator == "DIRFINANCE"){
                    foreach($userAll as $user){
                        foreach($user->getRoles() as $role){
                            if($role == "ROLE_FINANCE"){
                                $AllUserValidator[]=$user;
                            }
                        }
                    }
                }
                else{
                    foreach($userAll as $user){
                        foreach($user->getRoles() as $role){
                            if($role == "ROLE_DG"){
                                $AllUserValidator[]=$user;
                            }
                        }
                    }
                }
            }
            else{
                $AllUserValidator = $repoUser->findBy(["paysProduction" => $paysId]);
            }
            foreach($AllUserValidator as $userValidator){
                $sendMailTo->sendEmail($this->getParameter('from_email'), $userValidator->getEmail(),$titre, $message, null);
            }
        }
        return "envoyerAvec success";
    }
    private function getTextMessage($allIdBdcNumBdc){
        $ListeDeBdcMessage="Les Bon de commandes ci-dessous attendent votre validation SVP. <br> <br> ";
        foreach($allIdBdcNumBdc as $idBdcNumbdc){
            $ListeDeBdcMessage .= "- Bon de Commande <". $idBdcNumbdc['numBdc']."> <br> ";
        }
        $ListeDeBdcMessage.= "Merci de se connecter sur http://madacontact.com/crm_actuel pour passer aux validations SVP <br> <br>";
        $ListeDeBdcMessage.= "Cordialement, <br>";
        return $ListeDeBdcMessage;
    }
}
