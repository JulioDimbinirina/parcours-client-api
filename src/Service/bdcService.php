<?php
namespace App\Service;

use App\Repository\BdcOperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class bdcService {
    private $parameterBag;
    private $httpClient;

    public function __construct(ParameterBagInterface $parameterBag,HttpClientInterface $httpClient)
    {
        $this->parameterBag = $parameterBag;
        $this->httpClient = $httpClient;
    }

    public function setValueOfNumVersion(){
    }

    public function UpdateTarifSuivi($idBdc,$bdcRepository){
        try {
            # On recupère d'abord le bdc en question
            $bdc = $bdcRepository->find($idBdc);

            # Logique date de debut et date de fin
            $dateNow = new \DateTime();

            # Prendre le premier du mois en cours
            $dateDebut = date('Y-m-d', mktime(0, 0, 0, date("m"), 1, date("Y")));

            $dateFin = $dateNow->format("Y").'-'.'12'.'-'.'31';

            if (!empty($bdc)) {
                foreach ($bdc->getBdcOperations() as $ligneFacturation) {
                    /*
                    * Ne pas inséré les lignes bonus, malus, frais télécom
                    *  et Inséré uniquement les lignes de types à l’heure et à l’acte
                    */
                    if (!in_array($ligneFacturation->getOperation()->getId(), $this->parameterBag->get('param_id_operation_bonus_malus_frais_telecoms_2'))
                        && in_array($ligneFacturation->getTypeFacturation()->getId(), $this->parameterBag->get('param_id_type_facte_acte_heure'))){

                        # Logique tarif formation
                        $tarifFormation = null;
                        if ($ligneFacturation->getOperation()->getId() == $this->parameterBag->get('param_id_operation_formation_continue')) {
                            $tarifFormation = floatval($ligneFacturation->getPrixUnit());
                        }

                        $tarifActeDimanche = null;
                        $tarifHeureDimanche = null;
                        if ($ligneFacturation->getIsHnoDimanche() == 1){
                            # Logique tarif acte dimanche
                            if ($ligneFacturation->getTypeFacturation()->getId() == $this->parameterBag->get('param_id_type_fact_acte')){
                                $tarifActeDimanche = floatval($ligneFacturation->getPrixUnit());
                            }

                            # Logique tarif heure dimanche
                            if ($ligneFacturation->getTypeFacturation()->getId() == $this->parameterBag->get('param_id_type_fact_heure')){
                                $tarifHeureDimanche = floatval($ligneFacturation->getPrixUnit());
                            }
                        }

                        $tarifActeHorsDimanche = null;
                        $tarifHeureHorsDimanche = null;
                        if($ligneFacturation->getIsHnoHorsDimanche() == 1) {
                            # Logique tarif acte hors dimanche
                            if ($ligneFacturation->getIsHnoHorsDimanche() == 1 && $ligneFacturation->getTypeFacturation()->getId() == $this->parameterBag->get('param_id_type_fact_acte')){
                                $tarifActeHorsDimanche = floatval($ligneFacturation->getPrixUnit());
                            }

                            # Logique tarif heure hors dimanche
                            if ($ligneFacturation->getTypeFacturation()->getId() == $this->parameterBag->get('param_id_type_fact_heure')){
                                $tarifHeureHorsDimanche = floatval($ligneFacturation->getPrixUnit());
                            }
                        }

                        if ($ligneFacturation->getAvenant() == 1 || !empty($ligneFacturation->getApplicatifDate())) {
                            # Envoi des données vers api suivi-renta via de requette httpClient (for avenant)
                            $this->httpClient->request('POST', $this->parameterBag->get('param_inject_tarif_in_suivirenta_url'), [
                                'body' => [
                                    'pays' => $bdc->getPaysProduction()->getLibelle(),
                                    'bu' => $ligneFacturation->getBu()->getLibelle(),
                                    'client' => $bdc->getResumeLead()->getCustomer()->getRaisonSocial(),
                                    'operation' => $ligneFacturation->getOperation()->getLibelle(),
                                    'date_debut' => !empty($ligneFacturation->getApplicatifDate()) ? date_format($ligneFacturation->getApplicatifDate(), "Y-m-d") : $dateDebut,
                                    'date_fin' => $dateFin,
                                    'tarifheure' => ($ligneFacturation->getTypeFacturation()->getId() == $this->parameterBag->get('param_id_type_fact_heure')) ? floatval($ligneFacturation->getPrixUnit()) : null,
                                    'tarifacte' => ($ligneFacturation->getTypeFacturation()->getId() == $this->parameterBag->get('param_id_type_fact_acte')) ? floatval($ligneFacturation->getPrixUnit()) : null,
                                    'tarifformation' => $tarifFormation,
                                    'tarifheuredimanche' => $tarifHeureDimanche,
                                    'tarifactedimanche' => $tarifActeDimanche,
                                    'tarifformationdimanche' => null,
                                    'tarifheurehorsdimanche' => $tarifHeureHorsDimanche,
                                    'tarifactehorsdimanche' => $tarifActeHorsDimanche
                                ]
                            ]);
                        } else {
                            # Envoi des données vers api suivi-renta via de requette httpClient
                            if (!empty($ligneFacturation->getBu()) && !empty($ligneFacturation->getOperation())) {
                                $res = $this->httpClient->request('POST', $this->parameterBag->get('param_inject_tarif_in_suivirenta_url'), [
                                    'body' => [
                                        'pays' => $bdc->getPaysProduction()->getLibelle(),
                                        'bu' => $ligneFacturation->getBu()->getLibelle(),
                                        'client' => $bdc->getResumeLead()->getCustomer()->getRaisonSocial(),
                                        'operation' => $ligneFacturation->getOperation()->getLibelle(),
                                        'date_debut' => $dateDebut,
                                        'date_fin' => $dateFin,
                                        'tarifheure' => ($ligneFacturation->getTypeFacturation()->getId() == $this->parameterBag->get('param_id_type_fact_heure')) ? floatval($ligneFacturation->getPrixUnit()) : "",
                                        'tarifacte' => ($ligneFacturation->getTypeFacturation()->getId() == $this->parameterBag->get('param_id_type_fact_acte')) ? floatval($ligneFacturation->getPrixUnit()) : "",
                                        'tarifformation' => $tarifFormation,
                                        'tarifheuredimanche' => $tarifHeureDimanche,
                                        'tarifactedimanche' => $tarifActeDimanche,
                                        'tarifformationdimanche' => "",
                                        'tarifheurehorsdimanche' => $tarifHeureHorsDimanche,
                                        'tarifactehorsdimanche' => $tarifActeHorsDimanche
                                    ]
                                ]);
                            }
                        }
                    }
                }
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        } 
    }
}