<?php

namespace App\Controller;

use App\Entity\Bdc;
use App\Entity\BdcOperation;
use App\Entity\CategorieClient;
use App\Entity\Customer;
use App\Entity\HistoriqueContrat;
use App\Entity\User;
use App\Repository\CustomerRepository;
use App\Repository\BdcOperationRepository;
use App\Repository\BdcRepository;
use App\Repository\ContactRepository;
use App\Repository\CategorieClientRepository;
use App\Repository\ContratRepository;
use App\Repository\HausseIndiceSyntecClientRepository;
use App\Repository\UserRepository;
use App\Service\InjectCoutInSuivirenta;
use App\Service\Lead;
use App\Service\SendMailTo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportBdcSigneController extends AbstractController
{
    /**
     * @var InjectCoutInSuivirenta
     */
    private $injectCoutInSuivirenta;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var BdcOperationRepository
     */
    private $bdcOperationRepository;

    private $entityManager;

    public function __construct(InjectCoutInSuivirenta $injectCoutInSuivirenta,
                                HttpClientInterface $httpClient,
                                BdcOperationRepository $bdcOperationRepository,
                                EntityManagerInterface $entityManager){
        $this->injectCoutInSuivirenta = $injectCoutInSuivirenta;
        $this->httpClient = $httpClient;
        $this->bdcOperationRepository = $bdcOperationRepository;
        $this->entityManager = $entityManager;
    }
     /**
     * @Route("/import/send/email/juriste", name="sendEmailJuriste", methods={"GET"})
     */
    public function sendEmailJuriste(SendMailTo $sendMailTo, UserRepository $repoUser) {
        # Test Premier Janvier
        $reponseInsomnia= "il ñ'est pas le premier du mois de janvier";
        //if(date("j")===1 && date("m")===1){
            #Les Message
            $titre="Notification Pour Les Validation Des Hausses Indice Syntec ";
            $lien ="https://madacontact.com/crm_actuel/";
            $message ="Bonjour, <br> C'est le premier du mois de janvier. L'outsourcia IT Bot vous informe qu'il était temps de passer au saisi des hausses indice syntec. <br> Merci de cliquer sur ce lien = ". $lien . " Et Bonne année à toi et tous tes proches. Que les 12 mois à venir soient synonyme de joie, de rires, de bonne santé <br> <br> Cordialement,";
            #Get Tous Les Juriste
            $AllUser=$repoUser->getAllUser();
            $arrayEmail= [];
            $reponseInsomnia="C'est pas le premiers du mois de Janvier";
            foreach ($AllUser as $user){
                foreach($user->getRoles() as $role){
                    if($role == "ROLE_JURISTE"){
                        $arrayEmail[]=$user->getEmail();
                        $sendMailTo->sendEmail($this->getParameter('from_email'), $user->getEmail(), $titre, $message, null);
                        $reponseInsomnia= "Email a ete Envoyer";
                    }
                }
            }
        //}
        return $this->json($reponseInsomnia);
    }
    /**
     * @Route("/import/bdc/sign/test", name="import_bdc_signTest", methods={"GET"})
     */
    public function importBdcsignEtContrat($bdc, UserRepository $user, SendMailTo $sendMailTo, Lead $lead): Response
    {
        $this->sendEmailJuristeContrat($bdc,$sendMailTo);
        return $this->json('testMety', 200, [], ['groups' => ['view', 'inject:cout']]);
    }

    public function sendNouveauClient(Customer $customer ,SendMailTo $sendMailTo,CustomerRepository $repoCust,BdcRepository $bdcRepository,$userDaf){
        #Teste Si le Client et existan ou pas
        $BdcsForOneCust = $bdcRepository->getBdcForOneCustomer2($customer->getId());
        $data= array();
      
        if($BdcsForOneCust!=[]){
            #Client Existant
            #N'envoye Pas De Email Parceque le client est existant
            #dd($BdcsForOneCust);
            //return $this->json("Client Existant Pas d'email", 200, []);
        } else{
            #Nouveau Client
            $customer=$repoCust->find($customer->getId());
            $contacts=$customer->getContacts();
            $raisonSocial=$customer->getRaisonSocial();
            $title="Contacts_".$raisonSocial."_Nouveau_Client";
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle($customer->getRaisonSocial());
            $tempHead=array();
            array_push($tempHead,"Raison Social");
            array_push($tempHead,"Prenom et Nom");
            array_push($tempHead,"Tel Contact");
            array_push($tempHead,"Email Contact");
            $data[]=$tempHead;
            $Char="A";
            foreach($contacts as $cont){
                $temp= [];
                array_push($temp,$raisonSocial);
                array_push($temp,$cont->getPrenom()." ". $cont->getNom());
                array_push($temp,$cont->getTel()."                               `");
                array_push($temp,$cont->getEmail());
                $data[]=$temp;
                for($i=0;$i<5;$i++,$Char++)
                $sheet->getColumnDimension($Char)->setAutoSize(true);
            }
        
            # Style
            $styleArray = [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                    'color' => ['argb' => 'E75012']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endColor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ];
                $ligneCsv = "A1:D1";
                $sheet->getStyle($ligneCsv)->applyFromArray($styleArray);

                $sheet->fromArray($data, null, 'A1', true);
                $writer = new Xlsx($spreadsheet);
                $filename = $this->getParameter('bdc_dir').$title;
                $writer->save($filename.".xlsx");
                $message="Bonjour,
                <br>
                Un nouveau client a été ajouté dans la base de données de notre CRM.
                <br>
                En pièce jointe SVP leurs contacts.
                <br>
                Merci de les ajouter dans SAGE SVP.
                <br>
                <br> 
                Cordialement,";

                //$sendMailTo->sendEmailNouveauClientExecel($this->getParameter('from_email'),"fetrajulio@gmail.com","L'escel du Nouveau Client","tena mety eee2",$title);
                foreach($userDaf as $user){ 
                    $sendMailTo->sendEmailNouveauClientExecel($this->getParameter('from_email'),$user,"Un nouveau client à rajouter dans Sage",$message,$title);
                }
            }
    }

    /**
     * @Route("/import/test/email", name="emailTest", methods={"GET"})
     */
    public function emailTest(SendMailTo $sendMailTo) : Response
    {
        $title="testfetra";
        $message="Madame la Directrice Financière, Monsieur le Directeur Financier.
        <br>
        Il y avait un nouveau client donc le site web Parcours Clients vous envoie automatiquement un excel qui contient les contacts du nouveau client
        <br>
        Veuillez trouver l'excel en pièce jointe";
        $sendMailTo->sendEmailNouveauClientExecel($this->getParameter('from_email'),"fetrajulio@gmail.com","Les Contact du Nouveau client ARBAT en excel",$message,$title);
        return $this->json("Nouveau Client Email Envoyer", 200, []);
    }

    /**
     * @Route("/import/bdc/sign/interne/", name="import_bdc_sign_com", methods={"GET"})
     */
    public function importBdcsignInterne(BdcRepository $bdcRepository, ContactRepository $contactRepository, EntityManagerInterface $em, Lead $lead): Response
    {
        $bdcs = $bdcRepository->findBdcToSignInterne();

        if (!empty($bdcs)) {
            foreach($bdcs As $bdc) {
                $response = $this->callSignDoc('cirrus/rest/v7/packages/' . $bdc->getSignaturePackageComId());

                // if (!empty($response) && ($response->state == 'COMPLETE' || $response->state == 'PREPARED')) {
                if (!empty($response) && $response->state == 'COMPLETE') {
                    list($commercialFile, $customerFile, $newStatut) = $this->bdcParams($bdc->getStatutLead());

                    # $downloaded = file_put_contents($this->getParameter('bdc_dir') . 'bdc_com_' . $bdc->getId() . '.zip', $this->callSignDoc('cirrus/rest/v7/packages/' . $bdc->getSignaturePackageComId() . '/documents', 'GET', [], false));
                    $downloaded = file_put_contents($this->getParameter('bdc_dir') . $commercialFile . $bdc->getIdMere() . '.zip', $this->callSignDoc('cirrus/rest/v7/packages/' . $bdc->getSignaturePackageComId() . '/documents', 'GET', [], false));

                    # Unzip file
                    if(!$this->unzip($this->getParameter('bdc_dir') . $commercialFile . $bdc->getIdMere() . '.zip', $this->getParameter('bdc_dir')))
                    {
                        die("Unzip tsy mandeha");
                    }

                    $customer = $bdc->getResumeLead()->getCustomer();

                    # MAJ champ status lead dans la table Bdc
                    $lead->updateStatusLeadBdc($bdc->getId(), $newStatut);

                    # Ajout ou MAJ statut client dans la table StatutLead
                    $lead->updateStatusLeadByCustomer($customer, $newStatut);

                    # Ajout d'une ligne dans la table WorkflowLead
                    $lead->addWorkflowLead($customer, $newStatut);

                    $em->persist($bdc);
                    $em->persist($customer);
                    $em->flush();

                    /*
                    * Envoie Signature au client
                    */

                    # Recuperation email de contact du client
                    $contacts = $customer->getContacts();

                    # Recuperation destinataire du BDC
                    $destinataires = $bdc->getDestinataireSignataire();

                    if(empty($destinataires))
                    {
                        $destinataires = array();

                        # Recuperation d'un contact destinataire. Temp
                        foreach($contacts As $contact)
                        {
                            $destinataires[] = $contact->getId();
                        }

                    }

                    # Prendre un contact destinataire
                    $signataire = [];

                    foreach($destinataires As $contactId)
                    {
                        $contact = $contactRepository->find($contactId);

                        $signataire["name"] = ($contact->getPrenom() . " " . $contact->getNom()) ?? "";
                        $signataire["email"] = $contact->getEmail();

                        break;
                    }


                    $files = [
                        ['type' => 'doc1', 'fileName' => $customerFile . $bdc->getIdMere() . '.pdf']
                    ];

                    $page = $this->nbr_pages($this->getParameter('bdc_dir') . $customerFile . $bdc->getIdMere() . '.pdf');
                    $this->sendToSign($files, $signataire, $bdc, $em, $page);

                } else if ($response->state == 'EXPIRED') {
                    # IGNORE PACKAGE FOR FUTUR PROCESS
                }
            }
            return $this->json('Bon de commande importe', 200, [], ['groups' => ['view']]);
        } else {
            return $this->json('Aucun bon de commande trouve !', 200, [], ['groups' => ['view']]);
        }
    }

    /**
     * @Route("/import/bdc/sign/", name="import_bdc_sign", methods={"GET"})
     * @param BdcRepository $bdcRepository
     * @param CategorieClientRepository $categClientRepository
     * @param EntityManagerInterface $em
     * @param SendMailTo $sendMailTo
     * @param Lead $lead
     * @param CustomerRepository $repoCust
     * @param UserRepository $repoUser
     * @param HausseIndiceSyntecClientRepository $repoHausseClient
     * @return Response
     */
    public function importBdcsign(BdcRepository $bdcRepository, CategorieClientRepository $categClientRepository, EntityManagerInterface $em, SendMailTo $sendMailTo, Lead $lead,CustomerRepository $repoCust,UserRepository $repoUser,HausseIndiceSyntecClientRepository $repoHausseClient): Response
    {
        #Get Tous Les Daf
        $AllUser=$repoUser->getAllUser();
        $UserDaf = array();
        foreach($AllUser as $user)
            foreach($user->getRoles() as $role)
                if($role == "ROLE_FINANCE")
                    $UserDaf[$user->getId()]= $user->getEmail();
		$bdcs = $bdcRepository->findBdcToSign();

		foreach($bdcs As $bdc) {
			$response = $this->callSignDoc('cirrus/rest/v7/packages/' . $bdc->getSignaturePackageId());

            if(is_object($response)){
                if (!empty($response) && ($response->state == 'COMPLETE')) {
                    $numBdc = $bdc->getNumBdc();
                    $customer = $bdc->getResumeLead()->getCustomer();
                    $client = $customer->getRaisonSocial();
                    $suiteProcess = $bdc->getSuiteProcess();
                    $isSeizureContract = $suiteProcess->getIsSeizureContract();
                    $isDevisPassToProdAfterSign = $suiteProcess->getIsDevisPassToProdAfterSign();

                    # Send Email To Juriste Demande de saisie Contrat
                    $isSeizureContract == 1 && $this->sendEmailJuristeContrat($bdc,$sendMailTo);

                    list($commercialFile, $customerFile, $newStatut) = $this->bdcParams($bdc->getStatutLead(), $isDevisPassToProdAfterSign);

                    $downloaded = file_put_contents($this->getParameter('bdc_dir') . $customerFile . $bdc->getIdMere() . '.zip', $this->callSignDoc('cirrus/rest/v7/packages/' . $bdc->getSignaturePackageId() . '/documents', 'GET', [], false));
                
                    # Unzip file
                    if(!$this->unzip($this->getParameter('bdc_dir') . $customerFile . $bdc->getIdMere() . '.zip', $this->getParameter('bdc_dir')))
                    {
                        die("Unzip tsy mandeha");
                    }

                    # L'injection vers suivirenta et IRM ne se font pas que si la suite du process est en prod après signature
                    if ($isDevisPassToProdAfterSign == 1){
                        # Injection client et opération dans IRM
                        $this->injectionIrmSuivirenta($bdc);

                        # Injection coût vers suivirenta
                        $this->injectCoutInSuivirenta->injectOrUpdateCoutToSuivirenta($bdc, "POST", $this->getParameter('param_injection_cout_in_suivirenta_url'));

                        # Injection tarif vers suivirenta
                        $this->injectTarifToSuiviRenta($bdc->getId(), $bdcRepository);
                    }

                    # Update  status Hausse en couleur vert 3
                    $repoHausseClient->UpdateStatutHausseClient($customer->getId());

                    # Send Email DAF Nouveau Client
                    $this->sendNouveauClient($customer ,$sendMailTo,$repoCust,$bdcRepository,$UserDaf);

                    # Mise à jour categorie du client à "client"
                    $categClient = $categClientRepository->find(2);
                    $customer->setCategorieClient($categClient);

                    # MAJ champ status lead dans la table Bdc
                    $lead->updateStatusLeadBdc($bdc->getId(),$newStatut);

                    # Ajout ou MAJ statut client dans la table StatutLead
                    $lead->updateStatusLeadByCustomer($customer, $newStatut);

                    # Ajout d'une ligne dans la table WorkflowLead
                    $lead->addWorkflowLead($customer, $newStatut);

                    # Donner une date au champ dateSignature du table Bdc
                    $bdc->setDateSignature(new \DateTime());

                    $em->persist($bdc);
                    $em->persist($customer);
                    $em->flush();

                    # Envoie d'email au commercial
                    $commercial = $customer->getUser();
                    $obj = "Devis signé par $client";
                    $msg = "Bonjour,<br>Le devis numéro $numBdc a été signé par $client.<br>Cordialement,";
                    $sendMailTo->sendEmail($this->getParameter('from_email'), $commercial->getEmail(), $obj, $msg, 1);
                } else if ($response->state == 'EXPIRED') {
                    # IGNORE PACKAGE FOR FUTUR PROCESS
                }
            }
		}

       return $this->json('Devis imported', 200, [], ['groups' => ['view', 'inject:cout']]);
    }

    /**
     * @Route("/get/lign/facturation/info/{id}", name="lign_fact_info", methods={"GET"})
     * @param BdcOperation $bdcOperation
     * @param BdcRepository $bdcRepository
     * @return Response
     */
    public function ligneFacturationInfo(BdcOperation $bdcOperation, BdcRepository $bdcRepository): Response
    {
        try {
                $bdc = $bdcRepository->find($bdcOperation->getBdc()->getId());

                if ($bdc){
                    list($pays, $ville, $cp, $adresse) = $this->getFacturationAdressValue($bdc);

                    $PrixUnitaireCheckDateApp =0.0;
                    $PrixUnitaireActeCheckDateApp = 0.0;
                    $PrixUnitaireHeureCheckDateApp = 0.0;
                    
                    if($bdcOperation->getApplicatifDate() == null || $bdcOperation->getApplicatifDate() <= date("Y-m-d H:i:s")){
                        $PrixCheckDateApp = $bdcOperation->getPrixUnit();
                        $PrixUnitaireActeCheckDateApp=$bdcOperation->getPrixUnitaireActe();
                        $PrixUnitaireHeureCheckDateApp =$bdcOperation->getPrixUnitaireHeure();
                    }
                    else{
                        $PrixCheckDateApp = $bdcOperation->getOldPrixUnit();
                        $PrixUnitaireActeCheckDateApp=$bdcOperation->getOldPrixUnitActe();
                        $PrixUnitaireHeureCheckDateApp =$bdcOperation->getOldPrixUnitHeure();
                    }
                    $datas = [
                        "bdc" => [
                            "id" => $bdc->getId(),
                            "numBdc" => $bdc->getNumBdc(),
                            "paysProduction" => $bdc->getPaysProduction()->getLibelle(),
                            "paysFacturation" => $bdc->getPaysFacturation()->getLibelle(),
                            "societeFacturation" => $bdc->getSocieteFacturation()->getLibelle()
                        ],
                        "client" => [
                            "raisonSocial" => $bdc->getResumeLead()->getCustomer()->getRaisonSocial(),
                            "adresseFacturation" => [
                                "pays" => $pays,
                                "ville" => $ville,
                                "cp" => $cp,
                                "adresse" => $adresse,
                            ]
                        ],
                        "ligneFacturation" => [
                            "bu" => $bdcOperation->getBu()->getLibelle(),
                            "operation" => $bdcOperation->getOperation()->getLibelle(),
                            "referenceArticle" => $bdcOperation->getOperation()->getReferenceArticle(),
                            "codeFamille" => $bdcOperation->getFamilleOperation()->getCodeFamille(),
                            "typeFacturation" => $bdcOperation->getTypeFacturation()->getLibelle(),
                            "prixUnit" => $PrixCheckDateApp,
                            "prixUnitaireHeure" => $PrixUnitaireHeureCheckDateApp,
                            "prixUnitaireActe" => $PrixUnitaireActeCheckDateApp,
                            "quantite" => $bdcOperation->getQuantite(),
                            "quantiteHeure" => $bdcOperation->getQuantiteHeure(),
                            "quantiteActe" => $bdcOperation->getQuantiteActe(),
                        ]
                    ];

                    return $this->json($datas, 200, [], ['groups' => ['get-by-bdc']]);
                }

                return $this->json("Devis not found", 200, [], []);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @param Bdc $bdc
     * @return array
     */
    private function getFacturationAdressValue(Bdc $bdc): array
    {
        $pays = null;
        $ville = null;
        $cp = null;
        $adresse = null;

        if ($bdc->getResumeLead()->getCustomer()->getIsAdressFactDiff() == 1){
            $pays = $bdc->getResumeLead()->getCustomer()->getAdresseFacturation() ? $bdc->getResumeLead()->getCustomer()->getAdresseFacturation()->getPays() : null;
            $ville = $bdc->getResumeLead()->getCustomer()->getAdresseFacturation() ? $bdc->getResumeLead()->getCustomer()->getAdresseFacturation()->getVille() : null;
            $cp = $bdc->getResumeLead()->getCustomer()->getAdresseFacturation() ? $bdc->getResumeLead()->getCustomer()->getAdresseFacturation()->getCp() : null;
            $adresse = $bdc->getResumeLead()->getCustomer()->getAdresseFacturation() ? $bdc->getResumeLead()->getCustomer()->getAdresseFacturation()->getAdresse() : null;
        } else {
            $pays = $bdc->getResumeLead()->getCustomer()->getPays() ?? null;
            $ville = $bdc->getResumeLead()->getCustomer()->getVille() ?? null;
            $cp = $bdc->getResumeLead()->getCustomer()->getCp() ?? null;
            $adresse = $bdc->getResumeLead()->getCustomer()->getAdresse() ?? null;
        }

        return [$pays, $ville, $cp, $adresse];
    }

    private function callSignDoc($entryPoint, $method = 'GET', $params = [], $return = 'json')
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt_array($curl, [
			CURLOPT_PORT => $this->getParameter('signdoc_port'),
			CURLOPT_URL => $this->getParameter('signdoc_url') . $entryPoint,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_POSTFIELDS => json_encode($params),
			CURLOPT_HTTPHEADER => [
				"API-key: " . $this->getParameter('signdoc_api_key'),
				"Content-Type: application/json"
			],
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		if ($err) {
			echo $err;
		}
		if ($return == 'json') {
			return json_decode($response);
		} else {
			return $response;
		}
	}

    /**
     * @param int $statulead
     * @param int $isDevisPassToProdAfterSign
     * @return array
     */
    private function bdcParams(int $statulead, int $isDevisPassToProdAfterSign = 1): array
    {
        $commercialFile = null;
        $customerFile = null;
        $newStatut = null;

        /**
         * Racuperation du nouveau status après import bdc signé
         * On verifie d'abord si le statut du devis est signé par le commercial et
         * que le suite du process du devis est en production après la signature du client.
         * Si c'est le cas, le statut du devis passe directement en production,
         * dans le cas contraire, le statut du devis change en "Signé par le client"
         */
        if (in_array($statulead, $this->getParameter("statut_lead_signed_by_commercial")) && $isDevisPassToProdAfterSign != 1){
            $statulead == $this->getParameter("statut_lead_bdc_signe_com") && $newStatut = $this->getParameter("statut_lead_bdc_signe_client");
            $statulead == $this->getParameter("statut_lead_bdc_avenant_signe_com") && $newStatut = $this->getParameter("statut_lead_bdc_avenant_on_prod");
        } else {
            $statuleadPos = array_search($statulead, $this->getParameter("statut_lead_before_import_bdc_sign"));
            $newStatut = $this->getParameter("statut_lead_after_import_bdc_sign")[$statuleadPos];
        }

        # Selection du nom de fichier à envoyer pour le signature éléctronique
        switch ($statulead)
        {
            case $this->getParameter('statut_lead_bdc_valider_dg'): # Validé par dg
                $commercialFile = "devis_com_";
                $customerFile = "devis_";
                break;
            case $this->getParameter('statut_lead_bdc_avenant_valider_dg'): # Avenant validé par dg
                $commercialFile = "devis_avenant_com_";
                $customerFile = "devis_avenant_";
                break;
            case $this->getParameter('statut_lead_bdc_signe_com'): # Signé par commercial
                $commercialFile = "devis_com_";
                $customerFile = "devis_";
                break;
            case $this->getParameter('statut_lead_bdc_avenant_signe_com'): # Avenant signé par commercial
                $commercialFile = "devis_avenant_com_";
                $customerFile = "devis_avenant_";
                break;
        }

        return array($commercialFile, $customerFile, $newStatut);
    }

    /**
     * @param int|null $paysprod
     * @return array
     * Retourne les urls necessaire pour l'injection dans IRM
     */
    private function getIRMUrlViaHisPaysProd(int $paysprod = null): array
    {
        $IrmClientUrl = null;
        $IrmOperationUrl = null;

        switch ($paysprod)
        {
            case 1: # Pays de production France
                $IrmClientUrl = $this->getParameter('irm_client_france_url_post');
                $IrmOperationUrl = $this->getParameter('irm_operation_france_url_post');
                break;
            case 2: # Pays de production Maroc
                $IrmClientUrl = $this->getParameter('irm_client_maroc_url_post');
                $IrmOperationUrl = $this->getParameter('irm_operation_maroc_url_post');
                break;
            case 3: # Pays de production Madagascar
                $IrmClientUrl = $this->getParameter('irm_client_mada_url_post');
                $IrmOperationUrl = $this->getParameter('irm_operation_mada_url_post');
                break;
            case 4: # Pays de production Niger
                $IrmClientUrl = $this->getParameter('irm_client_niger_url_post');
                $IrmOperationUrl = $this->getParameter('irm_operation_niger_url_post');
                break;
        }

        return array($IrmClientUrl, $IrmOperationUrl);
    }

    /**
     * Unzip
     * @param string $zip_file_path Eg - /tmp/my.zip
     * @param string $extract_dir_path
     * @return boolean
     */
	private function unzip(string $zip_file_path, string $extract_dir_path) {
		$zip = new \ZipArchive;
		$res = $zip->open($zip_file_path);
		if ($res === TRUE) {
			$zip->extractTo($extract_dir_path);
			$zip->close();
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function sendToSign($files, $signataire, $bdc, $em, $page){

		/* switch($page)
		{
			case 1:
				$top = 61.90625;
				$left = 330.99609375;
				$right = 480.23828125;
				$bottom = 10.16015625;
				$tabIndex = 0;
				break;
			case 2:
				$top = 81.90625;
				$left = 330.99609375;
				$right = 480.23828125;
				$bottom = 20.16015625;
				$tabIndex = 0;
				break;
            case 3:
			default:
				$top = 461.90625;
				$left = 330.99609375;
				$right = 480.23828125;
				$bottom = 400.16015625;
				$tabIndex = 0;
				break;
		} */

        $top = 485.90625;
        $left = 30.99609375;
        $right = 217.23828125;
        $bottom = 424.16015625;

        $tabIndex = 0;

        switch($page)
        {
            case 4:
                $pageToSign = 2;
                break;
            case 5:
                $pageToSign = 3;
                break;
            case 6:
                $pageToSign = 4;
                break;
            case 7:
                $pageToSign = 5;
                break;
            default:
                $pageToSign = 6;
                break;
        }

		$documentOptions = [
			'doc1' => [
                'label' => 'Document 1',
                'docType' => 'PDF',
                'widgets' => [
                    [
                        "pageNumber" => $pageToSign,
                        "top" => $top,
                        "left" => $left,
                        "right" => $right,
                        "bottom" => $bottom,
                        "tabIndex" => $tabIndex
                    ]
                ]
            ],
			'doc2' => [
				'label' => 'Document 2',
				'docType' => 'MS_WORD',
				'widgets' => [[
					"pageNumber" => 3,
					"top" => 113.484375,
					"left" => 330.24609375,
					"right" => 516.73828125,
					"bottom" => 59.98828125,
					"tabIndex" => 0
				]]
			],
			'doc3' => [
				'label' => 'Document3',
				'docType' => 'PDF',
				'widgets' => []
			]
		];

		$encodedFiles = [];
		foreach ($files as $k => $file) {
			$options = $documentOptions[$file['type']];
			if ($options['widgets']) {
				$encodedFiles[] =
					[
						"content" => base64_encode(file_get_contents($this->getParameter('bdc_dir') . $file['fileName'])),
						"signatureFields" => [
							[
								"id" => "signature-" . $k,
								"signerId" => "signer-1",
								"signingModeOptions" => ["C2S"],
								"required" => true,
								"readOnly" => false,
								"widgets" => $options['widgets']
							]
						],
						"id" => "document-" . $k,
						"fileName" => $file['fileName'],
						"format" => $options['docType'],
						"name" => $options['label']
					];
			} else {
				$encodedFiles[] =
					[
						"content" => base64_encode(file_get_contents($this->getParameter('bdc_dir') . $file['fileName'])),
						"id" => "document-" . $k,
						"fileName" => $file['fileName'],
						"format" => $options['docType'],
						"name" => $options['label']
					];
			}
		}

		$postParams = [
			"name" => "[DEVIS A SIGNE]",
			"description" => "[DEVIS A SIGNE]",
			"expirationDate" =>  date('Y-m-d', strtotime('+' . $this->getParameter('package_expiration') . ' month')) . 'T00:00:00Z',
			"documents" => $encodedFiles,
			"signingModeOptions" => ["C2S"],
			"type" => "PACKAGE",
			"state" => "DRAFT",
			"processingType" => "PAR",
			"mailSubject" => 'Devis à signer de la pars de Outsourcia',
			"mailMessage" => "Un devis vous a été créé.<br/> Merci de cliquer sur le bouton ci-dessous pour passer à la signaturer eléctroniaque SVP.<br/>Cordialement, <br/> Groupe Outsourcia",
			"inPersonEnabled" => true,
			"signers" => [
				[
					"id" => "signer-1",
					"role" => "SIGNER",
					"name" => $signataire['name'],
					"email" => $signataire['email'],
				]
			]
		];

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt_array($curl, [
			CURLOPT_PORT => $this->getParameter('signdoc_port'),
			CURLOPT_URL => $this->getParameter('signdoc_url') . "cirrus/rest/v7/package",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode($postParams),
			CURLOPT_HTTPHEADER => [
				"API-key: " . $this->getParameter('signdoc_api_key'),
				"Content-Type: application/json"
			],
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			var_dump("cURL Error #:" . $err);
			return false;
		} else {
			echo "reponse 1 : <br/>";
			var_dump($response);
			var_dump($signataire);

			$response = json_decode($response);
			if (isset($response->id)) {
				$curl2 = curl_init();
				curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt_array($curl2, [
					CURLOPT_PORT => $this->getParameter('signdoc_port'),
					CURLOPT_URL => $this->getParameter('signdoc_url') . "cirrus/rest/v7/packages/" . $response->id . "/scheduler",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_HTTPHEADER => [
						"API-key: " . $this->getParameter('signdoc_api_key'),
						"Content-Type: application/json"
					],
				]);

				$rep = curl_exec($curl2);
				$err = curl_error($curl2);

				curl_close($curl2);
				
				echo "reponse 2 : <br/>";
				var_dump($rep);

				# Stocké dans la BDD l'id du package
				$bdc->setSignaturePackageId($response->id);
				$em->persist($bdc);
				$em->flush();

				if ($err) {
					var_dump("cURL Error #:" . $err);
					return false;
				}
			} else {
				var_dump($response);
				return false;
			}
		}

		return $response->id;
	}

	private function nbr_pages($pdf){
	   if (false !== ($fichier = file_get_contents($pdf))){
		  $pages = preg_match_all("/\/Page\W/", $fichier, $matches);
		  return $pages;
	   }
	}

	private function injectionIrmSuivirenta($bdc)
    {
        try {
            if ($bdc) {
                # Variable locale necessaire.......
                $irmTab = array();
                $customer = $bdc->getResumeLead()->getCustomer();

                # Recuperation des urls client et operations suivant la pays de production du bdc
                list($IrmClientUrl, $IrmOperationUrl) = $this->getIRMUrlViaHisPaysProd($bdc->getPaysProduction()->getId());

                /*
                 * Validation service juridique (cas avenant Bdc signé par le client)
                 * Injection uniquement des nouvelles opération dans IRM et Suivi Renta
                */
                if ($bdc->getStatutLead() == $this->getParameter('statut_lead_bdc_avenant_signe_com')) {
                    foreach ($bdc->getBdcOperations() as $ligneFact) {
                        if ($ligneFact->getIrm() == 1 || $ligneFact->getIrm() == true) {
                            # Injection nouvelle opération dans IRM..................
                            if ($ligneFact->getAvenant() == 1){
                                $responseOperation = $this->httpClient->request('POST', $IrmOperationUrl, [
                                    'body' => [
                                        'libelle' => $ligneFact->getOperation()->getLibelle(),
                                        'operation_client_id' => $bdc->getClientIrmId(),
                                        'Site_id' => '',
                                        'Prime_base' => '0',
                                        'Type' => 'PR'
                                    ]
                                ]);

                                # Attribuer une valeur au champ IRM dans la table BdcOperation
                                if ($responseOperation->getStatusCode() == 200) {
                                    $bdcoperation = $this->bdcOperationRepository->find($ligneFact->getId());

                                    $operationId = str_replace('"', '', $responseOperation->getContent());
                                    $bdcoperation->setIrmOperation($operationId);
                                    $this->entityManager->persist($bdcoperation);
                                    $this->entityManager->flush();
                                }
                            }
                        }

                        # Injection nouvelle opération dans Suivi Renta.............
                        if ($ligneFact->getSiRenta() == 1 || $ligneFact->getSiRenta() == true) {
                            $this->httpClient->request('POST', $this->getParameter('suivi_renta_operation_url_post'), [
                                'body' => [
                                    'operation1' => $ligneFact->getOperation()->getLibelle(),
                                    'pays' => $bdc->getPaysProduction()->getLibelle(),
                                    'bu' => $ligneFact->getBu()->getLibelle(),
                                    'client' => $bdc->getResumeLead()->getCustomer()->getRaisonSocial(),
                                    'parcoursClientLigneFactId' => $ligneFact->getId()
                                ]
                            ]);
                        }
                    }
                } else {
                    # Injection client dans IRM
                    $responseClient = $this->httpClient->request('POST', $IrmClientUrl, [
                        'body' => [
							'parcours_client_id' => $bdc->getResumeLead()->getCustomer()->getNumClient(),
							'libelle' => $bdc->getResumeLead()->getCustomer()->getRaisonSocial()
						]
                    ]);

					# Mettre dans la table BDC l'id du client irm
					$clientIdIrm = $responseClient->getContent();
					$bdc->setClientIrmId(intval(str_replace('"', '', $clientIdIrm)));
                    $this->entityManager->persist($bdc);

                    foreach ($bdc->getBdcOperations() as $bdcoperation)
                    {
						# Inject Operation dans IRM............................
                        if ($bdcoperation->getIrm() == 1 || $bdcoperation->getIrm() == true) {
                            $operation = $bdcoperation->getOperation();
                            $responseOperation = $this->httpClient->request('POST', $IrmOperationUrl, [
                                'body' => [
                                    'libelle' => $operation->getLibelle(),
                                    'operation_client_id' => intval(str_replace('"', '', $clientIdIrm)),
                                    'Site_id' => '',
                                    'Prime_base' => '0',
                                    'Type' => 'PR'
                                ]
                            ]);

                            if (($responseClient->getStatusCode() == 200) && ($responseOperation->getStatusCode() == 200)) {
                                # Attribuer une valeur au champ IRM dans la table Customer
                                $clientId = intval(str_replace('"', '', $clientIdIrm));
                                $customer->setIrm($clientId);
                                $this->entityManager->persist($customer);

                                # Attribuer une valeur au champ IRM dans la table BdcOperation
                                $IdOperationInIrm = str_replace('"', '', $responseOperation->getContent()); // 89
                                $bdcoperation->setIrmOperation($IdOperationInIrm);
                                $this->entityManager->persist($bdcoperation);

                                $this->entityManager->flush();
                            }
                            array_push($irmTab, true);
                        }

                        if ($bdcoperation->getSiRenta() == 1 || $bdcoperation->getSiRenta() == true) {
                            # Injection Client dans suivi renta....................
                            $this->httpClient->request('POST', $this->getParameter('suivi_renta_client_url_post'), [
                                'body' => [
                                    'client1' => $bdc->getResumeLead()->getCustomer()->getRaisonSocial(),
                                    'bu' => $bdcoperation->getBu()->getLibelle(),
                                    'pays' => $bdc->getPaysProduction()->getLibelle(),
                                    'parcoursclientid' => $bdc->getResumeLead()->getCustomer()->getId(),
                                ]
                            ]);

                            # Injection Bu dans suivi renta.....................
                            $this->httpClient->request('POST', $this->getParameter('suivi_renta_bu_url_post'), [
                                'body' => [
                                    'bu1' => $bdcoperation->getBu()->getLibelle(),
                                    'Pays' => $bdc->getPaysProduction()->getLibelle()
                                ]
                            ]);

                            # Injection Operation dans suivi renta.......
                            $this->httpClient->request('POST', $this->getParameter('suivi_renta_operation_url_post'), [
                                'body' => [
                                    'operation1' => $bdcoperation->getOperation()->getLibelle(),
                                    'pays' => $bdc->getPaysProduction()->getLibelle(),
                                    'bu' => $bdcoperation->getBu()->getLibelle(),
                                    'client' => $bdc->getResumeLead()->getCustomer()->getRaisonSocial(),
                                    'parcoursClientLigneFactId' => $bdcoperation->getId()
                                ]
                            ]);
                        }
                    }

					# Injection Pays dans suivi renta....................
                    if ($bdc->getPaysProduction()->getLibelle() != null) {
                        $this->httpClient->request('POST', $this->getParameter('suivi_renta_pays_url_post'), [
                            'body' => [
                                'pays1' => $bdc->getPaysProduction()->getLibelle()
                            ]
                        ]);
                    }
                }

                $this->entityManager->flush();

                return array('status' => 200, 'message' => 'Client et Operation injecté dans IRM et Suivi Renta');
				
            } else {
                return array('status' => 200, 'message' => 'BDC VIDE');
            }
        } catch (Exception $e) {
            return array(
                "status" => 500,
                "message" => $e->getMessage());
        }
    }

    /**
     * @param $idBdc
     * @param $bdcRepository
     */
    private function injectTarifToSuiviRenta($idBdc, $bdcRepository): void
    {
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
                    if (!in_array($ligneFacturation->getOperation()->getId(), $this->getParameter('param_id_operation_bonus_malus_frais_telecoms_2'))
                        && in_array($ligneFacturation->getTypeFacturation()->getId(), $this->getParameter('param_id_type_facte_acte_heure'))){

                        # Logique tarif formation
                        $tarifFormation = null;
                        if ($ligneFacturation->getOperation()->getId() == $this->getParameter('param_id_operation_formation_continue')) {
                            $tarifFormation = floatval($ligneFacturation->getPrixUnit());
                        }

                        $tarifActeDimanche = null;
                        $tarifHeureDimanche = null;
                        if ($ligneFacturation->getIsHnoDimanche() == 1){
                            # Logique tarif acte dimanche
                            if ($ligneFacturation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_acte')){
                                $tarifActeDimanche = floatval($ligneFacturation->getPrixUnit());
                            }

                            # Logique tarif heure dimanche
                            if ($ligneFacturation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_heure')){
                                $tarifHeureDimanche = floatval($ligneFacturation->getPrixUnit());
                            }
                        }

                        $tarifActeHorsDimanche = null;
                        $tarifHeureHorsDimanche = null;
                        if($ligneFacturation->getIsHnoHorsDimanche() == 1) {
                            # Logique tarif acte hors dimanche
                            if ($ligneFacturation->getIsHnoHorsDimanche() == 1 && $ligneFacturation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_acte')){
                                $tarifActeHorsDimanche = floatval($ligneFacturation->getPrixUnit());
                            }

                            # Logique tarif heure hors dimanche
                            if ($ligneFacturation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_heure')){
                                $tarifHeureHorsDimanche = floatval($ligneFacturation->getPrixUnit());
                            }
                        }

                        if ($ligneFacturation->getAvenant() == 1 || !empty($ligneFacturation->getApplicatifDate())) {
                            $buLibelle = null;
                            if($ligneFacturation->getBu()){
                                $buLibelle = $ligneFacturation->getBu()->getLibelle();
                            }
                            # Envoi des données vers api suivi-renta via de requette httpClient (for avenant)
                            $this->httpClient->request('POST', $this->getParameter('param_inject_tarif_in_suivirenta_url'), [
                                'body' => [
                                    'pays' => $bdc->getPaysProduction()->getLibelle(),
                                    //'bu' => $ligneFacturation->getBu()->getLibelle(),
                                    'bu' => $buLibelle,
                                    'client' => $bdc->getResumeLead()->getCustomer()->getRaisonSocial(),
                                    'operation' => $ligneFacturation->getOperation()->getLibelle(),
                                    'date_debut' => !empty($ligneFacturation->getApplicatifDate()) ? date_format($ligneFacturation->getApplicatifDate(), "Y-m-d") : $dateDebut,
                                    'date_fin' => $dateFin,
                                    'tarifheure' => ($ligneFacturation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_heure')) ? floatval($ligneFacturation->getPrixUnit()) : null,
                                    'tarifacte' => ($ligneFacturation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_acte')) ? floatval($ligneFacturation->getPrixUnit()) : null,
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
                                $this->httpClient->request('POST', $this->getParameter('param_inject_tarif_in_suivirenta_url'), [
                                    'body' => [
                                        'pays' => $bdc->getPaysProduction()->getLibelle(),
                                        'bu' => $ligneFacturation->getBu()->getLibelle(),
                                        'client' => $bdc->getResumeLead()->getCustomer()->getRaisonSocial(),
                                        'operation' => $ligneFacturation->getOperation()->getLibelle(),
                                        'date_debut' => $dateDebut,
                                        'date_fin' => $dateFin,
                                        'tarifheure' => ($ligneFacturation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_heure')) ? floatval($ligneFacturation->getPrixUnit()) : "",
                                        'tarifacte' => ($ligneFacturation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_acte')) ? floatval($ligneFacturation->getPrixUnit()) : "",
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
        } catch (TransportExceptionInterface $e) {
        }
    }

    private function sendEmailJuristeContrat(BDC $bdc, SendMailTo $sendMailTo){
        $UserAll = $this->getDoctrine()->getRepository(User::class)->findAll();

        $client = $bdc->getResumeLead()->getCustomer()->getRaisonSocial();
        $numBdc = $bdc->getNumBdc();

        $titre = "Demande création contrat pour le client $client";
        $msg="Bonjour, <br> <br>Le Devis numéro $numBdc a été signé par le client $client.
        Merci de créer SVP son contrat. Pour créer le contrat, aller dans CRM , puis cliquer sur le menu CONTACT, 
        et enfin cliquer sur le bouton “Saisi information contrat <br> <br> Cordialement,";

        $statutLeadToSendNotif = [
            $this->getParameter('statut_lead_bdc_signe_com'),
            $this->getParameter('statut_lead_bdc_on_prod')
        ];

        # Izay Bdc tsy Avenant no Andefasana Email
        if(in_array($bdc->getStatutLead(), $statutLeadToSendNotif)){
            foreach($UserAll as $user){
                # Raha Juriste andefasana Email
                if(in_array("ROLE_JURISTE",$user->getRoles())){
                    $sendMailTo->sendEmail($this->getParameter("from_email"), $user->getEmail(), $titre, $msg, 1);
                    // $sendMailTo->sendEmailViaTwigTemplate("telmestour@outsourcia-group.com", "fetrajulio@gmail.com", $titre, 'emailContent/forValidationSuperior.html.twig', $user, 4);
                }
            }
        }
    }
}
