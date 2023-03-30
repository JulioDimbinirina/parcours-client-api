<?php

namespace App\Controller;

use App\Entity\Bdc;
use App\Entity\Contact;
use App\Entity\Contrat;
use App\Entity\Customer;
use App\Entity\Historique;
use App\Entity\HistoriqueContrat;
use App\Repository\HistoriqueRepository;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ContactRepository;
use App\Repository\ContratRepository;
use App\Repository\CustomerRepository;
use App\Repository\HistoriqueContratRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/api")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/list-contacts", name="contact", methods={"GET"})
     */
    public function findAllContacts(ContactRepository $repo): Response
    {
        try {
            return $this->json($repo->findAll(), 200, [], ['groups' => ['contact', 'has']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/get/contact/for/interlocuteur", name="contact", methods={"GET"})
     */
    public function getContactToDisplayInterlocuteur(ContactRepository $repo): Response
    {
        try {
            return $this->json($repo->findAll(), 200, [], ['groups' => ['interlocuteur']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/save-contact", name="ajout_contact", methods={"POST"})
     */
    public function saveContact(Request $request, EntityManagerInterface $em, 
    SerializerInterface $serializer): Response 
    {
        try {
            $jsonRecu = $request->getContent();
            $contact = $serializer->deserialize($jsonRecu, Contact::class, 'json');

            $data = json_decode($request->getContent(), true);

            $customer = $this->getDoctrine()->getRepository(Customer::class)->findOneById($data['customer']);

            $contact->setCustomer($customer);
            $em->persist($contact);
            $em->flush();

            return $this->json($contact, 201, [], ['groups' => ['contact']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/activedesactive-contact/{id}", name="disabled_contact", methods={"GET"})
     */
    public function activeDesactiveClick(int $id, EntityManagerInterface $em, ContactRepository $repo, HistoriqueRepository $histo): Response
    {
        try {
            $mappingContact = $repo->find($id);

            if ($mappingContact) {
                $nb = !$mappingContact->getStatus();

                $mappingContact->setStatus($nb);

                $em->persist($mappingContact);

                $history = new Historique();
                $history->setContact($mappingContact);
                $history->setDate(new \DateTime('now'));
                $history->setStatus($nb);

                $em->persist($history);
                $em->flush();

                return $this->json($nb, 200, [], []);
            }

        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/delete-contact/{id}", name="supp_contact", methods={"DELETE"})
     */
    public function delete(int $id, ContactRepository $repo, EntityManagerInterface $em): Response {
        try {
            $dataSupp = $repo->find($id);
            $em->remove($dataSupp);
            $em->flush();

            return $this->json(["status"=> 200, "message" => "resource deleted successfully !"], 200);
            
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }
    /**
     * @Route("/saisie/info", name="saisieInformationContart", methods={"POST"})
     */
    public function saisieInformationContart(Request $request,CustomerRepository $repoCust,EntityManagerInterface $em,HistoriqueContratRepository $repoHisto,ContratRepository $repoContrat){
       
       
        #Enlever les Virgule
        $TextAncienSansVirgule = [];
        $index=0;
        $DataRequets = json_decode($request->getContent(), true);
        $arrayDataToPdf = $DataRequets["arrayDataToPdf"];
        $TexteAncien=$this->getAncienTextContrat($repoCust,$DataRequets["row"]["id"]);
        foreach($TexteAncien as $Text){
            $TextAncienSansVirgule[$index]=substr($Text,2);
            $index++;
        }
        $TextModifier=$DataRequets["modifText"];
        $customerId = $DataRequets["row"]["id"];
        $customer = $repoCust->find($customerId);
        $arrayResumeLead=$customer->getResumeLeads();
        $ArrayTousBdcDuClient = [];
        # Maka Bdc Rehetra any client
        foreach($arrayResumeLead as $resumeLead){
            $bdcParResumeLead=$resumeLead->getBdcs();
            foreach($bdcParResumeLead as $bdc){
                if ($bdc->getStatutLead() >= 11 )
                $ArrayTousBdcDuClient[$resumeLead->getId()]=$bdc;
            }
        }
        $TousLigneFacturationClient= [];
        $arrayBdcOMisePlace = [];
        $arrayFaisPilotage = [];
        $arrayBdcOPrestation = [];
        # Maka ny Ligne de facturation Rehetra
        foreach($ArrayTousBdcDuClient as $bdc){
            foreach($bdc->getBdcOperations() as $bdcOp) {
                if($bdcOp->getOperation()->getId() == 1){
                    $arrayBdcOMisePlace[$bdcOp->getOperation()->getFamilleOperation()->getLibelle()]=$bdcOp;
                }else if($bdcOp->getOperation()->getId() == 14){
                    $arrayFaisPilotage[$bdcOp->getOperation()->getLibelle()]=$bdcOp;
                }
                else{
                    $arrayBdcOPrestation[$bdcOp->getOperation()->getLibelle()]=$bdcOp;
                }
                $TousLigneFacturationClient[$bdcOp->getOperation()->getLibelle()." ".$bdc->getId()]= $bdcOp;
            }
        }
        #Set Contrat
        $contrat = $repoContrat->findOneBy(['idCustomer' => $customerId]);
        if($contrat){
           

            #MAJ contrat
            $contrat->setDateContrat(new \DateTime('now'));
            $contrat->setStatusContrat($this->getNewStatusContratPlus($this->getParameter('statut_contrat_creer'),1));

             
        }else{
            $contrat = new Contrat();
            $contrat->setDateContrat(new \DateTime('now'));
            $contrat->setIdCustomer($customerId);
            $contrat->setStatusContrat($this->getParameter('statut_contrat_creer'));
        }
       
        $em->persist($contrat);
        $em->flush();

        #SetHistorique
        $contratCree = $repoContrat->findOneBy(['idCustomer' => $customerId]);
        
        $historique = new HistoriqueContrat();
        $historique->setDate(new \DateTime('now'));
        $historique->setIdContrat($contratCree->getId());
        $historique->setStatusContrat($contratCree->getStatusContrat());

        $em->persist($historique);
        $em->flush();

        for ($i=0 ; $i<count($TextModifier) ; $i++){
            if($TextModifier[$i]){
                $TexteAncien[$i]=$TextModifier[$i];
            }
        }
        $contact = $customer->getContacts()[0];
        $prestataire = $customer->getUser();
        $html = $this->renderView('premiersContrat.html.twig', [
            'Texte' => $TexteAncien,
            'byFront' => $arrayDataToPdf,
            'arrayBdcOMisePlace' =>  $arrayBdcOMisePlace,
            'arrayFaisPilotage' => $arrayFaisPilotage,
            'arrayBdcOPrestation' => $arrayBdcOPrestation,
            'contact' => $contact,
            'prestataire' =>$prestataire
        ]);
        # Configure Dompdf according to your needs
        $pdfOptions = new Options();

        # $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->set('defaultFont', 'Arial');

        # Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        // Render the HTML as PDF
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents($this->getParameter('bdc_dir') ."ContratCustomerId". $customerId. '.pdf', $output);
        // return $this->json("okoko", 201, [], ['groups' => ['contact']]);
        return new Response($html);
    }

      /**
     * @Route("/contact/get/touscustomer/contrat/cremod", name="AllCutomerCree", methods={"GET"})
     */
    public function AllCutomerCree(ContratRepository $repoContrat){
        $allContratCreerMod = $repoContrat->getAllCustomerCreerOuModifier();
        $arrayContratIdCustCreeEtModifier = [];
        foreach($allContratCreerMod as $contrat){
            $arrayContratIdCustCreeEtModifier[]=$contrat->getIdCustomer();
        }
        return $this->json($arrayContratIdCustCreeEtModifier, 200, [], []);
    }
     /**
     * @Route("/contact/send/contrat/customer/{customerId}", name="sendEmailContrat", methods={"GET"})
     */
    public function sendEmailContrat($customerId,BonDeCommandeController $bonDeCommandeController,CustomerRepository $repoCust,EntityManagerInterface $em,ContratRepository $repoContrat){
        copy($this->getParameter('bdc_dir') ."ContratCustomerId". $customerId. '.pdf', $this->getParameter('bdc_dir')  ."ContratPourCustomerId". $customerId. '.pdf');

                # Construction du fichier à envoyer
        $files = [
        ['type' => 'doc1', 'fileName' =>"ContratCustomerId". $customerId. '.pdf']
        ];
        $page = $bonDeCommandeController->nbr_pages($this->getParameter('bdc_dir') ."ContratCustomerId". $customerId. '.pdf');
        $page = $page * -1;
        $cutomer=$repoCust->find($customerId);

        $signataire = [];
        foreach($cutomer->getContacts() as $contact){
            if ($contact->getContactHasProfilContacts()){
                foreach($contact->getContactHasProfilContacts() as $profil){
                    if($profil->getProfilContact()->getLibelle() == "Signataire")
                    $signataire["name"] = $contact->getNom();
                    $signataire["email"] = $contact->getEmail();
                    break;
                }
            }
        }

        #Maka contrat any customer
        $contrat = $repoContrat->findOneBy(['idCustomer' => $customerId]);
        $contrat->setStatusContrat($this->getParameter('statut_contrat_envoyer'));
        $em->persist($contrat);
        $em->flush();

         #SetHistorique
        
        $historique = new HistoriqueContrat();
        $historique->setDate(new \DateTime('now'));
        $historique->setIdContrat($contrat->getId());
        $historique->setStatusContrat($contrat->getStatusContrat());
 
        $em->persist($historique);
        $em->flush();
        $this->sendToSignContrat($files, $signataire, $contrat, $em, $page,1);
        return $this->json("OKOK", 200, [], []);
    }

      /**
     * @Route("/get/ancienText/{idCust}", name="getAncienText", methods={"GET"})
     */
    public function getAncienText($idCust,CustomerRepository $repoCust){
        if ($idCust == 0 ){
            return $this->json("0 io oo", 200, [], []);
        }
        return $this->json($this->getAncienTextContrat($repoCust,$idCust), 200, [], []);
    }

    private function getNewStatusContratPlus($status,$nombre){
        $index = null;

        $arrayStatusContrat = $this->getParameter('statut_contrat_juriste');

        foreach($arrayStatusContrat as $key => $value){
            if($value == $status){
                $index = $key;
                break;
            }
        }
        return $arrayStatusContrat[$index + $nombre];
    }
    public function sendToSignContrat($files, $signataire, $contrat, $em, $page){
        $tabIndex = 0;
        if($page < 0){
            # Pour signature contrat
            $pageToSign = $page*-1;
            $top = 743.484375;
            $left = 245.24609375;
            $right = 481.73828125;
            $bottom = 679.98828125;
        } else{
            # Pour signature bdc
            $top = 485.90625;
            $left = 30.99609375;
            $right = 217.23828125;
            $bottom = 424.16015625;

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

        $oblNotif = "Contrat à signer de la part de Outsourcia";

        $postParams = [
            "name" => "[BON DE COMMANDE A SIGNE]",
            "description" => "[BON DE COMMANDE A SIGNE]",
            "expirationDate" =>  date('Y-m-d', strtotime('+' . $this->getParameter('package_expiration') . ' month')) . 'T00:00:00Z',
            "documents" => $encodedFiles,
            "signingModeOptions" => ["C2S"],
            "type" => "PACKAGE",
            "state" => "DRAFT",
            "processingType" => "PAR",
            "mailSubject" => $oblNotif,
            "mailMessage" => "Merci de cliquer sur le bouton ci-dessous pour passer à la signature électronique du Bon De Commande SVP.<br/>Cordialement, ",
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

                $contrat->setSignaturePackContratCustomer($response->id);


                $em->persist($contrat);
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
    private function getAncienTextContrat(CustomerRepository $repoCustomer,$idCustomer){
        if ($idCustomer == 0){
            $customer = $repoCustomer->find(10);
        }
        else{
            $customer = $repoCustomer->find($idCustomer);
        }
        $bdcProd = new Bdc();
        foreach($customer->getResumeLeads() as $resume){
            foreach($resume->getBdcs() as $bdc){
                if ($bdc->getStatutLead() >= 11){
                    $bdcProd = $bdc;
                    break;
                }
            }
        }
        $TexteAncien =  [
            ",CONTRAT DE PRESTATION DE SERVICES,",
            ",Entre les soussignées :,",
            ",La société [1;".str_replace(" ","_",$bdcProd->getSocieteFacturation()->getLibelle())."] , au capital social de [2;".str_replace(" ","_",$bdcProd->getSocieteFacturation()->getCapital())."] , immatriculée au Registre du Commerce et des Sociétés de [3;". $bdcProd->getSocieteFacturation()->getIdentifiantFiscal()."] , sous le numéro [4;".$bdcProd->getSocieteFacturation()->getIdentifiantFiscal()."] , dont le siège social est situé au [5;".str_replace(" ","_",$bdcProd->getSocieteFacturation()->getAdresse()) ."] , représentée par [6;".str_replace(" ","_",$customer->getUser()->getCurrentUsername())."] , en sa qualité de [7;commercial] , dûment habilité à l’effet des présentes,,",
            ",Ci-après dénommée le 'Prestataire', d une part,,",
            ",La société [8;".str_replace(" ","_",$customer->getRaisonSocial())."] ,au capital social de [10;159900] , immatriculée au Registre du Commerce et des Sociétés de [11;] , sous le numéro [12;] , dont le siège social est situé au [13;".str_replace(" ","_",$customer->getPays())."] , représentée par [14;".str_replace(" ","_",$customer->getContacts()[0]->getNom() ." ". $customer->getContacts()[0]->getPrenom())."] ,en sa qualité de [15;".$customer->getCategorieClient()->getLibelle()."] , dûment habilité à l’effet des présentes,",
            ",Le Prestataire et le Client étant individuellement désignés par une/la « Partie » et conjointement par les « Parties ». ,",
            ",Le Prestataire est un professionnel qui est spécialisé́ dans l’externalisation de processus métiers, dans le traitement spécifique de l’information et des donnéeset dans la gestion des relations clients.,",
            ",Le Client est spécialisé dans le secteur du [16,domaine_d’activité_du_Client_et_besoins_en_lien_avec_l’activité_du_Prestataire] ,",
            ",Les Parties se sont donc rapprochées afin de définir dans le présent contrat (ci-après « le Contrat ») les termes de leur accord.,",
            ",CECI ETANT EXPOSE, IL A ETE CONVENU CE QUI SUIT :,",
            ", :",
            ",Lorsqu’ils seront utilisés dans le corps des présentes avec une majuscule, les termes ci-dessous, qu’ils soient employés au singulier ou au pluriel, auront la signification suivante :,",
            ", Personnel employé par le Prestataire affecté à l’exécution des Prestations.,",
            ", document signé par le Client détaillant les prestations et traitements à effectuer par le Prestataire ainsi que les conditions tarifaires et qui en engage les Parties une fois signé.,",
            ", le présent contrat de prestations de services signé entre le Prestataire et le Client, définissant les conditions générales et particulières applicables aux Prestations.,",
            ", l’heure de production dédiée à l’opération comprenant les traitements, les debriefs individuels et collectifs et les pauses légales pendant les heures de travail. Cela ne comprend donc ni les absences, ni les retards, ni les jours fériés, ni les congés payés.,",
            ", désigne les appels, les emails, la saisie de donnée ou toute mission confiée au Prestataire par le Client qui peut être quantifiable à partir d’outil fiable et sécurisé pour les parties.,",
            ", critères déterminés contractuellement permettant aux Parties d’analyser et suivre l’exécution des Prestations.,",
            ", l’une de l’autre des parties au Contrat.,",
            ", l’ensemble des services et traitements effectués par le Prestataire pour le compte du Client, tels que décrits dans les présentes et dans le Bon de Commande.,",
            ",Article 1 : Objet du Contrat ,",
            ",Le Contrat a pour objet de définir les conditions et modalités suivant lesquelles le Prestataire effectue pour le compte du Client les Prestations détaillés aux présentes. ,",
            ",Article 2 : Contenu des Prestations,",
            ",* 2.1 Description générale des Prestations,",
            ",Le Client confie au Prestataire les prestations suivantes, [17;naturedesprestations,languesdeprestations]",
            ",Les Prestations sont réalisées dans les locaux du Prestataire à [18;] , du [19;] au [20;] , de [21;]  heures à [22;]  heures en heure [23;] . ,",
            ",Ces horaires pourront évoluer en fonction des activités du Client. Le Prestataire communiquera le nombre de ressource nécessaires pour couvrir les plages horaires.,",
            ",Le Client pourra demander au Prestataire une ouverture du service durant les heures non ouvrées, les jours fériés français et dimanche au minimum 15 jours avant lesdits jours.,",
            ",Toute demande de modification des Prestations de manière permanente doit faire l’objet d’un avenant dument signé par les Parties pour être effective.,",
            ",*2.2Volumes prévisionnels,",
            ",Les Parties conviennent de mettre en place, au démarrage de la Prestation, une équipe dédiée dimensionnée comme suit : [24;] ",
            ",Le CLIENT fournira au PRESTATAIRE au plus tard le 14ème jour ouvré de chaque mois (M) les prévisions des mois M+1, M+2 et M+3 ",
            ",- Les volumes de M+1 (du 1er jour à la fin du mois) sont fermes.,",
            ",- Les volumes de M+2 et M+3 sont communiqués à titre d’information.",
            ",Ces prévisions sont déclinées en nombre d’actes à traiter par canal.,",
            ",Le Prestataire se chargera de calculer le dimensionnement en nombre d’Agents nécessaire pour produire les prévisions en respectant les objectifs ci-dessous. Le dimensionnement sera systématiquement validé par le Client au démarrage de la production. Dans le cas où le Client refuserait les recommandations de dimensionnement du Prestataire, les engagements contractuels sur la qualité de service seront suspendus.,",
            ",Si le volume d’activité réellement confié au PRESTATAIRE devait excéder 120% des prévisionsmensuelles communiquées, les engagements contractuels de QS seront suspendus.,\t [25;Application_dans_le_cas_d’une_facturation_à_l’acte] « Dans le cas où les volumes réellement présentés sont inférieurs de 20% aux prévisions mensuelles, le Client prendra en charge financièrement 80% des volumes prévus. »,",
            ",La variation à la baisse de volume d’un mois sur l’autre est plafonnée dans les limites suivantes : ,",
            "M+1 ne pourra varier de moins 20% par rapport à M, ,",
            "M+2 ne pourra varier de moins 20% par rapport à M+1, ,",
            "Et ainsi de suite de manière glissante.,",
            ",La variation à la hausse n’est pas plafonnée, toutefois, elle dépendra de la capacité du Prestataire à sélectionner des ressources répondants aux critères d’exigence du Client. ,",
            ",*2.3Qualité de Service,",
            ",La qualité de service « QS » correspond au pourcentage d’actes traités (Fiches Produits rédigés) par rapport au nombre d’actes reçus.,",
            ",A ce titre, les Parties définissent les objectifs respectifs suivants :,-",
            "- [26;xxx] ,",
            ",Ces objectifs peuvent évoluer et être mis à jour de convention expresse par les Parties par la signature d’un avenant.,",
            ",Lors des comités de pilotage mensuels, les Parties ont la possibilité d examiner le niveau de performance atteint par le Prestataire sur une période donnée, de modifier les Indicateurs de qualité, de prendre en compte les évolutions des services, ou d adapter le Contrat aux nouveaux besoins exprimés, lesquels seront matérialisés par la signature d’un avenant à cet effet avant son entrée en vigueur.,",
            
            ",*2.4 Baromètre Qualité,",
            ",Le Prestataire s’engage à effectuer les Prestations dans le respect des délais et qualité requise définis par le Client.,",
            ",Le Prestataire fera réaliser chaque semaine par un salarié des contrôles des actes traités sur l’ensemble des Agents en production sur la base de la grille proposée par le Prestataire et validée par le Client. ,",
            ",","Le Client pourra réaliser également ces contrôles afin de comparer ses résultats avec ceux obtenus par le Prestataire, dans le cadre d’une démarche synchronisée. Une note sur 100 sera attribuée lors de chaque contrôle. ,",
            ",","Les Parties conviennent d’un objectif fixé à [27;1]  sur 100 en moyenne mensuelle. Cette note correspondra à la moyenne pondérée des évaluations effectuées par le Client et de celles effectuées par le Prestataire.,",
            ",Le Client organisera des comités hebdomadaires et mensuels de calibrage avec le Prestataire afin de définir les plans d’actions.,",
            ",*2.5Outils,",
            ",Les Prestations seront réalisées sur les outilssuivants :,",
            ",Prestations","Outils,",
            ",*2.6Reporting,",
            ",Un contrôle de l’activité sera réalisé à partir des Indicateurs définis par les Parties.,",
            ",Le Prestataire devra remettre au Client un reporting exhaustif de l’exécution de la Prestation qui lui est confiée et ce, afin que le Client soit en mesure d’opérer un contrôle quantitatif et qualitatif de son activité. Ledit reporting se fera hebdomadairement/mensuellement et servira de base de facturation en faveur du Prestataire.,",
            ",En outre, les Parties conviennent de se réunir au moins une fois par mois lors d’un Comité de Pilotage afin d’évaluer le bon déroulement des Prestations et de discuter des éventuelles modifications concernant le Contrat. Toute modification à apporter au Contrat devra faire l’objet d’un avenant dument signé entre les Parties avant son entrée en vigueur.,",
            ",Le Prestataire signale immédiatement par tous moyens adressé au Client toutes les anomalies ou problèmes techniques qu’il peut identifier afin de permettre au Client d’apporter une correction dans les meilleurs délais.,",
            ",Article 3 : Modalités d exécution et de suivi des Prestations,",
            ",3.1 Le Prestataire s’oblige à exécuter les Prestations qui lui sont confiées, dans le cadre d’une obligation de résultat portant sur les Indicateurs de performance et de qualité prévus au Contrat.,",
            ",Le Prestataire, qui déclare prendre toute la mesure des besoins du Client, s engage à prendre toutes les dispositions utiles pour assurer la mise en place, en temps voulu, des moyens et actions nécessaires à l exécution des Prestations. Il est soumis à une obligation générale d information, de conseil et de mise en garde.,",
            ",A cet effet, le Prestataire apporte son savoir-faire, ses méthodes et ses connaissances, concrétisés par l intervention de son personnel, son encadrement et ses matériels. Il précise par écrit au Client avant le démarrage de la Prestation les noms des responsables de l’équipe projet.,",
            ",3.2 Le Prestataire met en œuvre l’organisation nécessaire à la qualité de ses Prestations et à sa pérennité en y affectant les moyens humains et techniques nécessaires :,- encadrement de la plate-forme de production,- équipe de formation assurant les formations d’intégration et continues,- support technique informatique,- outils de communication nécessaires à la relation quotidienne entre le Client et le Prestataire,",
            ",En cas de modification des Prestations, le Client s’engage à avertir le Prestataire dans un délai raisonnable préalablement à la mise en œuvre de ladite modification et fournira le cas échéant au Prestataire les moyens nécessaires à la réalisation de ses Prestations. ,Le Prestataire s’engage quant à lui au titre de sa réactivité à réduire le délai de mise en œuvre de cette modification au strict minimum.,",
            ",Le Client considère que la qualité des recrutements est essentielle à la qualité de service attendu par ses clients. Le Prestataire s’engage à mettre à jour, un profil de poste adéquat et s’assurera que les compétences des Agents retenus seront en lien avec les tâches qui leur seront demandées. ,",
            ",En cas d indisponibilité des personnes affectées à l exécution de la Prestation, le Prestataire s engage à les remplacer sans délai par des personnes de compétence équivalente. De même, si les personnes désignées se révèlent inexpérimentées ou en mauvaise adéquation avec les Prestations, le Prestataire s engage à les remplacer sans délai par des personnes dotées de la qualification et du niveau d expérience adaptés à l exécution des Prestations. Plus généralement, la modification des moyens et actions mis en œuvre pour l exécution des Prestations pourra être demandée par le Client, en cas d inadaptation de ceux-ci à la nature des Prestations. ,",
            ",Au titre de la très forte réactivité attendue du Prestataire, ce dernier fera ses meilleurs efforts pour respecter ses engagements de résultat dans l’hypothèse où le nombre de contacts constatés serait supérieur au volume prévisionnel. ,",
            ",Les personnes affectées à l exécution des Prestations restent sous l entière subordination du Prestataire qui assure leur encadrement et leur surveillance. En conséquence, les personnes affectées à l exécution des Prestations restent sous l entière responsabilité du Prestataire et ne peuvent en aucun cas être considérées comme des salariés du Client.,",
            ",Les Parties conviennent que le Prestataire et son personnel ne sont, en aucun cas, assimilés au personnel du Client et intégrés à sa collectivité de travail. En conséquence, le Prestataire et son personnel ne peuvent prétendre aux avantages salariaux et aux ressources du Client mises à la disposition de son personnel.,",
            ",Le Prestataire s’engage à n’effectuer les Prestations pour le compte du Client, qu’avec des salariés employés régulièrement au regard de la législation du pays ou est traité la Prestation.,",
            ",3.3 Le Client apporte au Prestataire le soutien et la documentation dont celui-ci peut avoir besoin pour l exécution des Prestations. En particulier, le Client s’engage à mettre à la disposition du Prestataire les procédures de traitement nécessaires à l’exécution des Prestations, et à les mettre à jour régulièrement.,",
            ",Le Prestataire s’engage à apporter à la réalisation des Prestations tout le soin et la diligence d’un professionnel de haut niveau et à mettre à la disposition du Client des prestations de la meilleure qualité. La responsabilité du Prestataire ne pourra toutefois être engagée que dans la mesure où le préjudice que subirait le Client ait été causé par une faute intentionnelle ou lourde des employés du Prestataire.,",
            ",3.4 Le Prestataire s’engage à prendre toutes les précautions d’usage eu égard aux Prestations qui lui sont confiées pour la protection des données, programmes et systèmes d’exploitation auxquels il pourra avoir accès. Compte tenu de l’importance des données et de leur caractère strictement confidentiel, le Prestataire s’engage à assurer une sécurité optimale desdites données notamment en termes d’accès à ses locaux. ,",
            ",Le Prestataire s engage à garantir au Client, ainsi qu’à ses collaborateurs affectés à l exécution du présent Contrat, le libre accès aux dits locaux.,",
            ",3.5 Le Client tiendra à la disposition du Prestataire les procédures de traitement nécessaires ainsi que toutes les informations pouvant contribuer à la bonne réalisation des Prestations. A cette fin, chacune des Parties désigne respectivement deux interlocuteurs privilégiés pour assurer le dialogue dans les diverses étapes de la mission contractée.,",
            ",3.6 Le Client s’engage à effectuer toutes les déclarations prévues par les dispositions légales et règlementaires en vigueur découlant des présentes auprès des autorités de régulation y afférentes (la CNIL, l’autorité de régulation en charge des droits d’auteurs, …). Le Prestataire ne saurait être inquiété pour la régularité de ces déclarations sauf celles lui incombant en sa qualité de Prestataires de service. ,",
            ",Article 4 : Evolutions des Prestations,",
            ",Au cours de l’exécution des Prestations, les Indicateurs et/ou valeurs de ces Indicateurs contractuellement prévus pourront évoluer selon les conditions suivantes :,",
            ",-\tProposition d’Evolution : au titre de son obligation de conseil, le Prestataire doit être proactif à l’égard du Client et doit à ce titre l’informer de toute évolution d’un ou plusieurs Indicateur(s) susceptible(s) d’optimiser la qualité et la productivité des Prestations réalisées et/ou de tout Indicateur ou valeur de ces Indicateurs qui ne seraient pas en adéquation avec les Prestations confiées. ,",
            ",-\tRévision : la révision des Indicateurs de suivi de l’activité et/ou valeurs sera validée par le Client et aussitôt appliquée par le Prestataire.,",
            ",Les évolutions considérées comme substantielles en Comité de Pilotage donneront lieu à l’établissement d’un avenant au Contrat.,",
            ",Le Prestataire pourra soumettre au Client un devis dans le cas d’une demande de modification des objectifs si les hypothèses contractuellement prévues sont remises en cause.,",
            ",Article 5 : Modalités de commande,",
            ",L’accord entre les Parties est formalisé par la signature du Bon de Commande et du présent contrat par le Client. ,",
            ",Les Parties conviennent de préciser que la signature du Bon de Commande constitue le point de départ des relations contractuelles entre les Parties. A ce titre, toute prestation fournie au profit Client à compter de la date de signature du Bon de Commande sera de plein droit facturée au Client.,\t,",
            ",Toutefois, les Parties conviennent de rappeler que les dispositions prévues par les présentes prévalent aux dispositions prévues dans le Bon de Commande. ,"
            ,",Article 6 : Conditions Financières,"
            ,",Les Parties conviennent des conditions financières ci-après :,",
            ",Les tarifs y sont exprimés en Euro Hors Taxe. ,",
            ",*6.1Frais de mise en place,",
            ",Les Parties conviennent de la facturation des frais de mise en place pour le traitement des missions ci-dessous : ,",
            ",o","La mise en place de l’infrastructure technique, ,o",
            ",La mise en place RH : recrutement et formation, ,o",
            ",La mise en place du process, ,o",
            ",La mise en place du format de reporting.,",
            ",Désignation","Prix en € HT,"
            ,",Frais de Mise en Place",
            ",Frais de Formation des Agents",
            ",Le Client s’engage à s’acquitter des frais de formation prévus ci-dessus à la signature du présent contrat. ,",
            ",6.2 Frais de Pilotage,",
            ",Les Parties ont convenu d’une facturation mensuelle de pilotage comme suit :,",
            ",Désignation",
            ",Prix € HT",
            ",Forfait mensuel de pilotage",
            ",6 .3 Tarification de la Production,",
            ",Les Parties ont convenu d’une tarification à l’acte/à l’heure des Prestations effectuées par le Client comme suit :,",
            ",Désignation","Prix € HT",
            ",Prestation N°1",
            ",Prestation N°2",
            ",Prestation N°3",
            ",Dans l’hypothèse où le Client demanderait au Prestataire d’effectuer ses Prestations en dehors des heures ouvrables correspondant aux heures du dimanche, des jours fériés français et tous les autres jours de 22h à 6h, le Prestataire appliquera des Tarifs HNO soit une majoration de 50% à la tarification habituelle.,",
            ",",
            ",6.4Facturation et paiement,",
            ",Les factures de production sont établies mensuellement par le Prestataire le dernier jour ouvré du mois de production.,",
            ",Le paiement des factures peut être fait par virement bancaire ou par chèque, suivant les coordonnées indiquées dans le Bon de Commande ou dans la facture y afférente, dans un délai de trente (30) jours calendaires à compter de la date d’émission de la facture.,",
            ",En cas de non-paiement dans le délai susmentionné et après mise en demeure adressée par courrier écrit avec avis de réception restée sans effet pendant un délai de quinze (15) jours, des intérêts de retard calculés sur le montant de la facture impayée sur la base arrondie à l’unité supérieure de trois (3) fois le taux d intérêt légal seront dus.,",
            ",Toutes les prestations réalisées par le Prestataire et non prévues dans le Bon de Commande, le Contrat ou ses avenants éventuels, donneront nécessairement lieu, préalablement à leur exécution, à l’émission d’un devis par le Prestataire et par l’acceptation expresse du devis correspondant par le Client, ainsi qu’à la signature de l’avenant y afférent.,",
            ",Dans le cas où une facture ne serait pas réglée à l’échéance, le Prestataire se réserve le droit de suspendre les Prestations objet du Contrat, après mise en demeure restée sans effet pendant trente jours, sans que cette suspension puisse être considérée comme une résiliation du fait du Prestataire.,",
            ",6.5Révision des tarifs,",
            ",Les tarifs sont révisés le 1er janvier de chaque année, sur la base de l’INDICE SYNTEC REVISE en appliquant la formule suivante : P1 = P0 x (S1/S0)",
            ",Où",
            ",P1 = Prix révisé",
            ",P0 = Prix d’origine ",
            ",S0 = Indice SYNTEC réviséde référence retenue à la date contractuelle d’origine ",
            ",S1 = Indice du SYNTEC révisé publié à la date de la révision.,",
            ",Article 7 : Responsabilité,",
            ",Chaque Partie est responsable de l’exécution des obligations qui lui incombent, au regard du Contrat. En particulier, le Prestataire s’engage à la mise en œuvre de tous les moyens nécessaires à la réalisation des Prestations décrites dans le présent Contrat et sera tenue à ce titre d’une obligation de résultat, au titre des objectifs et qualités de service définis.,",
            ",Le Prestataire prend à sa charge toutes les conséquences financières des dommages corporels, matériels et/ou immatériels subis par son Personnel à l occasion de l exécution des Prestations.,",
            ",En aucune circonstance le Prestataire ne pourra être considéré comme responsable de dommages de nature indirecte de quelque nature que ce soit. La responsabilité du Prestataire est engagée dans la limite de la couverture de son assurance responsabilité civile professionnelle.,",
            ",Article 8 : Assurances,",
            ",Le Prestataire et le cas échéant son sous-traitant déclare qu il est titulaire d une police d assurance couvrant sa responsabilité civile professionnelle dans le cadre de l’exécution du Contrat auprès d’une compagnie notoirement solvable. Il s engage à fournir au Client, à sa demande, les attestations correspondantes.,",
            ",Article 9 : Propriété intellectuelle,",
            ",Le Client apporte au Prestataire le soutien et la documentation dont celui-ci peut avoir besoin pour l exécution des Prestations.,",
            ",Il met à sa disposition tous les renseignements et informations qui s avèrent nécessaires à l exécution des Prestations, étant entendu que le Client en reste propriétaire, et que cette mise à disposition ne peut en aucun cas et d aucune manière être considérée comme conférant au Prestataire un quelconque droit de propriété intellectuelle sur ces renseignements et informations.,",
            ",Chaque Partie conserve la propriété exclusive des marques, des brevets, des logiciels, des dessins et modèles, du savoir-faire et des informations lui appartenant, développés ou acquis antérieurement à l entrée en vigueur du présent Contrat ou en dehors du cadre de celui-ci. En conséquence, aucune disposition du Contrat ne peut être interprétée comme entraînant la concession explicite ou implicite d un tel droit de propriété intellectuelle ou tout autre droit par l une des Parties à l autre Partie.,",
            ",Tous les documents réalisés ainsi que les résultats (découvertes, améliorations, mises au point, créations logicielles, inventions brevetables ou non, ...) obtenus dans le cadre du Contrat pour les besoins du Client par le Prestataire, sont et restent la propriété exclusive du Client au fur et à mesure de leur réalisation, et ce, sans limitation de durée et de territoire. ,",
            ",Le Prestataire s engage à remettre au Client lesdits documents et/ou résultats et, le cas échéant, les codes sources, au fur et à mesure de l exécution des Prestations et aux dates indiquées par le Client.,",
            ",Article 10 : Confidentialité ,",
            ",Le Prestataire s engage à considérer comme strictement confidentiels, tant au sein de sa propre organisation que vis à vis des tiers, les Informations Confidentielles, qui lui sont transmises par le Client, quel que soit le support utilisé pour cette transmission (papier, supports informatiques, transmission orale etc.) ou la forme de cette transmission, ou qu il a pu obtenir ou dont il a eu autrement connaissance au titre de l’exécution du Contrat. A cet effet, le Prestataire s’engage à :,•",
            "n’utiliser les Informations Confidentielles qu’aux seules fins de l’exécution du Contrat et dans la stricte mesure du nécessaire ;,•",
            ",prendre toutes les mesures de précaution et de protection qui s’imposent aux fins de préserver la confidentialité des Informations Confidentielles et d empêcher l accès de personnes non autorisées ;,•",
            ",et à ne divulguer ou reproduire les Informations Confidentielles qu’aux personnes qui devront avoir accès à ces Informations Confidentielles pour remplir les obligations dont le Prestataire est tenu par le Contrat, ou qui ont qualité pour en connaître au titre du Contrat.,",
            ",Le Prestataire et ses collaborateurs s engagent à restituer au Client, à l expiration du Contrat, quelle qu en soit la cause :,",
            ",- le cas échéant, les mots, codes et clefs d accès aux machines et aux logiciels informatiques qui leur avaient été attribués,",
            ",- et plus généralement, tous les documents, supports lisibles par ordinateur, rapports qui leur auront été remis par le Client ou ses mandataires, y compris les copies qui auraient pu en être faites.,",
            ",Le présent article ne s applique pas aux éléments « d Informations Confidentielles » :",
            ",- qui étaient du domaine public au moment de leur divulgation ou sont tombés dans le domaine public sans qu il y ait eu contravention au Contrat,",
            ",- dont le Prestataire pourrait prouver qu ils étaient en sa possession antérieurement à la date d effet du Contrat,",
            ",- qui résultent de développements internes menés par le Prestataire sans utilisation d informations confidentielles au sens du présent article,",
            ",- qui sont communiqués au Prestataire ou à ses collaborateurs ou employés par des tiers aux présentes sans qu il y ait contravention au présent article,",
            ",- qui sont divulgués avec l accord préalable et écrit du Client.,",
            ",Dans tous les cas, le Prestataire se porte garant du respect de cet engagement de confidentialité par les personnes ayant connaissance des Informations Confidentielles.,",
            ",La présente obligation de confidentialité restera en vigueur pendant toute la durée du Contrat et 3 (trois) ans après la fin du Contrat.,",
            ",Article 11 :Protection des données à caractère personnel,",
            ",Les Parties déclarent se conformer aux dispositions légales et réglementaires relatives à la protection des données à caractère personnel en vigueur en France, conformément au Règlement Général sur la Protection des Données 2016/679 du 27 avril 2016 abrogeant la directive 95/46/CE (ci-après 'Règlementation').,",
            ",A cet égard, le Prestataire décrit en Annexe_DPA (ci-après « Annexe Protection des Données Personnelles ») des présentes les mesures techniques et organisationnelles déployées en conformité avec la Réglementation.,",
            ",Le Prestataire garantit au Client que les données à caractère personnel auxquelles il a accès dans le cadre de l’exécution de ses Prestations sont conservées et traitées conformément aux clauses de l’Annexe_DPA.,",
            ",Par ailleurs, le Client et le Prestataireont convenu qu’ils agissent respectivement en qualité de [43;responsable_de_traitement] et de [42;sous-traitant] . Etant à rappeler que les Prestations sont exécutées à/au Madagascar/Maroc et que ce dernier n’étant pas considéré par la Commission européenne comme offrant un niveau de protection des données à caractère personnel suffisant, les Parties signent les clauses contractuelles types approuvées par la Commission européenne de responsable de traitement à responsable de traitement, figurant en Annexe, afin de se conformer à la Règlementation, notamment à la Décision d’Exécution (UE) 2021/914 de la Commission du 4 juin 2021 relative aux clauses contractuelles types pour le transfert des données à caractère personnel vers des pays tiers en vertu du règlement (UE) 2016/679 du Parlement européen et du Conseil. ,Les Parties s’entendent à ce que ces clauses contractuelles types de la commission européenne soient directement signées entre [41;".str_replace(" ","_",$customer->getRaisonSocial())."] et [40;".str_replace(" ","_",$bdcProd->getSocieteFacturation()->getLibelle())."] étant donné que les transferts de données se font directement entre ces dernières.,Le Prestataire s’engage à mettre en oeuvre les moyens informatiques et physiques permettant de préserver la sécurité et l’intégrité des données à caractère personnel auxquelles il a accès dans le cadre de l’exécution de ses Prestations, afin d’empêcher qu’elles soient déformées, endommagées ou que des tiers non autorisés y aient accès. La durée de sauvegarde desdites données dans nos bases sera définie par les Parties dans l’annexe suscitée, laquelle prévaut en cas de contradiction avec les dispositions des présentes.,",
            ",Article 12 : Clause d’exclusivité,",
            ",Le Client et le Prestataire ne sont pas tenus à aucune exclusivité à l’égard de l’autre Partie. En effet, chaque Partie demeure, sans réserve, libre d’entrer en négociation avec d’autres prestataires ou clients.,",
            ",Article 13 : Non sollicitation de personnel,",
            ",Les Parties s’obligent à une obligation de loyauté réciproque et, à ce titre, s’interdisent expressément, directement ou indirectement, pour quelle que cause que ce soit, et de quelque manière que ce soit, de recruter, de contacter ou d avoir recours au service, directement ou indirectement, les employés, ayant collaboré ou collaborant avec le Client ou le Prestataire, sauf accord exprès préalable de l’autre Partie.,",
            ",Dans le cas où l’une des Parties ne respecterait pas cette obligation, elle s’engage à dédommager l’autre Partie en lui versant une indemnité forfaitaire égale à six fois le dernier salaire brut mensuel du collaborateur débauché.,",
            ",Article 14 : Entrée en vigueur – Durée du Contrat,",
            ",Le Contrat entre en vigueur à compter du [28;] , pour une durée de 01 an. Il est renouvelable par tacite reconduction pour la même période sauf dénonciation de non-renouvellement effectuée par l’une des parties, 03 mois calendaires à avant son arrivée à échéance par courrier écrit avec accusé de réception.,",
            ",Article 15 : Résiliation du Contrat,",
            ",En cas de manquement par l une des Parties à l une quelconque de ses obligations au titre du Contrat, l autre Partie peut, soixante (60) jours après l envoi d’un courrier écrit avec avis de réception l invitant à y remédier restée infructueuse, résilier le Contrat de plein droit et sans formalités judiciaires, sans préjudice de tous dommages et intérêts auxquels elle pourrait prétendre du fait de ce manquement.,",
            ",Le Contrat peut également prendre fin par accord mutuel entre les Parties ou en cas de non règlement par le Client des factures du Prestataire soixante (60) jours ouvrés après mise en demeure de payer envoyée par lettre recommandée avec avis de réception.,",
            ",Article 16 : Obligations en fin de contrat - Garantie de réversibilité ,",
            ",Principe",
            ",Le Prestataire s’engage, dans les conditions ci-après définies, à assurer la réversibilité des Prestations afin de permettre au Client, sans difficulté, de reprendre ou de faire reprendre par un tiers désigné par lui la fourniture desdites Prestations et ce, dans les meilleures conditions. Les conditions de cette réversibilité seront conformes à l’état de l’art et négociées entre les Parties.,",
            ",Pour ce faire, le Prestataire s’engage, pour son site d’exploitation, sauf accord préalable et écrit du Client, à mettre en œuvre uniquement des solutions matérielles et/ou logicielles qui devront être facilement portables, c’est à dire qu’elles doivent pouvoir être transférées sur un autre site informatique, sans difficulté.,",
            ",Informations en vue du transfert",
            ",En cas d’extinction du Contrat, pour quelque motif que ce soit, le Client sera en droit d’obtenir du Prestataire que ce dernier lui communique, à tout moment et dans un délai de sept jours, les informations qui lui sont nécessaires pour lui permettre de préparer la réversibilité.,",
            ",Eléments à transférer",
            ",A la date d’extinction du Contrat pour quelle que cause que ce soit, le Prestataire tiendra à la disposition du Client, les éléments suivants :,-",
            ",Les données sur supports magnétiques, ainsi que les fichiers et résultats des traitements du Client la documentation opérationnelle dans sa dernière version et l’ensemble des documentations de maintenance.,-",
            ",Et plus généralement, tout document et/ou élément qui aurait été mis à sa disposition par le Client.,",
            ",Article 17 : Sous-traitance,",
            ",Le Prestataire s’engage à ne pas sous-traiter tout ou partie des Prestations et à ne pas transférer le Contrat sans accord préalable écrit du Client. A défaut le Client est en droit de résilier le Contrat sans indemnité pour le Prestataire. ,",
            ",Par la présente, le Client autorise expressément le Prestataire à sous-traiter tout ou une partie des Prestations qui lui sont confiées par le Client à sa filiale [29;] basée à [30;".$bdc->getPaysProduction()->getLibelle()."] . Le Prestataire s’engage à se conformer aux dispositions légales et règlementaires en vigueur pour la mise en œuvre des Prestations à l’étranger et notamment dans les pays en dehors de l’UE.,",
            ",Article 18 : Circulation du Contrat,",
            ",Il est convenu entre les Parties que le Prestataire est dument autorisé à céder librement tout ou une partie du Contrat à toute société et/ou partenaire dans laquelle/lequel le Prestataire est en relation directe ou indirecte d’actionnariat et/ou d’administration. ,",
            ",En outre, pendant toute la durée du Contrat, les Parties sont libres de procéder à tout changement de structure d’actionnariat et/ou d’administration en son sein. ,",
            ",Article 19 : Clause sur la lutte contre la corruption et le blanchiment de capitaux",
            ",Le groupe OUTSOURCIA affiche une tolérance zéro pour toute forme de corruption dans son domaine professionnel et entend que toute personne en relation avec le Groupe adhère aux mêmes principes. ",
            ",Les Parties déclarent se conformer aux dispositions légales et règlementaires relatives à la lutte contre la corruption et le blanchiment de capitaux applicables au présent Contrat. ",
            ",Les Parties déclarent qu’elles ont respectivement pris toutes les mesures nécessaires et ont notamment adopté et mis en œuvre des procédures et codes de conduites adéquats afin de prévenir toutes violation de ces lois et règlementations relatives à la lutte contrat la corruption et le trafic d’influence ainsi que le blanchiment de capitaux.",
            ",A ce titre, les Parties garantissent qu’ :,-",
            ",-Aucune offre, aucun don, cadeau ou paiement, aucune rémunération ou avantage d aucune sorte constituant ou pouvant constituer un acte illicite ou une pratique de corruption ou frauduleuse n a été ou ne sera accordé à qui que ce soit, directement ou indirectement, en vue ou en contrepartie de l attribution ou de l exécution du présent contrat. ,-",
            ",-Les fonds et capitaux investis dans le cadre du présent contrat proviennent de sources licites et n’ont fait ni font l’objet d’aucun processus de déguisement ni de dissimulation d’origine ou de nature issue d’activités illicites. ,-",
            ",-Tout contrat fondé sur des pratiques, ou tentatives visant à mettre en œuvre des pratiques, relevant de la description qui figure au point 1 et 2 de la présente clause, sera considéré comme nul et non avenu, sans préjudice des sanctions disciplinaires, civiles ou pénales encourues le cas échéant par les personnes ayant pris part auxdites pratiques.,-",
            ",-Les règles qui précèdent valent également pour tout amendement ou addendum au contrat.,",
            ",Article 20 : Force majeure,",
            ",Aucune des Parties ne pourra être tenue responsable d un manquement quelconque à ses obligations dans le cadre du Contrat, si un tel manquement résulte d une décision gouvernementale, en ce compris le retrait ou la suspension des autorisations accordées au Client, d un incendie, d un état de guerre déclarée, d une guerre civile, d actes de terrorisme ou d une grève nationale, et plus généralement, tout autre événement de force majeure présentant les caractéristiques définies par la jurisprudence de la Cour de Cassation.,",
            ",La Partie affectée dans l exécution de ses obligations par la survenance d un cas de force majeure doit immédiatement avertir l autre Partie de la survenance d un cas de force majeure. Les Parties s efforcent alors de prendre les mesures propres à pallier les conséquences de l événement. Toutefois, en cas de persistance de l événement au-delà de un (1) mois, le Contrat peut être rompu par la Partie la plus diligente, sans qu aucune indemnité ne soit due par elle à l autre Partie à ce titre.,",
            ",Article 21 : Modifications et mise à jour,",
            ",Toute modification à apporter au Contrat devra faire l’objet d’un avenant dument signé par les Parties pour être effective.,",
            ",Article 22 : Mise en conformité et validité,",
            ",Toutes les dispositions contractuelles existantes entre les Parties devront être mises en conformité en cas de changement ou évolution des dispositions légales, administratives et règlementaires en vigueur et modifiées en ce sens, sans que cela ne puisse être considéré comme étant une cause pouvant justifier une résiliation du Contrat.,",
            ",Si une ou plusieurs dispositions du Contrat est déclarée nulle en application d une loi, d un règlement ou à la suite d une décision définitive d une juridiction compétente, cette disposition est considérée comme détachable du Contrat. Les autres dispositions du Contrat sont considérées comme valides et restent en vigueur, à moins que l une des Parties ne démontre que la disposition annulée revêt un caractère essentiel et déterminant sans lequel elle n aurait pas contracté.,",
            ",Article 23 : Loi applicable,",
            ",Le Contrat est régi par les dispositions légales et règlementaires en vigueur en France.,",
            ",Article 24 : Règlement des litiges - Attribution de juridiction,",
            ",Les Parties s efforceront de résoudre à l amiable tout différend susceptible d intervenir entre elles à l occasion de l interprétation ou de l exécution du Contrat.,",
            ",A défaut d’accord amiable, tout litige relatif à la conclusion, l’interprétation, l’exécution ou la résiliation du Contrat sera soumis à la compétence exclusive du Tribunal de commerce de Paris, y compris pour les procédures d’urgence ou les procédures conservatoires, en référé ou par requête, même en cas de demande incidente, de pluralité de défendeurs ou d’appel en garantie.,",
            ",Article 25 : Dispositions diverses",
            ",Le fait pour l une des Parties de ne pas se prévaloir d un manquement par l autre Partie à l une quelconque des obligations visées dans le Contrat ne saurait être interprété, pour l avenir, comme une renonciation à l obligation en cause.,",
            ",Toute notification entre les Parties, en application ou dans le cadre du Contrat, devra être faite par écrit et envoyée par lettre recommandée avec avis de réception,",
            ",Le Contrat ne crée pas d’association ou de société entre les Parties. Sans préjudice des dispositions contraires prévues au Contrat, chaque Partie est seule responsable à l’égard des tiers des engagements qu’elle a souscrits.,",
            ",Article 26 : Intégralité du contrat,",
            ",La relation contractuelle entre les Parties est régie par les documents contractuels ci-après, cités par ordre de priorité et de préférence décroissante :,-
            ",",Le présent Contrat, ses annexes et éventuels avenants ;,-",
            ",Le ou les Bon(s) de Commande signés.,",
            ",L’ensemble de ces documents représente l’intégralité des engagements existants entre le Prestataire et le Client. ,",
            ",Les Parties conviennent de rappeler que le Contrat ne peut être modifié que par avenant écrit dument signé par les deux (2) Parties.,",
            ",Article 27: Convention de preuve,",
            ",Les Parties conviennent expressément que le Contrat sous format PDF « Portable Document Format » pris en sa version finale signée par les deux Parties, constitue un document original parfaitement valable entre elles.,",
            ",Aussi, les Parties reconnaissent et acceptent que le Contrat sous format PDF ait la même valeur probante qu’un écrit sur support papier conformément à l’article 1316-3 du Code Civil et pourra être valablement opposé entre elles.,",
            ",Article 28 : Interlocuteurs,",
            ",Chacune des Parties reconnaît que les personnes désignées ci-après la représentent et ont toute autorité pour poser les actes, prendre les décisions et donner les autorisations requises en vue de l exécution du Contrat.,",
            ",ENTITE",
            ",NOM"
            ,",Rôle"
            ,",Contact"
            ,",[31;".str_replace(" ","_",$customer->getContacts()[0]->getNom() ." ". $customer->getContacts()[0]->getPrenom())."]"
            ,",[33;] ,",
            ",Article 29 : Election de domicile,",
            ",Pour les besoins relatifs au Contrat, les Parties font élection de domicile aux adresses sus-indiquées.,",
            ",Fait à Evreux, le [34;".date("D/M/d,Y-G:i")."] , en deux (02) exemplaires originaux.,",
            ",Pour le Prestataire,,",
            ",Youssef CHRAIBI,Président\tPour le Client,,",
            ", [35;".str_replace(" ","_",$customer->getContacts()[0]->getNom() ." ". $customer->getContacts()[0]->getPrenom())."] ,en sa qualité de [36;".$customer->getCategorieClient()->getLibelle()."]"
        ];
        $index=0;
        foreach($TexteAncien as $Text){
            $TextAncienSansVirgule[$index]=substr($Text,1);
            $index++;
        }

        return $TextAncienSansVirgule;
    }
}
