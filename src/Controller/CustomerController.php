<?php

namespace App\Controller;

use App\Entity\Bdc;
use App\Entity\CategorieClient;
use App\Entity\ClientDocument;
use App\Entity\Contact;
use App\Entity\ContactHasProfilContact;
use App\Entity\Customer;
use App\Entity\HausseIndiceBdco;
use App\Entity\Historique;
use App\Entity\MappingClient;
use App\Entity\ProfilContact;
use App\Entity\StatusLead;
use App\Entity\TypeDocument;
use App\Entity\User;
use App\Entity\WorkflowLead;
use App\Models\BdcOperationPerClient;
use App\Models\HausseIndice;
use App\Entity\HauseIndiceLignefacturation;
use App\Entity\HausseIndiceSyntecClient;
use App\Repository\BdcRepository;
use App\Repository\ContactHasProfilContactRepository;
use App\Repository\CrmActuelRepository;
use App\Repository\CustomerRepository;
use App\Repository\MappingClientRepository;
use App\Repository\ContactRepository;
use App\Repository\CategorieClientRepository;
use App\Repository\HistoriqueRepository;
use App\Repository\OperationRepository;
use App\Repository\TypeDocumentRepository;
use App\Repository\HauseIndiceLignefacturationRepository;
use App\Repository\UserRepository;
use App\Repository\HausseIndiceSyntecClientRepository;
use App\Controller\BonDeCommandeController;
use App\Models\BdcParIdMere;
use App\Repository\BdcOperationRepository;
use App\Repository\CoutHoraireRepository;
use App\Service\CurrentBase64Service;
use App\Service\FileManipulate;
use App\Service\Lead;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Hybridauth\HttpClient\HttpClientInterface;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\StatusLeadRepository;
use App\Service\MixedService;
use App\Services\Base64Service;
use App\Service\SendMailTo;

use DateTime;
use PhpParser\Node\Stmt\Break_;

/**
 * @Route("/api")
 */
class CustomerController extends AbstractController
{
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
     /**
     * @Route("/customers/contact", name="list_customer_fotsiny_contact", methods={"GET"})
     */
    public function getAllCustomerEtContact(CustomerRepository $customerRepository): Response
    {
        try {
            $Customers = $customerRepository->findAll();
            $data=[];
            $contactMax=0;
            foreach($Customers as $cust){
                $temp =[];
                array_push($temp,$cust->getRaisonSocial());
                $nombreContact = 0;
                foreach ($cust->getContacts() as $cont){
                    array_push($temp,$cont->getNom());
                    $nombreContact++;
                }
                if($contactMax<$nombreContact){
                    $contactMax=$nombreContact;
                }
                $data[] = $temp; 
            }

            $spreadsheet = new Spreadsheet();

            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setTitle('TestFetra');

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
            $Char ="A";
            for ($i=0;$i<$contactMax;$i++,$Char++);
            
            $ligneCsv = "A1:".$Char."1";
            $sheet->getStyle($ligneCsv)->applyFromArray($styleArray);

            for($i=0,$Char ="A"; $i<count($Customers); $i++, $Char++){
                $sheet->getColumnDimension($Char)->setAutoSize(true);
            }
            $sheet->fromArray($data, null, 'A2', true);
            $writer = new Xlsx($spreadsheet);
            $filename = 'testfetra.xlsx';
            $writer->save($filename);
            //return $this->json($filename, 200, [], ['groups' => ['bdcs']]);
            return $this->json($Customers, 200, [],
                ['groups' => ['customer', 'contact-att', 'contact-profil-contact']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }

    }

    /**
     * @Route("/customers/ancien/customer/{idCustomer}", name="ancienLigne", methods={"GET"})
     */
    public function ancienLigne(HausseIndiceSyntecClientRepository $repoSyntecclient,HauseIndiceLignefacturationRepository $repoLigne,CustomerRepository $repoCust,$idCustomer): Response{
        try {
            $HausseClient=$repoSyntecclient->findOneBy(['id_customer' => $idCustomer]);
            $client=$repoCust->find($idCustomer);
            
            $HausseAJour =[];
            if($HausseClient && $client){
                $HausseBdcO=$repoLigne->getHausseBdcO($HausseClient->getId());
                $Tableau = [];
                $HausseAJour [0]= $HausseClient;
                $BdcParcouru =[];
                $typeMixte =0;
                foreach($HausseBdcO as $HBdcO){
                    if($typeMixte==0 && in_array($HBdcO->getIdOperation(),$BdcParcouru)){
                        break;
                    }
                    if($typeMixte==2 && in_array($HBdcO->getIdOperation(),$BdcParcouru)){
                        break;
                    }
                    $hausse= new HausseIndice();
                    $hausse->client=$client->getRaisonSocial();
                    $hausse->idBdcO=$HBdcO->getIdOperation();
                    if($HBdcO->getAncienPrix()){
                        $hausse->PrixUnitaire=$HBdcO->getAncienPrix();
                        $Tableau []= $hausse;
                    }
                    else{
                        if($HBdcO->getAncienPrixActe()){
                            $hausse->PrixUnitaire=$HBdcO->getAncienPrixActe();
                            $typeMixte++;
                            $Tableau []= $hausse;
                        }
                        elseif($HBdcO->getAncienPrixHeure()){ 
                            $typeMixte++;
                            $hausse->PrixUnitaire=$HBdcO->getAncienPrixHeure();
                            $Tableau []= $hausse;
                        }
                    }
                    $BdcParcouru[]= $hausse->idBdcO;
                }
                $HausseAJour [1] = $Tableau;
            }
            
            return $this->json($HausseAJour);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }
    /**
     * @Route("/customers/update/hausse", name="updateHausse", methods={"POST"})
     */
    public function updateHausse(Request $request,Lead $lead,HausseIndiceSyntecClientRepository $repoSyntecclient,HauseIndiceLignefacturationRepository $repoLigne,BonDeCommandeController $bdcControlleur): Response{
        $object=json_decode($request->getContent(), 'true');
        $actuel = $object["actuel"];
        $ligne = $object["ligne"];
        $taux =  $object["taux"];
        $clause = $object["clause"];
        $dateContart = $object["dateContart"];
        $idCustomer = $object["idCustomer"];
        $initial = $object["initial"];
        $isType = $object["isType"];
        $raisonSocial = $object["raisonSocial"];
        $valide = $object["valide"];
        $HausseClient = new HausseIndiceSyntecClient();
        $HausseClient->setActuel($actuel);
        //$HausseClient->setClause($clause);
        if($clause === "non"){
            $HausseClient->setClause(0);
        }
        else{
            $HausseClient->setClause(1);
        }
        //dd($object["dateContart"]);
        $dateTemp= (strtotime($dateContart));
        // dd($dateTemp);
        $date = new \DateTime($dateContart);
        $HausseClient->setDateContrat($date);
        $HausseClient->setDateYears(new \DateTime());
        $HausseClient->setIdCustomer($idCustomer);
        $HausseClient->setInitial($initial);
        $HausseClient->setStatus(1);
        $HausseClient->setTauxEvolution($taux);
        if($isType === "Smic"){
            $HausseClient->setIsType(2);
        }else if($isType === "Syntec")
            $HausseClient->setIsType(1);
        else $HausseClient->setIsType(3);
        $repoSyntecclient->add($HausseClient);
        $SyntecClient=$repoSyntecclient->GetByCustomerYears($idCustomer);
       $tab =array();
        foreach($ligne as $l){
            $HausseBdcO = new HauseIndiceLignefacturation();
            $HausseBdcO->setIdOperation($l["idBdcO"]);
            $HausseBdcO->setAncienPrix($l["PrixUnitaire"]);
            $prixfarany= ($taux+100)*$l["PrixUnitaire"]/100;
            $HausseBdcO->setNouveauPrix($prixfarany);
            $HausseBdcO->setHausseIndeceClientId($SyntecClient->getId());
            $repoLigne->add($HausseBdcO);
            array_push($tab,$HausseBdcO);
        }
        return $this->json($HausseClient);
    }
    /**
     * @Route("/customers/get/cust/bdco/hausse", name="getCustomerAndBdcPourHausse", methods={"GET"})
     */
    public function getCustomerAndBdcPourHausse(HausseIndiceSyntecClientRepository $repoSyntecclient,CustomerRepository $customerRepository , BdcRepository $bdcRepository ,HausseIndiceSyntecClientRepository $repoHausseClient): Response
    {
        try {
            $hausseResultat =array();
            $HausseSyntec=[];
            #Prendre tous les client
            $allCustomer= $customerRepository->getToutCustomerSansUser();
            $page=0;
            $i=0;
            foreach($allCustomer as $cust){
                #Parcours un par un les client
                $clientTemp= $customerRepository->find($cust["id"]);
                $raisonSocialTemp=$clientTemp->getRaisonSocial();
                $lastBdcByIdMere = [];
                $res = $bdcRepository->getBdcForOneCustomer2($cust["id"]);
                // dd($cust);
                #Get Les Dernier Bdc by ID mere avec 
                foreach($res as $bdcFormOneCustomer){
                    if(in_array($bdcFormOneCustomer->getStatutLead(), $this->getParameter("statut_lead_bdc_in_prod")))
                        $lastBdcByIdMere[$bdcFormOneCustomer->getIdMere()]= $bdcFormOneCustomer;
                }
                
                $resutlt=[];
                $BdcOperationParClient = new BdcOperationPerClient();
                $statusDuDernierBdc = $this->getParameter("statut_lead_bdc_on_prod");
                $BdcOperationParClient->idCustomer=$clientTemp->getId();
                #Ito no soloina asina daty rehefa oavy ny Hausse indice isatona
                //$HausseClient=$repoSyntecclient->findOneBy(['id_customer' => $cust["id"]]);
                #maka ny Hausse io client io isakany tona anio
                $tab =$repoSyntecclient->getByYearsCurrentByIdCustomer($cust["id"]);
                # test na efa vita Update hausse na tsy
                if($tab == []){
                    $HausseClient =[];
                }
                else{
                    $HausseClient= $tab[0];
                }
                $bdcArrayParIdMere = [];
                $resultat= array();
                foreach($lastBdcByIdMere as $bdcPourUnIDMere){ 
                    $resultat =[];
                    $bdcByIdmere = new BdcParIdMere();
                    $bdcByIdmere->numBdc=$bdcPourUnIDMere->getNumBdc();
                    $bdcByIdmere->idMere=$bdcPourUnIDMere->getIdMere();
                    $bdcByIdmere->idBdc=$bdcPourUnIDMere->getId();
                    $r=$bdcPourUnIDMere;
                    if($r->getStatutLead() === $this->getParameter("statut_lead_bdc_on_prod") || $r->getStatutLead() === $this->getParameter("statut_lead_bdc_avenant_on_prod") || $r->getStatutLead() === $this->getParameter("statut_lead_bdc_avenant_signe_com")){
                        $statusDuDernierBdc = $r->getStatutLead();
                        #Test si il y a de hausse pendant l'anne
                        if( $repoHausseClient->CheckCustomerAJourOrNotByRsAndYears($clientTemp->getId()) !== []){
                            $BdcOperationParClient->valide=$HausseClient->getStatus();
                        }
                        else{
                            $BdcOperationParClient->valide=0;
                        }
                        $resutlt[$r->getId()]=$r->getBdcOperations();
                         foreach($resutlt[$r->getId()] as $lignefacturation){
                             if($lignefacturation->getPrixUnit() !== 0 && $lignefacturation->getPrixUnit() !== null){
                                 if($lignefacturation->getOperation()->getId() !== 1007 && $lignefacturation->getOperation()->getId() !== 13 && $lignefacturation->getOperation()->getId() !== 15 && $lignefacturation->getOperation()->getId() !== 1004){
                                     $hausse= new HausseIndice();
                                     $hausse->operationLabel=$this->HnoValueTest($lignefacturation).$lignefacturation->getOperation()->getLibelle(). " Prix Unitaire : ";
                                     $hausse->PrixUnitaire=$lignefacturation->getPrixUnit();
                                     $hausse->idBdcO=$lignefacturation->getId();
                                     $hausse->client=$raisonSocialTemp;
                                     $resultat[]=$hausse;
                                 }
                             }
                             if($lignefacturation->getPrixUnitaireActe() !== 0 && $lignefacturation->getPrixUnitaireActe() !== null){
                                
                                $hausse= new HausseIndice();
                                     $hausse->operationLabel=$this->HnoValueTest($lignefacturation).$lignefacturation->getOperation()->getLibelle(). " Prix Unitaire à l'Acte : ";
                                     $hausse->PrixUnitaire=$lignefacturation->getPrixUnitaireActe();
                                     $hausse->idBdcO=$lignefacturation->getId();
                                     $hausse->client=$raisonSocialTemp;
                                     $resultat[]=$hausse;
                                     
                                //dd($lignefacturation->getOperation()->getLibelle());
                             }
                             if($lignefacturation->getPrixUnitaireHeure() !== 0 && $lignefacturation->getPrixUnitaireHeure() !== null){
                                
                                $hausse= new HausseIndice();
                                     $hausse->operationLabel=$this->HnoValueTest($lignefacturation).$lignefacturation->getOperation()->getLibelle(). " Prix Unitaire à l'Heure : ";
                                     $hausse->PrixUnitaire=$lignefacturation->getPrixUnitaireHeure();
                                     $hausse->idBdcO=$lignefacturation->getId();
                                     $hausse->client=$raisonSocialTemp;
                                     $resultat[]=$hausse;
                                     //dd($lignefacturation->getOperation()->getLibelle());
                             }
                        }
                    }
                    $bdcByIdmere->bdcOpe=$resultat;
                    $bdcArrayParIdMere[]=$bdcByIdmere;
                }
                if($resultat !== array()){
                     //$valiny[$raisonSocialTemp]=$resultat; 
                    $BdcOperationParClient->raisonSocial=$raisonSocialTemp;
                    $BdcOperationParClient->ligne=$resultat;
                    $BdcOperationParClient->bdcParIdmere=$bdcArrayParIdMere;
                    
                    $contactReslut = [];
                    $test = 0;
                    #Algo pour envoyer les Examinateur
                    foreach($clientTemp->getContacts() as $cont){
                        foreach($cont->getContactHasProfilContacts() as $profil){
                            if($profil->getProfilContact()->getId() == 5) {
                                $test=1;
                                $contactReslut[]=$cont;
                                break;
                            }
                        }
                        if($test == 1) break;
                        #il n'y a pas d'examinateur
                       
                    }
                    if($test == 0) {
                        foreach($clientTemp->getContacts() as $cont){
                            foreach($cont->getContactHasProfilContacts() as $profil){
                                if($profil->getProfilContact()->getId() == 4) {
                                    $contactReslut[]=$cont;
                                }
                            }
                        }
                    }
                        $contactReslut=$contactReslut;
                        $BdcOperationParClient->contact=$contactReslut;
                        $BdcOperationParClient->statusBdc=$statusDuDernierBdc;
                        //soloina
                        $HausseSyntec[$i]=$BdcOperationParClient;
                        $i++;
                        array_push($hausseResultat,$BdcOperationParClient);
                    }
                
            }
            return $this->json($hausseResultat, 200, [],['groups' => ["contact","profil-contact","categorie","BdcOperationPerClient",'customer', 'contact-att', 'contact-profil-contact']]);
        }
        catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/customers/get/cust/bdco", name="list_customer_bdco", methods={"GET"})
     */
    public function getCustomerAndBdc(HausseIndiceSyntecClientRepository $repoSyntecclient,CustomerRepository $customerRepository , BdcRepository $bdcRepository ,HausseIndiceSyntecClientRepository $repoHausseClient): Response
    {
        try {
            /*$allCustomer= $customerRepository->getToutCustomerSansUser();
           $result=[];
            foreach($allCustomer as $cust){
                $result[$cust['id']]=$bdcRepository->getBdcForOneCustomer($cust['id']);
            }*/
            $hausseResultat =array();
            $HausseSyntec=[];
            $allCustomer= $customerRepository->getToutCustomerSansUser();
            //dd($allCustomer[0]);
            $page=0;
           /* $customerParPage=array();
            for($j=$page*10;$j<$page*10+10;$j++){
                array_push($customerParPage,$allCustomer[$j]);
            }
            $valiny=[];*/
            $i=0;
            foreach($allCustomer as $cust){
                $clientTemp= $customerRepository->find($cust["id"]);
                $raisonSocialTemp=$clientTemp->getRaisonSocial();
                
                $res = $bdcRepository->getBdcForOneCustomer2($cust["id"]);
                $resutlt=[];
                $resultat= array();
                $BdcOperationParClient = new BdcOperationPerClient();
                $BdcOperationParClient->idCustomer=$clientTemp->getId();
                #Ito no soloina asina daty rehefa oavy ny Hausse indice isatona
                //$HausseClient=$repoSyntecclient->findOneBy(['id_customer' => $cust["id"]]);
                $tab =$repoSyntecclient->getByYearsCurrentByIdCustomer($cust["id"]);
                if($tab == []){
                    $HausseClient =[];
                }
                else{
                    $HausseClient= $tab[0];
                }
                
                //dd(end($res));
                
                if(end($res)){
                    $r=end($res);
                    if($HausseClient){
                        $r=$res[0];
                    }
                    if($r->getStatutLead() === 11 || $r->getStatutLead() === 20 || $r->getStatutLead() === 19){
                        if( $repoHausseClient->CheckCustomerAJourOrNotByRsAndYears($clientTemp->getId()) !== []){
                            $BdcOperationParClient->valide=$HausseClient->getStatus();
                        }
                        else{
                            $BdcOperationParClient->valide=0;
                        }
                        $resutlt[$r->getId()]=$r->getBdcOperations();
                        // dd($r->getStatutLead());
                         foreach($resutlt[$r->getId()] as $lignefacturation){
                             if($lignefacturation->getPrixUnit() !== 0 && $lignefacturation->getPrixUnit() !== null){
                                 if($lignefacturation->getOperation()->getId() !== 1007 && $lignefacturation->getOperation()->getId() !== 13 && $lignefacturation->getOperation()->getId() !== 15 && $lignefacturation->getOperation()->getId() !== 1004){
                                     //if($lignefacturation->getStatus())
                                     $hausse= new HausseIndice();
                                     $hausse->operationLabel=$this->HnoValueTest($lignefacturation).$lignefacturation->getOperation()->getLibelle(). " Prix Unitaire : ";
                                     $hausse->PrixUnitaire=$lignefacturation->getPrixUnit();
                                     $hausse->idBdcO=$lignefacturation->getId();
                                     $hausse->client=$raisonSocialTemp;
                                     $resultat[]=$hausse;
                                 }
                             }
                             if($lignefacturation->getPrixUnitaireActe() !== 0 && $lignefacturation->getPrixUnitaireActe() !== null){
                                
                                $hausse= new HausseIndice();
                                     $hausse->operationLabel=$this->HnoValueTest($lignefacturation).$lignefacturation->getOperation()->getLibelle(). " Prix Unitaire à l'Acte : ";
                                     $hausse->PrixUnitaire=$lignefacturation->getPrixUnitaireActe();
                                     $hausse->idBdcO=$lignefacturation->getId();
                                     $hausse->client=$raisonSocialTemp;
                                     $resultat[]=$hausse;
                                     
                                //dd($lignefacturation->getOperation()->getLibelle());
                             }
                             if($lignefacturation->getPrixUnitaireHeure() !== 0 && $lignefacturation->getPrixUnitaireHeure() !== null){
                                
                                $hausse= new HausseIndice();
                                     $hausse->operationLabel=$this->HnoValueTest($lignefacturation).$lignefacturation->getOperation()->getLibelle(). " Prix Unitaire à l'Heure : ";
                                     $hausse->PrixUnitaire=$lignefacturation->getPrixUnitaireHeure();
                                     $hausse->idBdcO=$lignefacturation->getId();
                                     $hausse->client=$raisonSocialTemp;
                                     $resultat[]=$hausse;
                                
                                     //dd($lignefacturation->getOperation()->getLibelle());
                             }
                         }
                    }  
                }
                /*
                foreach($res as $r){
                    if($r->getStatutLead() === 11 || $r->getStatutLead() === 20 || $r->getStatutLead() === 19){
                        if( $r->getStatutLead() === 19){
                            $BdcOperationParClient->valide=1;
                        }
                        else{
                            $BdcOperationParClient->valide=0;
                        }
                        $resutlt[$r->getId()]=$r->getBdcOperations();
                        // dd($r->getStatutLead());
                         foreach($resutlt[$r->getId()] as $lignefacturation){
                             if($lignefacturation->getPrixUnit() !== 0 && $lignefacturation->getPrixUnit() !== null){
                                 if($lignefacturation->getOperation()->getId() !== 1007 && $lignefacturation->getOperation()->getId() !== 13 && $lignefacturation->getOperation()->getId() !== 15 && $lignefacturation->getOperation()->getId() !== 1004){
                                     //if($lignefacturation->getStatus())
                                     $hausse= new HausseIndice();
                                     $hausse->operationLabel=$lignefacturation->getOperation()->getLibelle();
                                     $hausse->PrixUnitaire=$lignefacturation->getPrixUnit();
                                     $hausse->idBdcO=$lignefacturation->getId();
                                     $hausse->client=$raisonSocialTemp;
                                     $resultat[]=$hausse;
                                 }
                             }
                         }
                    }  
                }*/
                if($resultat !== array()){
                     //$valiny[$raisonSocialTemp]=$resultat; 
                    $BdcOperationParClient->raisonSocial=$raisonSocialTemp;
                    $BdcOperationParClient->ligne=$resultat;
                    //soloina
                    $HausseSyntec[$i]=$BdcOperationParClient;
                    $i++;
                    array_push($hausseResultat,$BdcOperationParClient);
                }
            }
            
            /*$hausseResultatParPage=array();
            for($j=$page*10;$j<$page*10+10;$j++){
                array_push($hausseResultatParPage,$HausseSyntec[$j]);
            }*/
            //$bdcop =$repoBdcO->findAll();
            //return $this->json($resutlt, 200, [],['groups' => ['get-by-bdc']]);
            return $this->json($hausseResultat, 200, [],[]);
            //return $this->json($customerRepository->findAll(), 200, [],[]);
        }
        catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function HnoValueTest($lignefacturation){
        if($lignefacturation->getValueHno()){
            return "(HNO) ";
        }
        if($lignefacturation->getIsHnoDimanche()){
            return "(HNO Dimanche) ";
        }
        if($lignefacturation->getIsHnoHorsDimanche()){
            return "(HNO Hors Dimanche) ";
        }
        return "";
    }
    /**
     * @Route("/customers/sendEmail/juriste", name="sendEmailJuriste", methods={"GET"})
     */
    public function sendEmailJuriste(SendMailTo $sendMailTo, UserRepository $repoUser) {
        # Test Premier Janvier
        $reponseInsomnia= "il ñ'est pas le premier du mois de janvier";
        //if(date("j")===1 && date("m")===1){
            #Les Message
            $titre="Notification Pour Les Validation Des Hausses Indice Syntec ";
            $lien ="https://madacontact.com/crm_actuel/";
            $message ="c'est le premier du mois de janvier. L'outsorcia IT Bot vous informe qu'il était temps de passer au saisi des hausses indice syntec. merci de cliquer sur ce lien = ". $lien . ". Et Bonne année à toi et tous tes proches. Que les 12 mois à venir soient synonyme de joie, de rires, de bonne santé";
            #Get Tous Les Juriste
            $AllUser=$repoUser->getAllUser();
            $arrayEmail= [];
            $reponseInsomnia="C'est pas le premiers du mois de Janvier";
            foreach ($AllUser as $user){
                foreach($user->getRoles() as $role){
                    if($role == "ROLE_JURISTE"){
                        $arrayEmail[]=$user->getEmail();
                        $sendMailTo->sendEmail("telmestour@outsourcia-group.com", $user->getEmail(), $titre, $message, null);
                        $reponseInsomnia= "Email a ete Envoyer";
                    }
                }
            }
        //}
        return $this->json($reponseInsomnia);
    }

    /**
     * @Route("/customers", name="list_customer_fotsiny", methods={"GET"})
     */
    public function getAllCustomer(CustomerRepository $customerRepository): Response
    {
        try {
            return $this->json($customerRepository->findAll(), 200, [],
                ['groups' => ['customer', 'contact-att', 'cat-attr', 'mapp-attr', 'contact-profil-contact']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }

    }

    
    /**
     * @Route("/customer/list/{rowpage}/{page}/{filter}/{keybord}", name="customer_list_all", methods={"GET"})
     * @return Response
     */
    public function CustomerListe(CustomerRepository $customerRepository, PaginatorInterface $paginator, UserInterface $user,int $rowpage,int $page,string $filter,string $keybord): Response
    {
        try {
            if(in_array("ROLE_JURISTE",$user->getRoles())){
                if($filter == "empty" && $keybord =="empty"){
                    $allCustomers = $customerRepository->getMyAllCustomerPourJuriste($rowpage,$page);
                }else if($keybord == "empty"){
                    $allCustomers = $customerRepository->getMyAllCustomerPourJuriste($rowpage,$page);
                }
                else{
                    $allCustomers =$customerRepository->searchByKeywordPourJuriste($filter,$keybord,$rowpage,$page);
                }
            }
            else{
                if($filter == "empty" && $keybord =="empty"){
                    $allCustomers = $customerRepository->getMyAllCustomer($user->getId(),$rowpage,$page);
                }else if($keybord == "empty"){
                    $allCustomers = $customerRepository->getMyAllCustomer($user->getId(),$rowpage,$page);
                }
                else{
                    $allCustomers =$customerRepository->searchByKeyword($user->getId(),$filter,$keybord,$rowpage,$page);
                }
            }
            return $this->json($allCustomers ,200, [], ['groups' => ['status:lead']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

     /**
     * @Route("/customer2/list/{rowpage}/{page}/{raison}/{contact}/{nbdc}/{marque}/{category}/{map}", name="customer_list_all2ffdf", methods={"GET"})
     * @return Response
     */
    public function CustomerListe2(CustomerRepository $customerRepository, PaginatorInterface $paginator, UserInterface $user,int $rowpage,int $page,string $raison,string $contact,string $nbdc,string $marque,string $category,string $map): Response 
    {
        try {
            $result=$customerRepository->searchV2($user->getId(), $raison, $contact, $nbdc, $marque, $category, $map);
            if(count($result) >5){
                $result=$customerRepository->searchV2withRow($rowpage,$page,$user->getId(), $raison, $contact, $nbdc, $marque, $category, $map);
            }
            return $this->json($result ,200, [], ['groups' => ['status:lead']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }


     /**
     * @Route("/customers/countclient", name="list_customer", methods={"GET"})
     */
    public function getCount(CustomerRepository $customerRepository,UserInterface $user): Response
    {
        try {
            if(in_array("ROLE_JURISTE",$user->getRoles()))
                $count = $customerRepository->getcount(null);
            else
                $count = $customerRepository->getcount($user->getId());
            return $this->json($count[0][1] ,200, [], []);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }

    }

     /**
     * @Route("/customer/list/all/json", name="get_allcustomjson", methods={"GET"})
     * @return Response
     */
    public function CustomerListeAllJson(CustomerRepository $customerRepository, UserInterface $user): Response
    {
        try {
            if(in_array("ROLE_JURISTE",$user->getRoles())){
                $allCustomers = $customerRepository->getMyAllCustomersansRow(null);
            }
           else{
            $allCustomers = $customerRepository->getMyAllCustomersansRow($user->getId());
           }
            return $this->json($allCustomers ,200, [], ['groups' => ['status:lead']]);
           
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/customer/list/all", name="get_allcustomdhgergfdgdfg", methods={"GET"})
     * @return Response
     */
    public function CustomerListeAll(CustomerRepository $customerRepository, UserInterface $user): Response
    {
        try {
            $allCustomers = $customerRepository->getMyAllRaisonSocialCustomer($user->getId());
            return $this->json($allCustomers ,200, [], ['groups' => ['status:lead']]);
           
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/customer/list/all/mapping", name="CustomerListeAllMapping", methods={"GET"})
     * @return Response
     */
    public function CustomerListeAllMapping(UserInterface $user ,MappingClientRepository $repomapping): Response
    {
        try {
            $allCustomers = $repomapping->getLibelleMapping();
            return $this->json($allCustomers ,200, [], ['groups' => ['status:lead']]);
           
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

     /**
     * @Route("/customer/list/all/category", name="CustomerListeAllCategory", methods={"GET"})
     * @return Response
     */
    public function CustomerListeAllCategory(UserInterface $user ,CategorieClientRepository $repomapping): Response
    {
        try {
            $allCustomers = $repomapping->getLibellecategory();
            return $this->json($allCustomers ,200, [], ['groups' => ['status:lead']]);
           
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }


    /** talou http://localhost:8000/api/customer/list/all */
    /*public function CustomerListe(CustomerRepository $customerRepository, PaginatorInterface $paginator, UserInterface $user): Response
    {
        try {
            $allCustomers = $customerRepository->getMyAllCustomer($user->getId());

            if (count($allCustomers) > 0) {
                $paginateCustomer = $paginator->paginate($allCustomers, 1, count($allCustomers));

                return $this->json($allCustomers, 200, [], ['groups' => ['status:lead']]);
            } else {
                # Si la liste de client est vide
                return $this->json("Vide", 200, [], []);
            }
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }*/

    /**
     * @Route("/customer/for/input/list", name="customer_for_input_list", methods={"GET"})
     * @return Response
     */
    public function ListForClientInput(CustomerRepository $customerRepository, PaginatorInterface $paginator, UserInterface $user): Response
    {
        try {
            $allCustomers = $customerRepository->getMyAllCustomer($user->getId());

            $paginateCustomer = $paginator->paginate($allCustomers, 1, count($allCustomers));

            return $this->json($paginateCustomer, 200, [], ['groups' => ['input']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/customer/{id}", name="customer_id", methods={"GET"})
     * @param int $id
     * @param CustomerRepository $repository
     * @return Response
     */
    public function getCustomerById(int $id, CustomerRepository $repository): Response
    {
        try {
            $tabObject[] = $repository->find($id);
            return $this->json($tabObject, 200, [], ['groups' => ['customer', 'contact-att', 'cat-attr', 'mapp-attr', 'contact-profil-contact']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/list/customers/{page}", name="list_customer_page", methods={"GET"})
     * @param int $page
     * @return Response
     */
    public function getListCustomers(int $page, CustomerRepository $customerRepository, SerializerInterface $serializer, PaginatorInterface $paginator, StatusLeadRepository $statusLeadRepository): Response
    {
        try {

            $allcustomer = $customerRepository->findAllCustomer();

            $paginateCustomer = $paginator->paginate($allcustomer, $page, 5);

            return $this->json([count($allcustomer), $paginateCustomer], 200, [], ['groups' => ['customer', 'contact-att', 'cat-attr', 'mapp-attr', 'contact-profil-contact']]);

        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }

    }

    /**
     * @Route("/contact/by/customer/{id}", name="contact_by_customer", methods={"GET"})
     */
    public function getContactbyCustomer(int $id, CustomerRepository $customerRepository, ContactRepository $contactRepository, SerializerInterface $serializer): Response
    {
        try {
            $getedCustomer = $customerRepository->find($id);
            $getedContact = $contactRepository->findBy(['customer' => $getedCustomer]);

            return $this->json([$getedCustomer->getRaisonSocial(), $getedContact], 200, [], ['groups' => ['contact']]);

        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/bdc/for/one/customer/{id}", name="bdc_for_one_customer", methods={"GET"})
     */
    public function getBdcForOneCutomer(int $id, BdcRepository $bdcRepository): Response
    {
        try {
            $res = $bdcRepository->getBdcForOneCustomer($id);

            return $this->json($res, 200, [], ['groups' => ['bdcs']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/export/data/for/commercial", name="export_data_for_commercial", methods={"GET"})
     */
    public function exportDataForCommerciale(UserInterface $user): Response
    {
        try {
            # On recupère d'abord les données.
            $datas = $this->getBdcToExport($user->getId());

            if (!empty($datas)){
                $spreadsheet = new Spreadsheet();

                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setTitle('Liste client et leurs bdc');

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

                # Stylise les entêtes du fichier
                $sheet->getStyle('A1:P1')->applyFromArray($styleArray);

                # Donner la largeur automatique pour chaque colonne
                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);
                $sheet->getColumnDimension('E')->setAutoSize(true);
                $sheet->getColumnDimension('F')->setAutoSize(true);
                $sheet->getColumnDimension('G')->setAutoSize(true);
                $sheet->getColumnDimension('H')->setAutoSize(true);
                $sheet->getColumnDimension('I')->setAutoSize(true);
                $sheet->getColumnDimension('J')->setAutoSize(true);
                $sheet->getColumnDimension('K')->setAutoSize(true);
                $sheet->getColumnDimension('L')->setAutoSize(true);
                $sheet->getColumnDimension('M')->setAutoSize(true);
                $sheet->getColumnDimension('N')->setAutoSize(true);
                $sheet->getColumnDimension('O')->setAutoSize(true);
                $sheet->getColumnDimension('P')->setAutoSize(true);

                # Tout les colonnés existant
                $sheet->getCell('A1')->setValue('NUMERO CLIENT');
                $sheet->getCell('B1')->setValue('RAISON SOCIALE');
                $sheet->getCell('C1')->setValue('MARQUE COMMERCIALE');
                $sheet->getCell('D1')->setValue('CATEGORIE CLIENT');
                $sheet->getCell('E1')->setValue('MAPPING CLIENT');
                $sheet->getCell('F1')->setValue('NUMERO DU BON DE COMMANDE');
                $sheet->getCell('G1')->setValue('DATE DE CREATION');
                $sheet->getCell('H1')->setValue('PAYS DE PRODUCTION');
                $sheet->getCell('I1')->setValue('DUREE DE TRAITEMENT');
                $sheet->getCell('J1')->setValue('SOCIETE DE FACTURATION');
                $sheet->getCell('K1')->setValue('MARGE CIBLE');
                $sheet->getCell('L1')->setValue('STATUT LEAD');
                $sheet->getCell('M1')->setValue('AVENANT');
                $sheet->getCell('N1')->setValue('CONTRAT');
                $sheet->getCell('O1')->setValue('Registre Traitement RGPD');
                $sheet->getCell('P1')->setValue('ANNEXE RGPD');

                # Insertion des données dans le fichier
                $sheet->fromArray($datas, null, 'A2', true);

                $writer = new Xlsx($spreadsheet);

                # Le nom du fichier à exporter
                $filename = 'client_de_'.strtolower(str_replace(' ', '_', $user->getCurrentUsername())).'.xlsx';

                $writer->save($filename);

                return $this->json($filename, 200, [], ['groups' => ['bdcs']]);
            }
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/export/operation/for/in/prod/bdc", name="export_operation_for_in_prod_bdc", methods={"GET"})
     */
    public function exportOperationForInProdBdc(): Response
    {
        try {
            # On recupère d'abord les données.
            $datas = $this->getClientAndOperationforInProdBdc();

            if (!empty($datas)){
                $spreadsheet = new Spreadsheet();

                $sheet = $spreadsheet->getActiveSheet();

                # Titre dans le ficiher
                $sheet->setTitle('Liste client et operation bdc');

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

                # Stylise les entêtes du fichier
                $sheet->getStyle('A1:D1')->applyFromArray($styleArray);

                # Donner la largeur automatique pour chaque colonne
                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);

                # Tout les colonnés existant
                $sheet->getCell('A1')->setValue('CLIENT');
                $sheet->getCell('B1')->setValue('PAYS');
                $sheet->getCell('C1')->setValue('BUSINNESS UNIT');
                $sheet->getCell('D1')->setValue('OPERATION');

                # Insertion des données dans le fichier
                $sheet->fromArray($datas, null, 'A2', true);

                $writer = new Xlsx($spreadsheet);

                # Le nom du fichier à exporter
                $filename = 'Parcours-clients_client_et_operation_'.date("d-m-Y").'.xlsx';

                $writer->save($filename);

                return $this->json($filename, 200, [], ['groups' => ['get-by-bdc']]);
            }
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/delete/exported/bdc", name="delete_file", methods={"POST"})
     */
    public function deleteExportedFile(Request $request): Response
    {
        try {
            $filename = json_decode($request->getContent(), 'true');

            $file = $this->getParameter('exported_bdc').$filename['filename'];

            $fileManipulate = new FileManipulate();
            $isDeleted = $fileManipulate->deleteFile($file);

            if ($isDeleted) {
                return $this->json("La suppression du fichier a ete effectue avec succes", 201, [], []);
            }

            return $this->json("La suppression non éffectué", 201, [], []);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/teste/injection/client", name="teste_injection_client", methods={"POST"})
     */
    public function testeInjectionClient(Request $request, CrmActuelRepository $crmActuelRepository): Response
    {
        try {
            $data = json_decode($request->getContent(), 'true');

            if (!empty($data)){
                // $res = $crmActuelRepository->injectCustomerInCrmActuel($data);

                return $this->json("Ok", 201, [], []);
            }
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/customer", name="post_customer", methods={"POST"})
     */
    public function createCustomer(Request $request, SerializerInterface $serializer,
                                   UserRepository $userRepository, Lead $lead, CrmActuelRepository $crmActuelRepository): Response
    {
        try {
            $data = json_decode($request->getContent(), 'true');
            $contacts = isset($data['contacts']) ? $data['contacts'] : [];
            $isAdressFactDiff = isset($data['isAdressFactDiff']) ? (int)$data['isAdressFactDiff'] : 0;
            unset($data['contacts']);
            unset($data['isAdressFactDiff']);

            $customer = $serializer->deserialize(json_encode($data), Customer::class, 'json');

            # Ajout client
            $jsonDecode = json_decode($request->getContent(), true);
            $cat = $this->getDoctrine()->getRepository(CategorieClient::class)->find($jsonDecode['categorieClientId']);
            $mapp = $this->getDoctrine()->getRepository(MappingClient::class)->find($jsonDecode['mappingClientId']);

            # $userId = explode($this->getUser()->getSalt())[0];

            $customer->setIsAdressFactDiff($isAdressFactDiff);
            $customer->setCategorieClient($cat);
            $customer->setMappingClient($mapp);
            $customer->setUser($userRepository->find($jsonDecode['userId']));

            # $httpClient = HttpClient::create();

            foreach ($contacts as $contact) {
                $contactDeserialized = $serializer->deserialize(json_encode($contact), Contact::class, 'json');

                $this->setContactForOneCustomer($contactDeserialized, $contact);

                $customer->addContact($contactDeserialized);

                # Injection client et contact dans BDD crm_actuel
                # $this->injectClientAndContactToCrmActuel($data, $contact); // via api
                $crmActuelRepository->injectOrUpdateCustomerInCrmActuel($data, $contact); // via dbal connection
            }

            $this->entityManager->persist($customer);
            $this->entityManager->flush();

            # Get id customer pour pouvoir faire l'ajout fiche qualification ou resumé du lead
            $idCustomer = $customer->getId();

            # Ajout ou MAJ statut client dans la table StatutLead
            $lead->updateStatusLeadByCustomer($customer, $this->getParameter('statut_lead_client_creer'));

            # Ajout d'une ligne dans la table WorkflowLead
            $lead->addWorkflowLead($customer, $this->getParameter('statut_lead_client_creer'));

			# Mettre à jour le numero client
			$customer->setNumClient($idCustomer);
            $this->entityManager->persist($customer);
            $this->entityManager->flush();

            return $this->json($idCustomer, 201, [], ['groups' => ['customer']]);

        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/update-customer/{id}", name="customer_update", methods={"PUT"})
     */
    public function updateCustomer(int $id, Request $request, CustomerRepository $repo,
                                   SerializerInterface $serializer,
                                   UserRepository $userRepository, CrmActuelRepository $crmActuelRepository): Response
    {
        try {
            $customer = $repo->find($id);

            if ($customer) {
                $data = json_decode($request->getContent(), true);
                $contactsArray = isset($data['contacts']) ? $data['contacts'] : [];
                unset($data['contacts']);

                $serializer->deserialize(
                    json_encode($data),
                    Customer::class,
                    'json',
                    [
                        'object_to_populate' => $customer
                    ]
                );

                $customer->setCategorieClient($this->getDoctrine()->getRepository(CategorieClient::class)->find($data['categorieClientId']));

                if (!empty($data['mappingClientId'])) {
                    $customer->setMappingClient($this->getDoctrine()->getRepository(MappingClient::class)->find($data['mappingClientId']));
                }
                $customer->setUser($userRepository->find($data['userId']));

				$customer->setNumClient($id);

                # Traitement des nouveaux contacts et contacts edités
                if (!empty($contactsArray)){
                    list($newContacts, $editedContacts) = $this->filterContactArray($contactsArray);

                    # Mis à jour des contacts
                    if (!empty($editedContacts)){
                        foreach ($editedContacts as $editedContact){
                            # Recupération du contact
                            $contact = $this->getDoctrine()->getRepository(Contact::class)->find($editedContact["id"]);

                            if (!empty($contact)){
                                $editedContact['mailForContactToUpdateOrDelete'] = $contact->getEmail();

                                if (isset($editedContact['contactHasProfilContacts'])) {
                                    unset($editedContact['contactHasProfilContacts']);
                                }

                                $serializer->deserialize(json_encode($editedContact), Contact::class, 'json', ['object_to_populate' => $contact]);

                                # Atribuer une valeur dans la status et customer dans la table contact
                                if (!empty($editedContact["status"])){
                                    $contact->setStatus($editedContact["status"] === true ? 1 : 0);
                                } else {
                                    $contact->setStatus(0);
                                }

                                # Suppression des profils du contact
                                $this->getDoctrine()->getRepository(ContactHasProfilContact::class)->deleteByContactId($editedContact["id"]);

                                # Set profil contact
                                foreach ($editedContact['profilContactIds'] as $profilContactId) {
                                    $contactHasProfilContact = new ContactHasProfilContact();
                                    $contactHasProfilContact->setContact($contact);
                                    $contactHasProfilContact->setProfilContact($this->getDoctrine()->getRepository(ProfilContact::class)->find($profilContactId));
                                    $this->entityManager->persist($contactHasProfilContact);
                                }

                                $this->entityManager->persist($contact);
                                $this->entityManager->flush();

                                # Faire la mis à jour dans crm_actuel
                                $crmActuelRepository->injectOrUpdateCustomerInCrmActuel($data, $editedContact, true);
                            }
                        }
                    }

                    # Creation des nouveaux contacts
                    if (!empty($newContacts)){
                        foreach ($newContacts as $newContact) {
                            $contactDeserialized = $serializer->deserialize(json_encode($newContact), Contact::class, 'json');

                            $this->setContactForOneCustomer($contactDeserialized, $newContact);

                            $customer->addContact($contactDeserialized);

                            # Injection client et contact dans BDD crm_actuel
                            $crmActuelRepository->injectOrUpdateCustomerInCrmActuel($data, $newContact);
                        }
                    }
                }

                $this->entityManager->persist($customer);
                $this->entityManager->flush();

                # Get id customer pour pouvoir faire l'ajout fiche qualification ou resumé du lead
                $customerId = $customer->getId();

                return $this->json($customerId, 200, [], ['groups' => ['update']]);
            } else {
                return $this->json([
                    'message' => 'Item not found.'
                ], 200);
            }
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    
     /**
     * @Route("/get/ref/data/{form}", name="mixedGetRef", methods={"GET"})
     */

    public function getRefData($form, MixedService $mixedService, CoutHoraireRepository $coutHoraireRepository, OperationRepository $operationRepository){
        #Maka Ny Donne Ref rehetra
        $valiny= [];
        if($form == "ficheSociete"){
            $categoryCLient=$mixedService->getAllByEntity("categorieClient");
            $mappingClient=$mixedService->getAllByEntity("MappingClient");
            $profilContact=$mixedService->getAllByEntity("ProfilContact");

            $valiny[]=$categoryCLient;
            $valiny[]=$mappingClient;
            $valiny[]=$profilContact;

        }
        if($form == "ficheQualification" || $form == "bonDeCommande"){
            $PaysProduction=$mixedService->getAllByEntity("PaysProduction");
            $PaysFacturation=$mixedService->getAllByEntity("PaysFacturation");
            // $Operation=$mixedService->getAllByEntity("Operation");
            $FamilleOperation=$mixedService->getAllByEntity("FamilleOperation");
            $Bu=$mixedService->getAllByEntity("Bu");
            $TypeFacturation=$mixedService->getAllByEntity("TypeFacturation");
            $LangueTrt=$mixedService->getAllByEntity("LangueTrt");
            $HoraireProduction=$mixedService->getAllByEntity("HoraireProduction");
            if($form == "ficheQualification"){
                $OriginLead=$mixedService->getAllByEntity("OriginLead");
                $DureeTrt=$mixedService->getAllByEntity("DureeTrt");
                $PotentielTransformation=$mixedService->getAllByEntity("PotentielTransformation");
                // $Operation=$mixedService->getAllByEntity("Operation");
                $valiny[]=$Bu; // 0
                $valiny[]=$DureeTrt; # 1
                $valiny[]=$FamilleOperation; # 2
                $valiny[]=$HoraireProduction; # 3
                $valiny[]=$LangueTrt; # 4
                $valiny[]=[]; # 5
                $valiny[]=$OriginLead; # 6
                $valiny[]=$PaysFacturation; # 7
                $valiny[]=$PaysProduction; # 8
                $valiny[]=$PotentielTransformation; # 9
                $valiny[]=$TypeFacturation; # 10
            }
            if($form == "bonDeCommande"){
                $CoutHoraire=$coutHoraireRepository->findAllDateCurrent();
                $ObjectifQualitatif=$mixedService->getAllByEntity("ObjectifQualitatif");
                $ObjectifQuantitatif=$mixedService->getAllByEntity("ObjectifQuantitatif");
                $valiny[]=$Bu; // 0
                $valiny[]=$CoutHoraire; // 1
                $valiny[]=$FamilleOperation; // 2
                $valiny[]=$HoraireProduction; // 3
                $valiny[]=$LangueTrt; // 4
                $valiny[]=$ObjectifQualitatif; // 5
                $valiny[]=$ObjectifQuantitatif; // 6
                $valiny[]=$PaysFacturation; // 7
                $valiny[]=$PaysProduction; // 8
                $valiny[]=$TypeFacturation; // 9
                $valiny[] = $operationRepository->GetAllOperation(); // 10
            }
        }
        if ($form == "bdcAutres"){
            $CoutHoraire=$coutHoraireRepository->findAllDateCurrent();
            $ObjectifQualitatif=$mixedService->getAllByEntity("ObjectifQualitatif");
            $ObjectifQuantitatif=$mixedService->getAllByEntity("ObjectifQuantitatif");
            $PaysFacturation=$mixedService->getAllByEntity("PaysFacturation");

            $valiny[]=$CoutHoraire; //0
            $valiny[]=$ObjectifQualitatif; //1
            $valiny[]=$ObjectifQuantitatif; //2
            $valiny[]=$PaysFacturation; //3

        }
       
        return $this->json($valiny, 200, [], ['groups' => ['categorie','mapping','profil-contact','ref', 'post:read']]);
     }

    /**
     * @Route("/remove-contact/{id}", name="remove_contact", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function deleteContactAndSocieteInCrmActuel(int $id, CrmActuelRepository $crmActuelRepository): Response
    {
        try {
            $contact = $this->getDoctrine()->getRepository(Contact::class)->find($id);

            if ($contact){
                $isDeleted = $crmActuelRepository->deleteCustomerInCrmActuel($contact->getEmail());

                if ($isDeleted){
                    # Suppression des profils pour ce contact.
                    $profils = $this->getDoctrine()->getRepository(ContactHasProfilContact::class)->findBy([
                        "contact" => $contact
                    ]);

                    if (!empty($profils)){
                        foreach ($profils as $profil){
                            $this->getDoctrine()->getManager()->remove($profil);
                        }
                    }

                    # Suppression du contact.
                    $this->getDoctrine()->getManager()->remove($contact);
                    $this->getDoctrine()->getManager()->flush();

                    return $this->json("Done !", 200, [], []);
                }

                return $this->json("An error exist !", 200, [], []);
            }

            return $this->json("this contact does not exist !", 200, [], []);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route ("/save/customer/document", name="save_customer_doc", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * Import document client.........................
     */
    public function saveCustomerDocument(Request $request, EntityManagerInterface $entityManager): Response {
        try {
            $jsonRecu = json_decode($request->getContent(), true);
            $doc = new ClientDocument();

            if (isset($jsonRecu)) {
                $doc->setDateSignature(isset($jsonRecu['dateSignature']) ? (\DateTime::createFromFormat('Y-m-d', $jsonRecu['dateSignature'])) : null);
                $doc->setDateDebutPriseCompte(isset($jsonRecu['dateDebutPriseCompte']) ? (\DateTime::createFromFormat('Y-m-d', $jsonRecu['dateDebutPriseCompte'])) : null);
                $doc->setDateFinPriseCompte(isset($jsonRecu['dateFinPriseCompte']) ? (\DateTime::createFromFormat('Y-m-d', $jsonRecu['dateFinPriseCompte'])) : null);
                $doc->setTypeDocument($this->getDoctrine()->getRepository(TypeDocument::class)->find($jsonRecu['type'] ?? null));
                $doc->setCustomer($this->getDoctrine()->getRepository(Customer::class)->find($jsonRecu['customer']));

                # Upload file
                $base64service = new CurrentBase64Service();
                $file = $base64service->convertToFile($jsonRecu['name'], $this->getParameter('document_file_dir'), 'DOC_');

                $doc->setName($file);

                $entityManager->persist($doc);
            }

            $entityManager->flush();

            return $this->json($doc, 200, [], ['groups' => ['document']]);
        } catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @Route ("/type/documents", name="type_document", methods={"GET"})
     * @param TypeDocumentRepository $repository
     * @return Response
     * Get tous les données dans la table typeDocument
     */
    public function getTypeDocuments(TypeDocumentRepository $repository): Response {
        try {
            return $this->json($repository->findAll(), 200, [], ['groups' => ['type-doc']]);
        } catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @Route ("/get/contact/in/crm_actuel", name="get_contact_in_crm_actuel", methods={"GET"})
     */
    public function getCustomerInCrm_actuel(UserInterface $user, EntityManagerInterface $entityManager): Response {
        $url = $this->getParameter('get_client_contact_in_crm_actuel_url');
        $ch = curl_init();
        try {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "content-type:application/json;charset=utf-8"
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSLVERSION, 6);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo curl_error($ch);
                die();
            }

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if($http_code == intval(200)){
                $datas = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );

                foreach ($datas as $data) {
                    # Création du contact pour le client en question
                    $contact = new Contact();

                    $contact->setCivilite($data['civilite']);
                    $contact->setNom($data['nom']);
                    $contact->setPrenom($data['prenom']);
                    $contact->setFonction($data['fonction']);
                    $contact->setTel($data['telephone']);
                    $contact->setEmail($data['mail']);
                    $contact->setStatus(0);

                    # Nouveau client
                    $customer = new Customer();

                    $customer->setCategorieClient($this->getDoctrine()->getRepository(CategorieClient::class)->find($this->getValueOfCategorieClient($data['prospect_suspect'])));
                    $customer->setRaisonSocial($data['societe']);
                    $customer->setMarqueCommercial($data['societe']);
                    $customer->setAdresse($data['adresse'] ?? null);
                    $customer->setCp($data['cp'] ?? null);
                    $customer->setVille($data['ville'] ?? null);
                    $customer->setTel($data['tel_standard'] ?? null);
                    $customer->setPays($data['pays'] ?? null);
                    $customer->setUser($this->getDoctrine()->getRepository(User::class)->find($user->getId()));
                    $customer->addContact($contact);

                    $entityManager->persist($contact);
                    $entityManager->persist($customer);
                }

                $entityManager->flush();

                return $this->json("Ajout effectue", 200, [], []);
            } else{
                $er =  "Ressource introuvable : " . $http_code;
                return $this->json($er, 200, [], []);
            }
        } catch (\Throwable $th) {
            throw $th;
        } finally {
            curl_close($ch);
        }
    }

    /**
     * @Route("/export/raison/social/to/align/{percent}", name="export_raison_social_to_align", methods={"POST"})
     */
    public function exportRaisonSocialAlign(int $percent, Request $request, CustomerRepository $customerRepository): Response
    {
        try {
            # Service pour manipulation fichier
            $fileManipulate = new FileManipulate();

            # Import le fichier
            $file = $fileManipulate->uploadFile($this->getParameter('bdc_dir'), $request);

            if (file_exists($file)){
                # Recupère les donées dans la colonne du fichier excel du sage
                $sageRaisonSocialArray = $this->getSageFileDatas($file);

                # Recuperer tout les clients qui existe dans la base de donnée parcours client
                $allCustomers = $customerRepository->findAll();

                if ($allCustomers){
                    # Recupère les données qui ont des correspondances
                    list($datas, $noCorrespondanceRasisonSocials) = $this->getSimilarData($allCustomers, $sageRaisonSocialArray, $percent);

                    $sheetToCreate = new Spreadsheet();

                    $sheet = $sheetToCreate->getActiveSheet();

                    $sheet->setTitle('Alignement des raisons sociales');

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

                    # Stylise les entêtes du fichier
                    $sheet->getStyle('A1:E1')->applyFromArray($styleArray);

                    # Donner la largeur automatique pour chaque colonne
                    $sheet->getColumnDimension('A')->setAutoSize(true);
                    $sheet->getColumnDimension('B')->setAutoSize(true);
                    $sheet->getColumnDimension('C')->setAutoSize(true);
                    $sheet->getColumnDimension('D')->setAutoSize(true);
                    $sheet->getColumnDimension('E')->setAutoSize(true);

                    # Tout les colonnés existant
                    $sheet->getCell('A1')->setValue("ID CLIENT PARCOURS CLIENTS");
                    $sheet->getCell('B1')->setValue("RAISON SOCIALE CLIENT DANS CRM");
                    $sheet->getCell('C1')->setValue("RAISON SOCIALE QUI PEUVENT CORRESPONDRE");
                    $sheet->getCell('D1')->setValue("VALIDATION (Mettre un croix SVP)");
                    $sheet->getCell('E1')->setValue("LISTE DES RAISONS SOCIALES DANS SAGE QUI N'ONT PAS DE CORRESPONDANCE DANS CRM");

                    # Insertion des données dans le fichier
                    $sheet->fromArray($datas, null, 'A2', true);

                    # Fusionne les cellules qui doit être fusionnée
                    $this->mergeCells($datas, $sheet);

                    $columnAContent = "A2:A". (count($datas) + 1);

                    $sheet->getStyle($columnAContent)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                    if ($noCorrespondanceRasisonSocials){
                        /**
                         * Ajouter tout les raisons sociales qui n'ont pas
                         * de correspondance dans la colonne E de l'excel
                         */
                        $this->setValueOfColumnE($sheet, $noCorrespondanceRasisonSocials);
                    }

                    $writer = new Xlsx($sheetToCreate);

                    # Le nom du fichier à exporter
                    $newFile = $this->getParameter('bdc_dir').'raison_social_to_align.xlsx';

                    $writer->save($newFile);

                    # Supprime le fichier importé au paravant
                    $fileManipulate->deleteFile($file);

                    return $this->json("Ok", 200, [], ['groups' => ['status:lead']]);
                }

                return $this->json("No customer found", 200, [], []);
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
     * @Route("/import/raison/social/to/align", name="import_raison_social_to_align", methods={"POST"})
     */
    public function importRaisonSocialToAlign(Request $request): Response
    {
        try {
            # Import le fichier
            $fileManipulate = new FileManipulate();
            $file = $fileManipulate->uploadFile($this->getParameter('bdc_dir'), $request);

            if (file_exists($file)){
                # Recupère les donées dans la colonne du fichier excel du sage
                $spreadsheet = IOFactory::load($file);

                # Rempli les cellules fusionneé
                $sheetData = $fileManipulate->setDataOfMergeCell($spreadsheet);

                if($sheetData){
                    # Met à jour la raison sociale par rapport au raison social selectionné par le validateur
                    $this->updateRaisonSocialeOfParcoursClientCustomer($sheetData);

                    # Supprime le fichier importé au paravant
                    $fileManipulate->deleteFile($file);
                }

                return $this->json("Ok", 200, [], ['groups' => ['status:lead']]);
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
     * @param $sheetData
     * Met à jour la raison sociale par rapport
     * au raison social selectionné par le validateur
     */
    private function updateRaisonSocialeOfParcoursClientCustomer($sheetData): void
    {
        foreach ($sheetData as $row){
            if (!empty($row["D"])){
                if (!empty($row["A"]) && !empty($row["C"])){
                    $sageRaisonSociale = $row["C"];

                    $nbCaractere = strlen($sageRaisonSociale);

                    if ($sageRaisonSociale[$nbCaractere - 1] == " ") {
                        # Supprimer la derniere espace à la fin du chaine de caractère
                        $sageRaisonSociale = substr($sageRaisonSociale, 0, -1);
                    }

                    $customer = $this->getDoctrine()->getRepository(Customer::class)->find($row["A"]);

                    $customer->setRaisonSocial($sageRaisonSociale);

                    $this->entityManager->persist($customer);
                }
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @param $datas
     * @param $sheet
     * Fusionne les cellules
     */
    private function mergeCells($datas, $sheet): void
    {
        $ecart = 0;

        # Contient les rangers à fusionner
        $rangeToMerge = [];

        /**
         * Correspond au nombre d'ecart entre l'index et le numero de ligne dans l'excel
         */
        $gap = 2;

        $nbRowMax = count($datas) - $gap;

        foreach ($datas as $index => $row){
            $id = $row[0]; # Id du customer (colonne A)

            $nextIndex = $index < $nbRowMax ? ($index + 1) : $index;

            # Contient la valeur de l'id du ligne suivant dans l'excel
            $idForNextRow = $datas[$nextIndex][0];

            if ($idForNextRow == $id){
                # Pour les lignes à fusionner
                $ecart += 1;
            } else {
                # Pour les lignes à n'est pas fusionner
                $firstMergeRow = ($index - $ecart) + $gap;
                $lastMergeRow = $index < $nbRowMax ? ($index + $gap) : $index;


                if ($ecart > 0){
                    $columnA = "A" . $firstMergeRow .":A". $lastMergeRow;
                    $columnB = "B" . $firstMergeRow .":B". $lastMergeRow;

                    $rangeToMerge[] = [$columnA, $columnB];
                } else {
                    # Donner une valeur par defaut (x) au colonne validation
                    $defaultRow = $index + 2;
                    $sheet->setCellValue("D$defaultRow", "x");
                }

                $ecart = 0;
            }
        }

        if ($rangeToMerge){
            foreach ($rangeToMerge as $merge){
                # Fusionne les cellules dans la colonne A (id customer)
                $sheet->mergeCells($merge[0]);

                # Fusionne les cellules dans la colonne B (Raison sociale)
                $sheet->mergeCells($merge[1]);

                # Decoupe chaque range (Ex: "A21:A32" en "A21" et "A32")
                $decoupeRangeA = explode(":", $merge[0]);
                $decoupeRangeB = explode(":", $merge[1]);

                # Metre l'alignement verticel en center
                $sheet->getStyle($decoupeRangeA[0])->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->getStyle($decoupeRangeB[0])->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            }
        }
    }

    /**
     * @param string $referenceText
     * @param string $text
     * @return float
     */
    private function similarityText(string $referenceText, string $text): float
    {
        # Mis en minuscule et suppression des espaces dans le texte
        $ref = strtolower(str_replace(' ', '', $referenceText));
        $txt = strtolower(str_replace(' ', '', $text));

        $nb = strlen($ref);

        $similar = 0;

        for ($i=0; $i < $nb; $i++) {
            if (isset($txt[$i])) {
                if ($ref[$i] == $txt[$i]) {
                    $similar += 1;
                };
            }
        }

        if ($similar > 0){
            return round((($similar / $nb) * 100), 2);
        }

        return 0;
    }

    /**
     * @param $file
     * @return array[]
     * To read the excel sage file
     */
    private function getSageFileDatas($file): array
    {
        $sageRaisonSocialArray = [];

        # Lire le fichier
        $sheetToRead = IOFactory::load($file);

        $nbOnglet = $sheetToRead->getSheetCount();

        for ($i = 0; $i < $nbOnglet; $i++){
            # Supprime le premier ligne
            $sheetToRead->getSheet($i)->removeRow(1);

            $ongletDatas = $sheetToRead->getSheet($i)->toArray();

            foreach ($ongletDatas as $societe){
                if ($societe[1]){
                    if (!in_array($societe[1], $sageRaisonSocialArray)){
                        $sageRaisonSocialArray[] = $societe[1];
                    }
                }
            }
        }

        return $sageRaisonSocialArray;
    }

    /**
     * @param $allCustomers
     * @param $sageRaisonSocialArray
     * @return array
     * Recupère les données qui ont des correspondance
     */
    private function getSimilarData($allCustomers, $sageRaisonSocialArray, int $percent = 60): array
    {
        $datas = [];
        $noCorrespondanceRasisonSocials = [];

        $tmpSageRs = [];

        foreach ($allCustomers as $customer){
            foreach ($sageRaisonSocialArray as $societe) {
                $res = $this->similarityText($customer->getRaisonSocial(), $societe);

                if ($res >= $percent){
                    $societeClean = strtolower(str_replace(' ', '', $societe));
                    if (!in_array($societeClean, $tmpSageRs)){
                        $datas[] = [
                            $customer->getId(),
                            $customer->getRaisonSocial(),
                            $societe,
                            "",
                            "",
                        ];

                        $tmpSageRs[] = $societeClean;
                    }
                } else {
                    !in_array($societe, $noCorrespondanceRasisonSocials) && $noCorrespondanceRasisonSocials[] = $societe;
                }
            }
        }

        return [$datas, $noCorrespondanceRasisonSocials];
    }

    /**
     * @param $sheet
     * @param $noCorrespondanceRasisonSocials
     * Ajouter tout les raisons sociales qui n'ont pas
     * de correspondance dans la colonne E de l'excel
     */
    private function setValueOfColumnE($sheet, $noCorrespondanceRasisonSocials): void
    {
        $noCorresNb = count($noCorrespondanceRasisonSocials);

        for ($i=0; $i < $noCorresNb; $i++){
            # Cellule E2 à En
            $cell = "E". ($i+2);
            $sheet->setCellValue($cell, $noCorrespondanceRasisonSocials[$i]);
        }
    }

    private function getValueOfCategorieClient($prospectSuspect) {
        $text = strtolower($prospectSuspect);
        switch ($text)
        {
            case 'prospect':
                return 1;
                break;
            case 'client':
                return 2;
                break;
            case 'client perdu':
                return 3;
                break;
            default:
                return 3;
        }
    }

    /**
     * @Route("/download/document/{name}", name="download_doc", methods={"GET"})
     */
    public function dowloadDocument(string $name): Response
    {
        try {
            return $this->file('https://madacontact.com/parcours_client/bdc/DOC_6214dbd5a45287.12590893.pdf', 'myPdf.pdf');
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/remove-customer/{id}", name="customer_remove", methods={"DELETE"})
     */
    public function deleteCustomer(int $id, CustomerRepository $repo,
                                   EntityManagerInterface $em): Response
    {
        try {
            $customer = $repo->find($id);
            $em->remove($customer);
            $em->flush();

            return $this->json(["status" => 200, "message" => "resource deleted successfully !"], 200);

        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @param $contactDeserialized
     * @param $contact
     */
    public function setContactForOneCustomer($contactDeserialized, $contactFront){
        if (isset($contactFront['contactHasProfilContacts'])) {
            unset($contactFront['contactHasProfilContacts']);
        }

        foreach ($contactFront['profilContactIds'] as $profilContactId) {
            $contactHasProfilContact = new ContactHasProfilContact();
            $contactHasProfilContact->setContact($contactDeserialized);
            $contactHasProfilContact->setProfilContact($this->getDoctrine()->getRepository(ProfilContact::class)->find($profilContactId));
            $this->entityManager->persist($contactHasProfilContact);
        }

        # Atribuer une valeur dans la status et customer dans la table contact
        if (!empty($contactFront["status"])){
            $contactDeserialized->setStatus($contactFront["status"] === true ? 1 : 0);
        } else {
            $contactDeserialized->setStatus(0);
        }
    }

    /**
     * @param int $userId
     * @return array
     * fonction qui retourne les données nécéssaire au fichier à exporter
     */
    private function getBdcToExport(int $userId): array
    {
        $data = [];

        # On recupère les bdcs à exporter
        $customers = $this->getDoctrine()->getRepository(Customer::class)->getMyAllCustomer($userId);

        # On extraire les données qui doit être present dans le fichier
        if(count($customers) > 0) {
            foreach ($customers as $customer) {
                if (count($customer->getResumeLeads()) > 0) {
                    foreach ($customer->getResumeLeads() as $resumeLead) {
                        foreach ($resumeLead->getBdcs() as $bdc) {
                            $data[] = [
                                $customer->getId(),
                                $customer->getRaisonSocial(),
                                $customer->getMarqueCommercial(),
                                $customer->getCategorieClient()->getLibelle(),
                                $customer->getMappingClient()->getLibelle(),
                                $bdc->getNumBdc() ? $bdc->getNumBdc() : "",
                                $bdc->getDateCreate(),
                                $bdc->getPaysProduction()->getLibelle(),
                                $resumeLead->getDureeTrt() ? $resumeLead->getDureeTrt()->getLibelle() : "",
                                $bdc->getSocieteFacturation() ? $bdc->getSocieteFacturation()->getLibelle() : "",
                                $bdc->getMargeCible() ? (round($bdc->getMargeCible() * 100).' %') : "",
                                $bdc->getStatutLead() ? $this->statusLeadValue($bdc->getStatutLead()) : "",
                                $this->getAvenantValueToExcelColumn($bdc->getStatutLead()),
                                '"Dev à faire"',
                                '"Dev à faire"',
                                'Oui'
                            ];
                        }
                    }
                } else {
                    $data[] = [
                        $customer->getId(),
                        $customer->getRaisonSocial(),
                        $customer->getMarqueCommercial(),
                        $customer->getCategorieClient()->getLibelle(),
                        $customer->getMappingClient() ? $customer->getMappingClient()->getLibelle() : "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        '"Dev à faire"',
                        '"Dev à faire"',
                        'Oui'
                    ];
                }
            }
            return $data;
        }
    }

    /**
     * @return array
     * fonction qui retourne le client, pays, bu, operation des bdcs en production
     */
    private function getClientAndOperationforInProdBdc(): array
    {
        $datas = [];
        $idMere = [];

        # On recupère tous les bdcs en production
        $bdcs = $this->getDoctrine()->getRepository(Bdc::class)->findAllBdcEnProduction();

        # On prend juste les bdcs qui a la dernière version pour le cas avenant
        foreach ($bdcs as $bdc){
            if (!in_array($bdc->getIdMere(), $idMere)){
                $idMere[] = $bdc->getIdMere();
                $datas[] = $bdc;
            }
        }

        $finalData = [];

        # On extraire le client, pays, bu, operation pour chaque bdc recupéré
        if(!empty($datas)) {
            foreach ($datas as $bdc) {
                $client = $bdc->getResumeLead()->getCustomer()->getRaisonSocial();
                $pays = $bdc->getPaysProduction()->getLibelle();
                if (!empty($bdc->getBdcOperations())) {
                    foreach ($bdc->getBdcOperations() as $ligneFact) {
                        if ($ligneFact->getIrm() == 1){
                            $finalData[] = [
                                $client,
                                $pays,
                                $ligneFact->getBu() ? $ligneFact->getBu()->getLibelle() : "",
                                $ligneFact->getOperation() ? $ligneFact->getOperation()->getLibelle() : ""
                            ];
                        }
                    }
                }
            }
            return $finalData;
        }
    }

    /**
     * @param $arrayContactFront
     * @return array[]
     */
    private function filterContactArray($arrayContactFront): array
    {
        $newContacts = [];
        $editedContacts = [];

        foreach ($arrayContactFront as $arrayContact){
            if (!empty($arrayContact["isNewContact"]) && $arrayContact["isNewContact"] == 1){
                $newContacts[] = $arrayContact;
            }

            if (!empty($arrayContact["isEditedContact"]) && $arrayContact["isEditedContact"] == 1){
                $editedContacts[] = $arrayContact;
            }
        }

        return [$newContacts, $editedContacts];
    }

    private function getAvenantValueToExcelColumn($statutLead): string
    {
        $avenantStates = [12, 13, 14, 15, 16, 17, 18, 19, 20];
        if (in_array($statutLead, $avenantStates)) {
            return "Oui";
        } else {
            return "Non";
        }
    }

    private function injectClientAndContactToCrmActuel($data, $contact)
    {
        $dataPoste = [
            'societe' => $data['raisonSocial'],
            'adresse' => $data['adresse'],
            'cp' => $data['cp'],
            'tel_standard' => $data['tel'],
            'civilite' => $contact['civilite'],
            'prenom' => $contact['prenom'],
            'nom' => $contact['nom'],
            'fonction' => $contact['fonction'],
            'telephone' => $contact['tel'],
            'mail' => $contact['email'],
            'skype' => $contact['skype']
        ];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_SSLVERSION, 6);
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->getParameter('inject_client_contact_to_crm_actuel_url'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            // CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($dataPoste),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
        ]);

        curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            var_dump("Erreur curl:" . $err);
            return $this->json("Non ok", 500, [], []);
        } else {
            return $this->json("Ok", 200, [], []);
        }
    }

    private function statusLeadValue($statutLead): string
    {
        switch ($statutLead)
        {
            case -1:
                return 'BDC créé en brouillon';
                break;
            case 1:
                return 'Client créé';
                break;
            case 2:
                return 'Fiche qualification créé';
                break;
            case 3:
                return 'A valider par Dir Prod';
                break;
            case 4:
                return 'A valider par le Ser. Fin.';
                break;
            case 5:
                return 'A modifier suite au rejet Dir Prod';
                break;
            case 6:
                return 'A valider par le DG';
                break;
            case 7:
                return 'A modifier suite au rejet Ser. Fin.';
                break;
            case 8:
                return 'A signer par le commercial';
                break;
            case 9:
                return 'A modifier suite au rejet du DG';
                break;
            case 10:
                return 'Signé par le commercial';
                break;
            case 11:
                return 'En production';
                break;
            case 12:
                return 'Avenant à valider par Dir. Prod.';
                break;
            case 13:
                return 'Avenant à valider par Dir. Fin.';
                break;
            case 14:
                return 'Avenant rejeté par Dir. Prod.';
                break;
            case 15:
                return 'Avenant à valider par DG';
                break;
            case 16:
                return 'Avenant rejeté par Dir. Fin';
                break;
            case 17:
                return 'A Signer par le commercial';
                break;
            case 18:
                return 'Avenant rejeté par DG';
                break;
            case 19:
                return 'Avenant signé par le commercial';
                break;
            case 20:
                return 'Avenant signé par le Client';
                break;
            case 21:
                return 'Bon de commande perdu';
                break;
            case 22:
                return 'A valider par le Dir. Prod.';
                break;
            default:
                return 'Fiche qualification créé';
        }
    }
}
