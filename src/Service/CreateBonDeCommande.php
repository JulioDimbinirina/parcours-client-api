<?php

namespace App\Service;

use App\Entity\Bdc;
use App\Entity\BdcOperation;
use App\Entity\Bu;
use App\Entity\CoutHoraire;
use App\Entity\FamilleOperation;
use App\Entity\HoraireProduction;
use App\Entity\IndicatorQualitatif;
use App\Entity\IndicatorQuantitatif;
use App\Entity\LangueTrt;
use App\Entity\LeadDetailOperation;
use App\Entity\ObjectifQualitatif;
use App\Entity\ObjectifQuantitatif;
use App\Entity\Operation;
use App\Entity\PaysFacturation;
use App\Entity\PaysProduction;
use App\Entity\ResumeLead;
use App\Entity\SocieteFacturation;
use App\Entity\TypeFacturation;
use App\Repository\BdcRepository;
use App\Repository\BuRepository;
use App\Repository\CoutHoraireRepository;
use App\Repository\FamilleOperationRepository;
use App\Repository\HoraireProductionRepository;
use App\Repository\LangueTrtRepository;
use App\Repository\ObjectifQualitatifRepository;
use App\Repository\ObjectifQuantitatifRepository;
use App\Repository\OperationRepository;
use App\Repository\PaysFacturationRepository;
use App\Repository\PaysProductionRepository;
use App\Repository\ResumeLeadRepository;
use App\Repository\SocieteFacturationRepository;
use App\Repository\StatusLeadRepository;
use App\Repository\TypeFacturationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CreateBonDeCommande
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * @var BdcRepository
     */
    private $bdcRepository;

    /**
     * @var ResumeLeadRepository
     */
    private $resumeLeadRepository;

    /**
     * @var PaysProductionRepository
     */
    private $paysProductionRepository;

    /**
     * @var PaysFacturationRepository
     */
    private $paysFacturationRepository;

    /**
     * @var SocieteFacturationRepository
     */
    private $societeFacturationRepository;

    /**
     * @var BuRepository
     */
    private $buRepository;

    /**
     * @var OperationRepository
     */
    private $operationRepository;

    /**
     * @var FamilleOperationRepository
     */
    private $familleOperationRepository;

    /**
     * @var LangueTrtRepository
     */
    private $langueTrtRepository;

    /**
     * @var TypeFacturationRepository
     */
    private $typeFacturationRepository;

    /**
     * @var CoutHoraireRepository
     */
    private $coutHoraireRepository;

    /**
     * @var HoraireProductionRepository
     */
    private $horaireProductionRepository;

    /**
     * @var ObjectifQualitatifRepository
     */
    private $objectifQualitatifRepository;

    /**
     * @var ObjectifQuantitatifRepository
     */
    private $objectifQuantitatifRepository;


    public function __construct(EntityManagerInterface $manager,
                                ParameterBagInterface $parameterBag,
                                BdcRepository $bdcRepository,
                                ResumeLeadRepository $resumeLeadRepository,
                                PaysProductionRepository $paysProductionRepository,
                                PaysFacturationRepository $paysFacturationRepository,
                                SocieteFacturationRepository $societeFacturationRepository,
                                BuRepository $buRepository,
                                OperationRepository $operationRepository,
                                FamilleOperationRepository $familleOperationRepository,
                                LangueTrtRepository $langueTrtRepository,
                                TypeFacturationRepository $typeFacturationRepository,
                                CoutHoraireRepository $coutHoraireRepository,
                                HoraireProductionRepository $horaireProductionRepository,
                                ObjectifQualitatifRepository $objectifQualitatifRepository,
                                ObjectifQuantitatifRepository $objectifQuantitatifRepository)
    {
        $this->manager = $manager;
        $this->parameterBag = $parameterBag;
        $this->bdcRepository = $bdcRepository;
        $this->resumeLeadRepository = $resumeLeadRepository;
        $this->paysProductionRepository = $paysProductionRepository;
        $this->paysFacturationRepository = $paysFacturationRepository;
        $this->societeFacturationRepository = $societeFacturationRepository;
        $this->buRepository = $buRepository;
        $this->operationRepository = $operationRepository;
        $this->familleOperationRepository = $familleOperationRepository;
        $this->langueTrtRepository = $langueTrtRepository;
        $this->typeFacturationRepository = $typeFacturationRepository;
        $this->coutHoraireRepository = $coutHoraireRepository;
        $this->horaireProductionRepository = $horaireProductionRepository;
        $this->objectifQualitatifRepository = $objectifQualitatifRepository;
        $this->objectifQuantitatifRepository = $objectifQuantitatifRepository;
    }

    /**
     * @param ResumeLead $resumeLead
     * @param $resumeLeadArray
     * @param $oneOperationArray
     * @return Bdc
     * @throws \Exception
     */
    public function NewBonCommandeForNewLignFact(ResumeLead $resumeLead, $resumeLeadArray, $oneOperationArray): Bdc
    {
        $paysProdBdc = $oneOperationArray[0]['paysProduction'];
        $paysFactBdc = $oneOperationArray[0]['paysFacturation'];

        $bdc = new Bdc();
        $bdc->setDateDebut($resumeLeadArray["dateDebut"] ? new \DateTime($resumeLeadArray["dateDebut"]) : null);
        // $bdc->setAdresseFacturation($dataUpdate->getAdresseFacturation() ?? null);
        // $bdc->setCdc($dataUpdate->getCdc() ?? null);
        // $bdc->setCgv($dataUpdate->getCgv() ?? null);
        $bdc->setDateCreate(new \DateTime());
        $bdc->setDateModification(null);
        $bdc->setDateFin(null);

        # Liste de diffusion BDC
        $listeDiffusion = "";
        foreach($resumeLead->getCustomer()->getContacts() As $contact)
        {
            if($contact->getIsCopieFacture())
            {
                $listeDiffusion .= $contact->getEmail() . ";";
            }
        }
        $bdc->setDiffusions($listeDiffusion);

        $bdc->setPaysProduction($this->paysProductionRepository->find($paysProdBdc));
        $bdc->setTitre('Titre bon de commande');
        $bdc->setResumePrestation($resumeLeadArray['resumePrestation'] ?? null);
        $bdc->setResumeLead($resumeLead);
        $bdc->setPaysFacturation($this->paysFacturationRepository->find($paysFactBdc));
        $bdc->setSocieteFacturation(null);
        $bdc->setStatutClient(null);
        $bdc->setUniqId(uniqid());

        $this->manager->persist($bdc);
        $this->manager->flush();

        return $bdc;
    }

    /**
     * @param Bdc $currentBdc
     * @param $operationArray
     */
    public function createManuelleLignFactAndLeadDetailOperation(Bdc $currentBdc, $operationArray): array
    {

        $langTrtArray = [];
        foreach ($operationArray as $item){
            # Creation du leadDetailOperation
            $this->newLeadDetailOperation($currentBdc->getResumeLead(), $item);

            # Creation des lignes de facturation Manuelle et HNO
            $this->createLigneFacturation($currentBdc, $item);

            # Pour l'ajout des lignes facturations formation par langue de traitement.
            !in_array(intval($item['langueTrt']), $langTrtArray) && $langTrtArray[] = intval($item['langueTrt']);
        }

        return $langTrtArray;
    }

    /**
     * Ajout nouvelle lead detail operation du FQ par rapport à la modification du BDC
     * Modification bdc (Cas ajout nouvelle ligne de facturation)
     */
    private function newLeadDetailOperation(ResumeLead $resumeLead, $frontOperation): void
    {
        # On va créer une nouvelle lead detail operation venant du bdc
        $leadDetailOperation = new LeadDetailOperation();

        $leadDetailOperation->setPaysProduction($this->paysProductionRepository->find($frontOperation['paysProduction']));
        $leadDetailOperation->setPaysFacturation($this->paysFacturationRepository->find($frontOperation['paysFacturation']));

        $leadDetailOperation->setPrixUnit($frontOperation['prixUnit'] ?? null);

        # Champ obligatoire
        $leadDetailOperation->setLangueTrt($this->langueTrtRepository->find($frontOperation['langueTrt']));

        $leadDetailOperation->setCategorieLead($frontOperation['categorieLead'] ?? null);
        $leadDetailOperation->setVolumeATraite($frontOperation['volumeATraite'] ?? null);
        $leadDetailOperation->setNbHeureMensuel($frontOperation['nbHeureMensuel'] ?? null);
        $leadDetailOperation->setNbEtp($frontOperation['nbEtp'] ?? null);

        # Champ obligatoire
        $leadDetailOperation->setBu($this->buRepository->find($frontOperation['bu']));
        $leadDetailOperation->setTypeFacturation($this->typeFacturationRepository->find($frontOperation['typeFacturation']));
        $leadDetailOperation->setOperation($this->operationRepository->find($frontOperation['operation']));
        $leadDetailOperation->setHoraireProduction($this->horaireProductionRepository->find($frontOperation['horaireProduction']) ?? null);

        $leadDetailOperation->setTempsProductifs($frontOperation['tempsProductifs'] ?? null);
        $leadDetailOperation->setTarifHoraireCible($frontOperation['tarifHoraireCible'] ?? null);
        $leadDetailOperation->setDmt($frontOperation['dmt'] ?? null);
        $leadDetailOperation->setCoutHoraire($this->coutHoraireRepository->find($frontOperation['coutHoraire'] ?? null));

        if (intval($frontOperation['typeFacturation']) === $this->parameterBag->get('param_id_type_fact_mixte')){
            $leadDetailOperation->setProductiviteActe($frontOperation['productiviteActe'] ?? null);
            $leadDetailOperation->setPrixUnitaireActe($frontOperation['prixUnitaireActe'] ?? null);
            $leadDetailOperation->setPrixUnitaireHeure($frontOperation['prixUnitaireHeure'] ?? null);

            if (isset($frontOperation['designationActe'])) {
                $leadDetailOperation->setDesignationActe($this->operationRepository->find($frontOperation['designationActe']) ?? null);
            }
        }

        # Champ obligatoire
        $leadDetailOperation->setFamilleOperation($this->familleOperationRepository->find($frontOperation['familleOperation']));

        if (isset($frontOperation['uniq'])) {
            $leadDetailOperation->setUniqBdcFqOperation($frontOperation['uniq']);
        }

        # Logique exceptionnel pour les deux objectifs
        if (!empty($frontOperation['objectifQualitatif'])) {
            foreach ($frontOperation['objectifQualitatif'] as $objQual) {
                $objectifQual = $this->objectifQualitatifRepository->find($objQual);
                $leadDetailOperation->addObjectifQualitatif($objectifQual);
            }
        }
        if (!empty($ligneFact['objectifQuantitatif'])) {
            foreach ($ligneFact['objectifQuantitatif'] as $objQuant) {
                $objectifQuantitatif = $this->objectifQuantitatifRepository->find($objQuant);
                $leadDetailOperation->addObjectifQuantitatif($objectifQuantitatif);
            }
        }

        $leadDetailOperation->setResumeLead($resumeLead);

        $this->manager->persist($leadDetailOperation);
    }

    /**
     * @param Bdc $currentBdc
     * @param $item
     */
    private function createLigneFacturation(Bdc $currentBdc, $item){
        # Creation du ligne de facturation Manuelle
        $this->CreateManuelleLigneFacturation($currentBdc, $item);

        if (isset($item["hno"]) && $item["hno"] == "Oui"){
            # Creation du ligne de facturation HNO
            $this->saveLigneFacturationHno($currentBdc, $item);
        }
    }

    /**
     * @param Bdc $bdc
     * @param $item
     */
    public function CreateManuelleLigneFacturation(Bdc $bdc, $item) {
        $dataOperation = new BdcOperation();

        $dataOperation->setTarif(null);
        $dataOperation->setCategorieLead($item['categorieLead'] ?? null);
        $dataOperation->setDmt($item['dmt'] ?? null);
        $dataOperation->setObjectif($item['objectif'] ?? null);
        $dataOperation->setProdParHeure(null);
        $dataOperation->setTarifHoraireCible($item['tarifHoraireCible'] ?? null);
        $dataOperation->setTarifHoraireFormation($item['tarifHoraireFormation'] ?? null);
        $dataOperation->setTempsProductifs($item['tempsProductifs'] ?? null);
        $dataOperation->setVolumeATraite($item['volumeATraite'] ?? null);
        $dataOperation->setNbHeureMensuel($item['nbHeureMensuel'] ?? null);
        $dataOperation->setNbEtp($item['nbEtp'] ?? null);
        $dataOperation->setValueHno($item['hno'] ?? null);
        $dataOperation->setIsParamPerformed(1);

        if (isset($item['bu'])) {
            $dataOperation->setBu($this->buRepository->find($item['bu']));
        }

        if (isset($item['operation'])) {
            $dataOperation->setOperation($this->operationRepository->find($item['operation']));
        }

        if (isset($item['familleOperation'])) {
            $familleOperation = $this->familleOperationRepository->find($item['familleOperation']);

            # Donner la valeur aux irm, siRenta, sage en fonction du valeur qui se trouve dans famille operation
            if (!empty($familleOperation)){
                $dataOperation->setSiRenta($familleOperation->getIsSiRenta() ?? null);
                $dataOperation->setIrm($familleOperation->getIsIrm() ?? null);
                $dataOperation->setSage($familleOperation->getIsSage() ?? null);

                $dataOperation->setFamilleOperation($familleOperation);
            }
        }

        if (isset($item['designationActe'])){
            $dataOperation->setDesignationActe($this->operationRepository->find($item['designationActe']) ?? null);
        }

        if (isset($item['langueTrt'])) {
            $dataOperation->setLangueTrt($this->langueTrtRepository->find($item['langueTrt']));
        }

        if (isset($item['typeFacturation'])) {
            $dataOperation->setTypeFacturation($this->typeFacturationRepository->find($item['typeFacturation']));

            # Calcule Prix Unitaire et Quantité
            $this->setPrixUnitaireAndQuantity($dataOperation, $item);
        }

        if (isset($item['coutHoraire'])) {
            $dataOperation->setCoutHoraire($this->coutHoraireRepository->find($item['coutHoraire']));
        }

        if (isset($item['uniq'])) {
            $dataOperation->setUniqBdcFqOperation($item['uniq']);
        }

        if (!empty($item['objectifQuantitatif'])) {
            foreach ($item['objectifQuantitatif'] as $objQtf) {
                $dataOperation->addObjectifQuantitatif($this->objectifQuantitatifRepository->find($objQtf));
            }
        }
        if (!empty($item['objectifQualitatif'])) {
            foreach ($item['objectifQualitatif'] as $objQtt) {
                $dataOperation->addObjectifQualitatif($this->objectifQualitatifRepository->find($objQtt));
            }
        }

        # Ajouter ses objectifs et ses indicateurs dans la table IndicatorQualitatif
        if (!empty($item['indicateurQl'])){
            foreach ($item['indicateurQl'] as $indicQl) {
                $indicatorQl = new IndicatorQualitatif();

                $indicatorQl->setObjectifQualitatif($this->objectifQualitatifRepository->find($indicQl['objectifQl']));
                $indicatorQl->setIndicator($indicQl['indicator'] ?? null);
                $indicatorQl->setUniqBdcFqOperation($value['uniq'] ?? null);

                $dataOperation->addIndicatorQualitatif($indicatorQl);
            }
        }

        # Ajouter ses objectifs et ses indicateurs dans la table IndicatorQuantitatif
        if (!empty($item['indicateurQt'])){
            foreach ($item['indicateurQt'] as $indicQt) {
                $indicatorQt = new IndicatorQuantitatif();

                $indicatorQt->setObjectifQuantitatif($this->objectifQuantitatifRepository->find($indicQt['objectifQt']));
                $indicatorQt->setIndicator($indicQt['indicator'] ?? null);
                $indicatorQt->setUniqBdcFqOperation($value['uniq'] ?? null);

                $dataOperation->addIndicatorQuantitatif($indicatorQt);
            }
        }

        $dataOperation->setBdc($bdc);

        $this->manager->persist($dataOperation);
        $this->manager->flush();
    }

    /**
     * @param BdcOperation $bdcOperation
     * Met à jour le prix unitaire d'une ligne de facturation
     * HNO ou non et ainsi que les quantité
     */
    public function setPrixUnitaireAndQuantity(BdcOperation $bdcOperation, $ligneFacturationFront){
        list($quantite, $quantiteActe, $quantiteHeure) = $this->getQuantityOfLignFact($bdcOperation, $ligneFacturationFront);

        # Si type de facturation est égal à acte, alors on refait le calcul du prix unit à partir du tarifHoraireCible, tempsProductifs, et dmt
        if ($bdcOperation->getTypeFacturation()->getId() == $this->parameterBag->get('param_id_type_fact_acte')){
            # On met à jour les tarifHoraireCible, tempsProductifs, dmt et prix unitaire
            $bdcOperation->setTarifHoraireCible($ligneFacturationFront['tarifHoraireCible'] ?? null);
            $bdcOperation->setTempsProductifs($ligneFacturationFront['tempsProductifs'] ?? null);
            $bdcOperation->setDmt($ligneFacturationFront['dmt'] ?? null);
            $bdcOperation->setPrixUnit($ligneFacturationFront['prixUnit'] ?? null);

            $bdcOperation->setQuantite($quantite);
        } elseif ($bdcOperation->getTypeFacturation()->getId() == $this->parameterBag->get('param_id_type_fact_mixte')) {
            /**
             * quantiteActe = (nbEtp * nbHeureMensuel) * productiviteActe
             */
            $bdcOperation->setQuantiteActe($quantiteActe ?? null);

            /**
             * quantiteHeure = nbEtp * nbHeureMensuel
             */
            $bdcOperation->setQuantiteHeure($quantiteHeure ?? null);

            $bdcOperation->setProductiviteActe($ligneFacturationFront['productiviteActe'] ?? null);
            $bdcOperation->setPrixUnitaireHeure($ligneFacturationFront['prixUnitaireHeure'] ?? null);
            $bdcOperation->setPrixUnitaireActe($ligneFacturationFront['prixUnitaireActe'] ?? null);
        } else {
            # Si le type de facturation n'est pas acte, alors on met à jour directement son prix unitaire via le prix unitaire venant du front.
            $bdcOperation->setPrixUnit($ligneFacturationFront['prixUnit'] ?? null);
            $bdcOperation->setQuantite($quantite ?? null);
        }
    }

    /**
     * @param BdcOperation $bdcOperation
     * @param $jsonResponse
     * @return array
     */
    public function getQuantityOfLignFact(BdcOperation $bdcOperation, $jsonResponse = null): array
    {
        $quantite = null;
        $quantiteActe = null;
        $quantiteHeure = null;

        $operationId = $bdcOperation->getOperation()->getId();

        $duree = $jsonResponse['duree'] ?? null;
        $ressourceFormer = $jsonResponse['ressourceFormer'] ?? null;
        $nbHeureMensuel = $jsonResponse['nbHeureMensuel'] ?? null;
        $nbEtp = $jsonResponse['nbEtp'] ?? null;
        $volumeMensuel = $jsonResponse['volumeATraite'] ?? null;
        $productiviteActe = $jsonResponse['productiviteActe'] ?? null;

        switch($bdcOperation->getTypeFacturation()->getId())
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
                $quantiteActe = ($nbEtp * $nbHeureMensuel) * $productiviteActe;
                $quantiteHeure = $nbEtp * $nbHeureMensuel;
                break;
            default:
                $quantite = 1;
                break;
        }

        return [$quantite, $quantiteActe, $quantiteHeure];
    }

    private function getQuantityForAlheureTypeFact($idOperation, $duree, $ressourceFormer, $nbHeureMensuel, $nbEtp)
    {
        if (in_array($idOperation, $this->parameterBag->get('param_id_operation_formation'))) {
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
     * @param $idBdc
     * Ajout nouvelle ligne de facturation HNO (dimance et hors dimanche)
     */
    private function saveLigneFacturationHno($idBdc, $item) {

        # On a besoin l'id du bon de commande en question
        $bonDeCommande = $this->bdcRepository->find($idBdc);

        $nbLignFactHno = intval($item['typeFacturation']) == $this->parameterBag->get('param_id_type_fact_mixte') ? 4 : 2;

        # Ajout des lignes des facturations HNO pour typeFact mixte (nb = 4)
        for ($j = 0; $j < $nbLignFactHno; $j++) {
            list($facturationType, $hnoHorsDimanche, $hnoDimanche) = $this->typeFactValueForHnoLigneFact($item, $nbLignFactHno, $j);

            $operationBdc = new BdcOperation();
            $operationBdc->setOperation($this->operationRepository->find($item['operation']));
            $operationBdc->setTypeFacturation($this->typeFacturationRepository->find($facturationType ?? null));
            $operationBdc->setBu($this->buRepository->find($item['bu'] ?? null));
            $operationBdc->setLangueTrt($this->langueTrtRepository->find($item['langueTrt'] ?? null));
            $operationBdc->setCategorieLead($item['categorieLead'] ?? null);
            $operationBdc->setIsHnoDimanche($hnoDimanche);
            $operationBdc->setIsHnoHorsDimanche($hnoHorsDimanche);
            $operationBdc->setIsParamPerformed(0);
            $operationBdc->setBdc($bonDeCommande);

            $this->manager->persist($operationBdc);
            $this->manager->flush();
        }
    }

    /**
     * @param int|null $elem
     * @return array
     * Retourne les valeurs de type de facturation HNO
     * et determine s'il est hno hors dimanche ou dimanche
     */
    private function typeFactValueForHnoLigneFact($item, $nbLignFactHno, int $index = null): array
    {
        $facturationType = null;
        $hnoHorsDimanche = null;
        $hnoDimanche = null;

        if ($nbLignFactHno == 4){
            # cas mixte
            switch ($index)
            {
                case 0: # HNO hors dimanche type Acte
                    $facturationType = $this->parameterBag->get('param_id_type_fact_acte');
                    $hnoHorsDimanche = 1;
                    break;
                case 1: # HNO dimanche type Acte
                    $facturationType = $this->parameterBag->get('param_id_type_fact_acte');
                    $hnoDimanche = 1;
                    break;
                case 2: # HNO hors dimanche type heure
                    $facturationType = $this->parameterBag->get('param_id_type_fact_heure');
                    $hnoHorsDimanche = 1;
                    break;
                case 3: # HNO dimanche type Acte
                    $facturationType = $this->parameterBag->get('param_id_type_fact_heure');
                    $hnoDimanche = 1;
                    break;
            }
        } else {
            # Cas qui n'est pas mixte, avec le meme type de facturation que celle du mère
            switch ($index)
            {
                case 0: # HNO hors dimanche
                    $facturationType = $item['typeFacturation'];
                    $hnoHorsDimanche = 1;
                    break;
                case 1: # HNO dimanche
                    $facturationType = $item['typeFacturation'];
                    $hnoDimanche = 1;
                    break;
            }
        }

        return array($facturationType, $hnoHorsDimanche, $hnoDimanche);
    }

    /**
     * @param $idBdc
     * Ajout automatique des opérations Panne technique DO, Panne technique outsourcia, Regule et .........
     */
    public function ajoutOperationAutomatique(Bdc $createdBdc, $FrontOperation, $langTrtArray) {

        # Ajout des operation Formation et PanneTechnique
        $this->AjoutOperationFormationEtPanneTechnique($createdBdc, $FrontOperation, $langTrtArray);

        # Logique ajout des operations automatique
        $this->AjoutOperationAuto($createdBdc);
    }

    private function AjoutOperationFormationEtPanneTechnique(Bdc $createdBdc, $FrontOperation, $langTrtArray): void
    {
        # Ajout operation formation et panne technique uniquement pour langue de traiment ajouté
        $tabIdOperationFormationAndPanneTech = $this->parameterBag->get('param_id_operation_formation_panne_technique');

        foreach ($tabIdOperationFormationAndPanneTech as $formationAndPanneTech) {
            foreach ($langTrtArray as $langTrt){
                $ligneFacturation = new BdcOperation();
                $ligneFacturation->setTypeFacturation($this->typeFacturationRepository->find($this->parameterBag->get("param_id_type_fact_heure")));
                $ligneFacturation->setBu($this->buRepository->find($FrontOperation[0]["bu"]));
                $ligneFacturation->setLangueTrt($this->langueTrtRepository->find($langTrt));
                $ligneFacturation->setOperation($this->operationRepository->find($formationAndPanneTech));
                $ligneFacturation->setBdc($createdBdc);
                $ligneFacturation->setIsParamPerformed(0);

                $this->manager->persist($ligneFacturation);
                $this->manager->flush();
            }
        }
    }

    /**
     * @param $dataBdc
     */
    private function AjoutOperationAuto($dataBdc): void
    {
        $tabIdOperation = $this->parameterBag->get('param_id_operation_automatique');

        for ($k = 0; $k < sizeof($tabIdOperation); $k++) {
            $ligneFacturationAuto = new BdcOperation();

            # On va faire de switch ici pour connaitre le type facturation de l'opération
            $ligneFacturationAuto->setOperation($this->operationRepository->find($tabIdOperation[$k]));
            $typeFact = $this->getTypeFactForLignFactAuto($tabIdOperation[$k]);

            switch ($typeFact) {
                case "Heure":
                    $ligneFacturationAuto->setTypeFacturation($this->typeFacturationRepository->find($this->parameterBag->get('param_id_type_fact_heure')));
                    break;
                case "Acte":
                    $ligneFacturationAuto->setTypeFacturation($this->typeFacturationRepository->find($this->parameterBag->get('param_id_type_fact_acte')));
                    $ligneFacturationAuto->setPrixUnit(1);
                    break;
                case "Forfait":
                    $ligneFacturationAuto->setTypeFacturation($this->typeFacturationRepository->find($this->parameterBag->get('param_id_type_fact_forfait')));
                    break;
            }

            $ligneFacturationAuto->setBdc($dataBdc);
            $ligneFacturationAuto->setIsParamPerformed(0);

            $this->manager->persist($ligneFacturationAuto);
            $this->manager->flush();
        }
    }

    /**
     * @param $operation
     * @return string|void
     * Retourne le type facturation pour chaque ligne de facturation automatique
     */
    private function getTypeFactForLignFactAuto($operation){
        if (in_array($operation, $this->parameterBag->get('param_lign_fact_auto_type_heure'))){
            return "Heure";
        } elseif (in_array($operation, $this->parameterBag->get('param_lign_fact_auto_type_acte'))) {
            return "Acte";
        } elseif (in_array($operation, $this->parameterBag->get('param_lign_fact_auto_type_forfait'))) {
            return "Forfait";
        }
    }

    /**
     * Function that groups an array of associative arrays by some key.
     * @param {String} $key Property to sort by.
     * @param {Array} $data Array that stores multiple associative arrays.
     */
    public function regroupOperation($key, $data): array
    {
        $result = [];

        foreach($data as $val) {
            array_key_exists($key, $val) && $result[$val[$key]][] = $val;
        }

        return $result;
    }
}