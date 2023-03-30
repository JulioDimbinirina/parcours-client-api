<?php

namespace App\Controller;

use App\Entity\BdcDocument;
use App\Entity\Bu;
use App\Entity\CategorieClient;
use App\Entity\ClientDocument;
use App\Entity\Customer;
use App\Entity\DureeTrt;
use App\Entity\FamilleOperation;
use App\Entity\HoraireProduction;
use App\Entity\IndicatorQualitatif;
use App\Entity\IndicatorQuantitatif;
use App\Entity\LangueTrt;
use App\Entity\LeadDetailOperation;
use App\Entity\Operation;
use App\Entity\OriginLead;
use App\Entity\PaysFacturation;
use App\Entity\PaysProduction;
use App\Entity\PotentielTransformation;
use App\Entity\RejectBdc;
use App\Entity\ResumeLead;
use App\Entity\Bdc;
use App\Entity\BdcOperation;
use App\Entity\StatutClient;
use App\Entity\SuiteProcess;
use App\Entity\Tarif;
use App\Entity\HausseIndiceSyntecClient;
use App\Entity\HauseIndiceLignefacturation;
use App\Entity\TypeDocument;
use App\Entity\TypeFacturation;
use App\Entity\CoutHoraire;
use App\Entity\SocieteFacturation;
use App\Entity\Devise;
use App\Entity\Tva;
use App\Entity\ObjectifQuantitatif;
use App\Entity\ObjectifQualitatif;
use App\Entity\WorkflowLead;
use App\Entity\User;
use App\Repository\BdcDocumentRepository;
use App\Repository\ContactRepository;
use App\Repository\CustomerRepository;
use App\Repository\BdcRepository;
use App\Repository\DureeTrtRepository;
use App\Repository\IndicatorQualitatifRepository;
use App\Repository\IndicatorQuantitatifRepository;
use App\Repository\HauseIndiceLignefacturationRepository;
use App\Repository\OperationRepository;
use App\Repository\OriginLeadRepository;
use App\Repository\PotentielTransformationRepository;
use App\Repository\CoutHoraireRepository;
use App\Repository\BdcOperationRepository;
use App\Repository\ContratRepository;
use App\Repository\PaysProductionRepository;
use App\Repository\PaysFacturationRepository;
use App\Repository\RejectBdcRepository;
use App\Repository\ResumeLeadRepository;
use App\Repository\StatusLeadRepository;
use App\Repository\UserRepository;
use App\Repository\HausseIndiceSyntecClientRepository;
use App\Service\CurrentBase64Service;
use App\Service\FilterLigneFacturation;
use App\Service\Lead;
use App\Service\SendMailTo;
use App\Service\CaMensuel;
use App\Service\bdcService;
use App\Service\ServiceForSignature;
use App\Services\Base64Service;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use PhpParser\Node\Stmt\TryCatch;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\DateTime;
use function Composer\Autoload\includeFile;

/**
 * @Route("/api")
 */
class BonDeCommandeController extends AbstractController
{
    private $entityManager;

    private $paginator;

    public function __construct(EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        $this->entityManager = $entityManager;

        $this->paginator = $paginator;
    }
     /**
     * @Route("/bdc/get/texte/byIdMere/{idMere}/{langue}", name="getTexteByIdMere" ,methods={"GET"})
     */

     public function getTexteByIdMere($idMere,$langue,BdcRepository $bdcRepository,HausseIndiceSyntecClientRepository $RepoHausseClient,ContratRepository $repoContart){
        $arrayBdc = $bdcRepository->getBdcByIdMere($idMere);

        # BDC Farany
        $bdc = end($arrayBdc);
        $customer = $bdc->getResumeLead()->getCustomer();
        
        $isHasContrat = $repoContart->findOneBy(['idCustomer'=> $customer->getId()]);
        $Titre = "Avenant au Devis";
            
        if($isHasContrat){
            $Titre = "Avenant au contrat";
        }
        $HausseClient = $RepoHausseClient->findOneBy([
            "id_customer" => $customer->getId()
        ]);
        $dateContratHausse = "";
        if ($HausseClient != null){
            if ($HausseClient->getDateContrat() != null){
                $dateContratHausse=$HausseClient->getDateContrat()->format('d-m-Y H:i:s');
            }
        }
        $dateSignatureBdc = "";
        if($bdc != null){
            if($bdc->getDateSignature() != null){
                $dateSignatureBdc =$bdc->getDateSignature()->format('d-m-Y H:i:s');
            }
        }
        $hausseIsType= ["Syntec","Smic","IHCT-TS"];
        if($HausseClient->getIsType()== 1 || $HausseClient->getIsType()== 2){
            $IsType=$hausseIsType[$HausseClient->getIsType()-1];
        }
        else{
            $IsType=$hausseIsType[2];
        }
        $TexteAncien =  [
            ", $Titre ,",
            ",Entre les soussignées :,",
            ",La société [1;".str_replace(" ","_",$bdc->getSocieteFacturation()->getLibelle())."] , au capital social de [2;".str_replace(" ","_",$bdc->getSocieteFacturation()->getCapital())."] , immatriculée au Registre du Commerce et des Sociétés de [3;". $bdc->getSocieteFacturation()->getIdentifiantFiscal()."] , sous le numéro [4;".$bdc->getSocieteFacturation()->getIdentifiantFiscal()."] , dont le siège social est situé au [5;".str_replace(" ","_",$bdc->getSocieteFacturation()->getAdresse()) ."] , représentée par [6;".str_replace(" ","_",$customer->getUser()->getCurrentUsername())."] , en sa qualité de [7;commercial] , dûment habilité à l’effet des présentes,,",
            ",Ci-après dénommée le 'Prestataire', d une part,,",
            ",La société [8;".str_replace(" ","_",$customer->getRaisonSocial())."] ,au capital social de [10;159900] , immatriculée au Registre du Commerce et des Sociétés de [11;] , sous le numéro [12;] , dont le siège social est situé au [13;".str_replace(" ","_",$customer->getPays())."] , représentée par [14;".str_replace(" ","_",$customer->getContacts()[0]->getNom() ." ". $customer->getContacts()[0]->getPrenom())."] ,en sa qualité de [15;".$customer->getCategorieClient()->getLibelle()."] , dûment habilité à l’effet des présentes,",
            ",Le Prestataire et le Client étant individuellement désignés par une/la « Partie » et conjointement par les « Parties ». ,",
            ",Les Parties ont signé en date du , $dateSignatureBdc , un bon de commande avec les conditions générales de vente de [1;".str_replace(" ","_",$bdc->getSocieteFacturation()->getLibelle())."] , suivant lequel le client met à disposition du
            Client une équipe dédiée composée de Développeurs Web confirmés (le « [20;$IsType] initial ») .",
            "Conformément à la section « Prix » des Conditions Générales de Vente, le montant des Prestations sera révisé chaque année suivant l’indice SYNTEC à la date d’anniversaire du contrat.",
            "Les Parties se sont donc rapprochées afin de définir dans le présent contrat (ci-après (le « [20;$IsType] »)  les termes de leur accord.",
            "CECI ETANT EXPOSE, IL A ETE CONVENU CE QUI SUIT :",
            "Article 1 : Objet de l’Avenant.",
            "Le présent avenant a pour objet de mettre à jour la tarification des prestations conformément aux termes des Conditions Générales de Vente. Toutes les dispositions contractuelles non expressément modifiées par les présentes demeurent en vigueur. En cas de contradiction avec celles des présentes, ces dernières prévaudront.  "
            ,"Article 2 : Modification de la section  « Tarification de Production » du Bon de Commande"
            ," Par application de la formule suivante :P1 = P0 x (S1/S0)"
            ," Où" ,"P1 = Prix révisé" ,"P0 = Prix d’origine" 
            ,"S0 = Indice SYNTEC de référence retenue à la date contractuelle d’origine = ". $dateContratHausse ." : [21;". $HausseClient->getInitial()."]"
            ,"S1 = Indice du SYNTEC publié à la date de la révision ". $dateContratHausse ." : [22;". $HausseClient->getActuel()."]"
            ,"La tarification des Prestations applicable à compter du ".$HausseClient->getDateAplicatif()." sera comme suit :"
            ,"Article 3 : Entrée en vigueur"
            ,"Le présent avenant entre en vigueur à compter du 1erjuillet 2022"
            ,"Fait à Evreux, le [50;], en deux (02) exemplaires originaux. "
            ," [23;".str_replace(" ","_",$customer->getUser()->getCurrentUsername())."] ",
            " [24;".$customer->getContacts()[0]->getNom()."_".$customer->getContacts()[0]->getPrenom()."] "
        ];
        return $this->json($TexteAncien, 200, [], []);
     }

     /**
     * @Route("/bdc/save/pdf/mopifier/{idCust}", name="savePdfModifier" ,methods={"POST"})
     */
    public function savePdfModifier($idCust,Request $request,CustomerRepository $repoCust,BdcRepository $bdcRepository,HausseIndiceSyntecClientRepository $repoHausseClient,ContratRepository $repoContart){
        $DataRequets = json_decode($request->getContent(), true);
        $customer = $repoCust->find($idCust);
        $isHasContrat = $repoContart->findOneBy(['idCustomer'=> $customer->getId()]);
        $titre = "Avenant au Devis";
            
        if($isHasContrat){
            $titre = "Avenant au contrat";
        }
        $res = $bdcRepository->getBdcForHausseUpdate($idCust);
        $bdcParIdmere = [];
        foreach($res as $bdc){
            $bdcParIdmere[$bdc->getIdMere()]=$bdc;
        }
        $dateSignature = end($bdcParIdmere)->getDateSignature();
        $hausseClient = $repoHausseClient->findOneBy([
            "id_customer" => $customer->getId()
        ]);

        # BdcParIdMere Bdc Farany ao amin'ny IDMere
        $bdcOperationParIdMere = [];
        
        foreach($bdcParIdmere as $bdc){
            $tempOperationHno = "tsisy";
            foreach ($bdc->getBdcOperations() as $key => $ligneDeFacturation) {
                if($ligneDeFacturation->getOperation()->getId() !== 1007 && $ligneDeFacturation->getOperation()->getId() !== 13 && $ligneDeFacturation->getOperation()->getId() !== 15 && $ligneDeFacturation->getOperation()->getId() !== 1004){
                    if ($ligneDeFacturation->getTypeFacturation()->getId() == 7){
                        $bdcOperationParIdMere[$bdc->getIdMere()][$ligneDeFacturation->getOperation()->getLibelle()]=$ligneDeFacturation;
                    }else{
                        if($tempOperationHno != $ligneDeFacturation->getOperation()->getLibelle())
                            $bdcOperationParIdMere[$bdc->getIdMere()][$ligneDeFacturation->getOperation()->getLibelle()]=$ligneDeFacturation;
                    }
                    $tempOperationHno = $ligneDeFacturation->getOperation()->getLibelle();
                }
                    
            }
        }
        $html = $this->renderView('ContratModifierV2.html.twig', [
            'titre' => strtoupper($titre),
            'byFront' => $DataRequets,
            'DateSignature' => $dateSignature,
            'hausseClient' => $hausseClient,
            'ligneFacturation' => $bdcOperationParIdMere
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
        file_put_contents($this->getParameter('bdc_dir') .$customer->getRaisonSocial(). ' Avenant Revision taifaire.pdf', $output);
        $html = $this->renderView('ContratModifierUKV2.html.twig', [
            'titre' => $titre,
            'byFront' => $DataRequets,
            'DateSignature' => $dateSignature,
            'hausseClient' => $hausseClient,
            'ligneFacturation' => $bdcOperationParIdMere
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
         file_put_contents($this->getParameter('bdc_dir') .$customer->getRaisonSocial(). ' Avenant Revision taifaireUK.pdf', $output);
        return $this->json("Creation OK", 200, [], []);
    }

    /**
     * @Route("/get/idbdcMere/by/idBdcOP/{idBdcO}", name="idbdcM_getIdBdcOP" ,methods={"GET"})
     */
    public function idbdcM_getIdBdcOP(int $idBdcO,BdcRepository $bdcRepository,BdcOperationRepository $bdcOpRepo):Response{
        try{
            $res=$bdcOpRepo->getBdcOByidBdcO($idBdcO);
            $resultat=$bdcRepository->findOneBy(['id' => $idBdcO]);
            return $this->json($res[0]->getBdc()->getIdMere());
        }catch(\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/get/idbdc/via/id/{id}", name="idbdc_getId" ,methods={"GET"})
     */
    public function getIdBdcId(int $id,BdcRepository $bdcRepository):Response{
        try{
            $result=$bdcRepository->findBy([
                "id" => $id
            ]);
            $idres=0;
            foreach ($result as $r){
                $idres=$r->getNumBdc();
            }
            return $this->json($idres);
        }
        catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/get/bdc/via/idmere/{idMere}", name="bdc_getIdMere" ,methods={"GET"})
     */
    public function getBdcByIdMere(int $idMere,BdcRepository $bdcRepository):Response
    {
        try{
            $result=$bdcRepository->findBy([
                "idMere" => $idMere
            ]);
            $result1=$bdcRepository->findBy([
                "id" => $idMere
            ]);
            $valiny= $bdcRepository->ComparaisonBdc($result,$result1);
            $i=0;
            $res= array();
            $tmp=array();
            foreach ($result as $r){
                if($r->getNumVersion()){
                    $tmp=explode("_",$r->getNumVersion());
                    foreach ($result1 as $pp){
                        $tmp[3]=$pp->getNumBdc();
                    }
                    $res[$i]=$tmp;
                    $i++;
                }
            }
            return $this->json( $res, 200, [], ['groups' => ['get-by-bdc']]);
        }
        catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }
    /**
     * @Route("/get/devis/modif/histo/{idMere}", name="devis_modifHistoIdMere" ,methods={"GET"})
     */
    public function bdcmodifhisto(int $idMere,BdcRepository $bdcRepository,BdcOperationRepository $bdcOperationRepository):Response
    {
        try{
            $result=$bdcRepository->findBy([
                "idMere" => $idMere
            ]);

            $bigVersion=0;
            foreach ($result as $r){
                if($bdcRepository->getVersionBdc($r)>$bigVersion)
                    $bigVersion=$bdcRepository->getVersionBdc($r);
            }

            $tableaustring=array();
            for($i=1;$i<$bigVersion;$i++){
                $bdca=$bdcRepository->getBdcByVersion($i,$result);
                $bdcb=$bdcRepository->getBdcByVersion($i+1,$result);
                $date=$bdcb->getDateModification();
                $result2=$bdcOperationRepository->findBy([
                    "bdc" => $bdca->getId()
                ]);

                $result3=$bdcOperationRepository->findBy([
                    "bdc" => $bdcb->getId()
                ]);

                $fotoana=date_format($date,"d/m/Y H:i");

                //Mety eto
                $tabmety2=array();
                $i2mety=0;
                $i3mety=0;

                foreach($result2 as $r){
                    $pp=$r->getOperation()->getId();
                    if(!isset($tabmety2[intval("$pp")]))
                        $tabmety2[intval("$pp")]=$r;
                    $i2mety++;
                }

                $tabmety3=array();
                $test=0;
                foreach($result3 as $r){
                    if($r->getFamilleOperation()){
                        $test++;
                        if($test>1){
                            $op=$r->getOperation()->getLibelle();
                            $tableaustring["$test Ajout"]="Le $fotoana, Ajout Opération $op";
                        }
                    }
                    $pp=$r->getOperation()->getId();
                    if(!isset($tabmety3[intval("$pp")]))
                        $tabmety3["$pp"]=$r;
                    $i3mety++;
                }

                # Recupère la devise
                $devise = "";
                switch ($bdca->getPaysFacturation()->getId()){
                    case 1:
                        $devise = "Euro";
                        break;
                    case 2:
                        $devise = "Dirham";
                        break;
                    case 3:
                        $devise = "Mga";
                        break;
                    case 4:
                        $devise = "Cfa";
                        break;
                }

                if($i3mety>$i2mety){

                    foreach ($tabmety3 as $key=>$ra){
                        $typaFacturation2 = $ra->getTypeFacturation()->getId();
                        $temp=$tabmety3[$key]->getOperation()->getLibelle();

                        if(isset($tabmety2[$key])){
                            if ($typaFacturation2 == $this->getParameter('param_id_type_fact_mixte')){
                                # Pour acte
                                if($tabmety2[$key]->getPrixUnitaireActe() != $tabmety3[$key]->getPrixUnitaireActe()){
                                    $prix1=$tabmety2[$key]->getPrixUnitaireActe();
                                    $prix2=$tabmety3[$key]->getPrixUnitaireActe();
                                    $tableaustring[]= "Le $fotoana, Modification tarif à l'acte de l'operation $temp de $prix1 $devise à $prix2 $devise";
                                }

                                # Pour heure
                                if($tabmety2[$key]->getPrixUnitaireHeure() != $tabmety3[$key]->getPrixUnitaireHeure()){
                                    $prix1=$tabmety2[$key]->getPrixUnitaireHeure();
                                    $prix2=$tabmety3[$key]->getPrixUnitaireHeure();
                                    $tableaustring[]= "Le $fotoana, Modification tarif à l'heure de l'operation $temp de $prix1 $devise à $prix2 $devise";
                                }
                            } else {
                                if($tabmety2[$key]->getPrixUnit() != $tabmety3[$key]->getPrixUnit()){
                                    $prix1=$tabmety2[$key]->getPrixUnit();
                                    $prix2=$tabmety3[$key]->getPrixUnit();
                                    $texte="Le $fotoana, Modification tarif $temp Tarif unitaire $prix1 $devise à $prix2 $devise";
                                }
                            }
                        }
                    }
                }
                else{
                    foreach ($tabmety2 as $key=>$ra){
                        $typaFacturation2 = $ra->getTypeFacturation()->getId();

                        $temp=$tabmety2[$key]->getOperation()->getLibelle();

                        if(isset($tabmety3[$key])){
                            if ($typaFacturation2 == $this->getParameter('param_id_type_fact_mixte')){
                                # Pour acte
                                if($tabmety2[$key]->getPrixUnitaireActe() != $tabmety3[$key]->getPrixUnitaireActe()){
                                    $prix1=$tabmety2[$key]->getPrixUnitaireActe();
                                    $prix2=$tabmety3[$key]->getPrixUnitaireActe();
                                    $tableaustring[]= "Le $fotoana, Modification tarif à l'acte de l'operation $temp de $prix1 $devise à $prix2 $devise";
                                }

                                # Pour heure
                                if($tabmety2[$key]->getPrixUnitaireHeure() != $tabmety3[$key]->getPrixUnitaireHeure()){
                                    $prix1=$tabmety2[$key]->getPrixUnitaireHeure();
                                    $prix2=$tabmety3[$key]->getPrixUnitaireHeure();
                                    $tableaustring[]= "Le $fotoana, Modification tarif à l'heure de l'operation $temp de $prix1 $devise à $prix2 $devise";
                                }
                            } else {
                                if($tabmety2[$key]->getPrixUnit() != $tabmety3[$key]->getPrixUnit()){
                                    $prix1=$tabmety2[$key]->getPrixUnit();
                                    $prix2=$tabmety3[$key]->getPrixUnit();
                                    $tableaustring[]= "Le $fotoana, Modification tarif $temp Tarif unitaire $prix1 $devise à $prix2 $devise";
                                }
                            }
                        }
                    }
                }


            }

            $solution=array();
            foreach ($tableaustring as $t){
                $solution[]=$t;
            }
            if(count($solution)==0){
                $solution[]="Il n'y a pas d'historique pour ce devis";
            }
            rsort($solution);
            return $this->json( $solution ,200, []);
        }
        catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/bon/de/commande/list", name="bdc_list", methods={"POST"})
     */
    public function bdcList(BdcRepository $bdcRepository, UserInterface $user, CaMensuel $caMensuel, Request $request): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), 'true');

            # Recuperation des bons de commande
            list($total, $bdcs) = $bdcRepository->getBdcList($user, $dataFront);

            if ($total > 0) {
                if (!empty($dataFront["statutLead"])){
                    foreach ($bdcs as $index => $bdc){
                        if (!in_array($bdc->getStatutLead(), $dataFront["statutLead"])){
                            unset($bdcs[$index]);
                        }
                    }

                    $total = count($bdcs);
                }

                /**
                 * Calcul mensualité, potentiel sur 12 mois
                 * potentiel année en cours, montant
                 */
                $bdcs = $this->calculMensuality($bdcs, $caMensuel, $dataFront);

                # Pagination
                $results = $this->paginator->paginate(
                    $bdcs,
                    $request->query->getInt('page', $dataFront["page"]),
                    $dataFront["rowPerPage"]
                );

                return $this->json([$total, $results], 200, [], ['groups' => ['bdcs']]);
            }

            # Si le bon de commande est vide
            return $this->json("Vide", 200, [], ['groups' => ['bdcs']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/count/bdc/via/statulead", name="count_bdc_statutlead", methods={"GET"})
     * @return Response
     */
    public function countBdcViaStatutLead(BdcRepository $bdcRepository, UserInterface $user): Response
    {
        try {
            $count = $bdcRepository->countBdcByStatutLead($user);

            if ($count){
                $statutNeedToDispalyInFront = $this->getParameter("statut_lead_to_count_bdc_by_statut");

                $resultat = [];
                foreach ($statutNeedToDispalyInFront as $statut){
                    $resultat[] = [
                        "statutLead" => $statut,
                        "nombre" => 0
                    ];
                }

                $nombreRes = count($resultat);
                 foreach ($count as $item){
                     for ($i = 0; $i < $nombreRes; $i++){
                         if($item["statutLead"] == $resultat[$i]["statutLead"]){
                             $resultat[$i]["nombre"] = $item["nombre"];
                         }
                     }
                 }

                return $this->json($resultat, 200, [], ['groups' => ['bdcs']]);
            }

            return $this->json("No result found", 200, [], []);
        } catch (\Exception $exception) {
            return $this->json(["status" => 500, "message" => $exception->getMessage()], 500);
        }
    }

    /**
     * @Route("/get/ca/mensuel/{id}", name="get_ca_mensuel", methods={"GET"})
     * @return Response
     */
    public function getCAMensuel(Bdc $devis, CaMensuel $caMensuel): Response
    {
        try {
            $pot12mois = null;
            $potCurrentYear = null;
            $totalHT = 0;

            #CA Mensuel
            $caMensuels = $caMensuel->getCaMensuel($devis->getBdcOperations());

            $dureeDrt = $devis->getResumeLead()->getDureeTrt()->getId();

            if ($dureeDrt == 2) {
                #CA Potentiel sur 12 mois
                $pot12mois = $caMensuels * 12;

                # CA Potentiel du mois en cours
                $potCurrentYear = $caMensuels * (12 - (date("m")));
            } elseif ($devis->getResumeLead()->getDureeTrt()->getId() == 1) {

                #CA Potentiel sur 12 mois
                $pot12mois = $caMensuels;

                # CA Potentiel du mois en cours
                $potCurrentYear = $caMensuels * 12;
            }

            # Calcul montant
            foreach ($devis->getBdcOperations() As $operation){
                if ($operation->getQuantite()) {
                    /**
                     * Si ligne fact est mixte,
                     * $totalHT = (prixActe * qteActe) + (prixHeure * qteHeure)
                     * sinon, $totalHT = prixUnitaire * quantite
                     */
                    if ($operation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_mixte')){
                        $totalHT += ($operation->getPrixUnitaireActe() * $operation->getQuantiteActe()) + ($operation->getPrixUnitaireHeure() * $operation->getQuantiteHeure());
                    } else {
                        $totalHT += $operation->getPrixUnit() * $operation->getQuantite();
                    }
                }
            }

            return $this->json([$totalHT, $potCurrentYear, $pot12mois], 200, [], []);
        } catch (\Exception $exception) {
            return $this->json(["status" => 500, "message" => $exception->getMessage()], 500);
        }
    }

    /**
     * @Route("/get/suggestion/for/input", name="suggestion_input", methods={"GET"})
     * @return Response
     */
    public function getSuggestionForInput(BdcRepository $bdcRepository, UserInterface $user): Response
    {
        try {
            $numBdc = $bdcRepository->getMyNumBdc($user->getId());

            $paysProd = $this->getDoctrine()->getRepository(PaysProduction::class)->getLibelleOfPaysProd();

            $raisonSocial = $this->getDoctrine()->getRepository(Customer::class)->getMyAllRaisonSocialCustomer($user->getId());

            $dureTrt = $this->getDoctrine()->getRepository(DureeTrt::class)->getLibelleOfDureTrt();

            $societeFacturation = $bdcRepository->getLibelleOfSociFact($user->getId());

            $commercials = $bdcRepository->getCommercialsBdcs($user->getRoles()[0]);

            return $this->json([$numBdc, $paysProd, $raisonSocial, $dureTrt, $societeFacturation, $commercials], 200, [], ['groups' => ['bdcs']]);
        } catch (\Exception $exception) {
            return $this->json(["status" => 500, "message" => $exception->getMessage()], 500);
        }
    }

    /**
     * @Route("/test/repo/list", name="test_repo_list", methods={"POST"})
     * @return Response
     */
    public function testeRepositoryList(BdcRepository $bdcRepository, UserInterface $user, Request $request): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), 'true');

            $datas = $bdcRepository->getBdcList($user, $dataFront);

            return $this->json($datas, 200, [], ['groups' => ['bdcs']]);
        } catch (\Exception $exception) {
            return $this->json(["status" => 500, "message" => $exception->getMessage()], 500);
        }
    }

    /**
     * @Route("/get/seuil/minimum", name="get_seuil_minimum", methods={"GET"})
     * @return Response
     */
    public function getSeuilMinimum(): Response
    {
        try {
            return $this->json($this->getParameter('seuilToStartValidationProcess'), 200, [], []);
        } catch (\Exception $exception) {
            return $this->json(["status" => 500, "message" => $exception->getMessage()], 500);
        }
    }

    /**
     * @Route("/getbdc/between/two/dates", name="bonDeCommande_between_two_date", methods={"POST"})
     * @param Request $request
     * @param BdcRepository $bdcRepository
     * @return Response
     */
    public function getBdcBetweenTwoDate(Request $request, BdcRepository $bdcRepository): Response
    {
        try {
            $dataReject = json_decode($request->getContent(), true);

            $date1 = $dataReject['date1'];
            $date2 = $dataReject['date2'];

            $results = $bdcRepository->findBdcBetweenTwoDate($date1, $date2);

            return $this->json($results, 200, [], ['groups' => ['bdcs']]);
        } catch (\Exception $exception) {
            return $this->json(["status" => 500, "message" => $exception->getMessage()], 500);
        }
    }

    /**
     * @Route("/get/idbdc/via/idcustomer/{customer}", name="get_idbdc_via_idcustomer", methods={"GET"})
     * @param int $customer
     * @return Response
     * Obtenir l'Id des devis via l'ID d'un customer
     */
    public function getIdDevisViaIdCustomer(int $customer, CustomerRepository $customerRepository, BdcRepository $bdcRepository, ResumeLeadRepository $resumeLeadRepository): Response
    {
        try {
            $customer = $customerRepository->find($customer);

            $resumeLead = $resumeLeadRepository->findBy(
                ['customer' => $customer], ['id' => 'DESC'], 1
            );

            $bdc = $bdcRepository->findBy(
                ['resumeLead' => $resumeLead]
            );

            $nb = count($bdc);
            $idBdc = array();

            for($i=0; $i<$nb; $i++){
                $id = $bdc[$i]->getId();
                array_push($idBdc, $id);
            }

            return $this->json($idBdc, 200, [], ['groups' => ['get-by-bdc']]);

        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/bonDeCommande/via/id/{id}", name="bonDeCommande_via_hisid", methods={"GET"})
     * @param int $id
     * @param BdcRepository $repository
     * @return Response
     * Get bon de commande par rapport à son id
     */
    public function getBdcViaHisId(int $id, BdcRepository $repository): Response {
        try {
            $data = $repository->GetBdcById($id);

            return $this->json($data, 200, [], ['groups' => ['get-by-bdc']]);
        } catch (\Exception $exception) {
            return $this->json(["status" => 500, "message" => $exception->getMessage()], 500);
        }
    }

    /**
     * @Route("/to/lost/devis/{id}", name="lost_devis", methods={"GET"})
     * @param Bdc $devis
     * @param Lead $lead
     * @param EntityManagerInterface $entityManager
     * @return Response
     * Change le statut du bdc en bdc perdu
     */
    public function lostDevis(Bdc $devis, Lead $lead, EntityManagerInterface $entityManager): Response {
        try {
            $id = $devis->getId();

            # Mis à jour statut du devis.
            $lead->updateStatusLeadBdc($id, $this->getParameter('statut_lead_bdc_perdu'));

            /*
                $clientId = $devis->getResumeLead()->getCustomer()->getId();

                # On recupère tout les bdcs du client
                $customersBdcs = $this->getDoctrine()->getRepository(Bdc::class)->getBdcForOneCustomer($clientId);

                # Si nombre de bdc client est égal à 1, alors on met à jours le statut du client en client perdue
                if (count($customersBdcs) == 1){
                    $customer = $this->getDoctrine()->getRepository(Customer::class)->find($clientId);

                    if (!empty($customersBdcs) && !empty($customer)){
                        # Mis à jour statut du client.
                        $lead->updateStatusLeadByCustomer($customer, $this->getParameter('statut_lead_bdc_perdu'));

                        $categoryClient = $this->getDoctrine()->getRepository(CategorieClient::class)->find(3);

                        # Mis à jour categorie du client
                        $customer->setCategorieClient($categoryClient);

                        $entityManager->persist($customer);
                        $entityManager->flush();
                    }
                }
            */

            return $this->json("Devis has been losted.", 200, [], ['groups' => ['get-by-bdc']]);
        } catch (\Exception $exception) {
            return $this->json(["status" => 500, "message" => $exception->getMessage()], 500);
        }
    }

    /**
     * @Route("/send/devis/to/validator/{id}", name="send_bdc_to_his_validator", methods={"GET"})
     * @param Bdc $currentDevis
     * @param EntityManagerInterface $em
     * @param SendMailTo $sendMailTo
     * @param UserRepository $userRepository
     * @param UserInterface $currentUser
     * @return Response
     * Envoyer du bon de commande au validateur
     */
    public function sendDevisToValidator(Bdc $currentDevis, EntityManagerInterface $em, SendMailTo $sendMailTo, UserRepository $userRepository, UserInterface $currentUser): Response {
        try {
            # Verifier si Montant du Bdc est supérieur au seuil
            $isSuperior = $this->isMontantSuperiorToSeuil($currentDevis);

            if ($isSuperior) {
                $users = $userRepository->findAll();

                # Recuperation de l'objet pour la notification validateur.
                $emailObjectOfValidator = $this->getEmailObjectOfValidator($currentDevis);

                $currentDevisId = $currentDevis->getId();
                $paysProdDevis = $currentDevis->getPaysProduction();

                # Recupere la premiere validateur
                $firstValidator = $this->getParameter('role_validators')[0];

                # Envoie email
                foreach ($users as $user) {
                    $roles = $user->getRoles();
                    $userPaysProd = $user->getPaysProduction();

                    # Notifier les validateurs (avec backup)
                    if ((in_array($firstValidator, $roles) && $userPaysProd &&
                        $userPaysProd->getId() == $paysProdDevis->getId())) {
                        $sendMailTo->sendEmailViaTwigTemplate($currentUser->getEmail(), $user->getEmail(), $emailObjectOfValidator, 'emailContent/forValidationSuperior.html.twig', $currentUser, $currentDevisId);
                    }

                    # Notifier le directeur gestion compte
                    if (in_array($this->getParameter('role_dir_gest_compte'), $roles)) {
                        $sendMailTo->sendEmailViaTwigTemplate($currentUser->getEmail(), $this->getParameter('email_dir_gest_compte'), $emailObjectOfValidator, 'emailContent/forValidationSuperior.html.twig', $currentUser, $currentDevisId);
                    }
                }

                $currentDevis->setStatutLead($this->getParameter('statut_lead_workflow_validation')[0]);
                $em->persist($currentDevis);
                $em->flush();

                return $this->json("Devis envoyé au validateur", 200, [], ['groups' => ['update-bdc', 'current-user', 'contact']]);
            } else {
                return $this->json(-1, 200, [], []);
            }
        } catch (\Exception $exception) {
            return $this->json(["status" => 500, "message" => $exception->getMessage()], 500);
        }
    }

    /**
     * @Route("/send/devis/to/customer/for/sign/{id}", name="send_bdc_to_customer", methods={"GET"})
     */
    public function sendBdcToCustomerForSign(Bdc $devis, Lead $lead, ContactRepository $contactRepository, EntityManagerInterface $em): Response
    {
        try {
            # Verifier si Montant du Bdc est inférieur au seuil
            $isSuperior = $this->isMontantSuperiorToSeuil($devis);

            if (!$isSuperior) {
                # Envoie signature electronique au client
                $id = $devis->getId();
                $client = $devis->getResumeLead()->getCustomer();

                # Construction du fichier à envoyer
                $files = [
                    ['type' => 'doc1', 'fileName' => 'devis_' . $id . '.pdf']
                ];

                $page = $this->nbr_pages($this->getParameter('bdc_dir') . 'devis_' . $id . '.pdf');

                foreach ($devis->getDestinataireSignataire() as $idContact) {
                    $contact = $contactRepository->find($idContact);
                    if (!empty($contact)) {
                        # Prend l'information du signataire
                        $signataire = [];

                        $signataire["name"] = $contact->getPrenom();
                        $signataire["email"] = $contact->getEmail();

                        $this->sendToSign($files, $signataire, $devis, $em, $page, 1);
                    }
                }

                # Mis à jour statutLead
                $this->updateStatutLead($id, intval($this->getParameter('statut_lead_bdc_signe_com')), $client, $lead);

                return $this->json("Devis envoyé au client ...", 200, [], ['groups' => ['update-bdc', 'current-user', 'contact']]);
            } else {
                return $this->json(-1, 200, [], []);
            }
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/getedUser", name="getedUser", methods={"GET"})
     */
    public function getedUser(UserInterface $user): Response
    {
        try {
            return $this->json($user->getId(), 200, [], ['groups' => ['bdcs']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/bdc/{uniqId}", name="bdc_uniqId", methods={"GET"})
     * @param string $uniqId
     * @param BdcRepository $repo
     * @return Response
     * Get devis par rapport à son unique id....
     */
    public function getBdcByUniqId(string $uniqId, BdcRepository $repo): Response {
        try {
            $reponse=$repo->GetBdcByUniqId($uniqId);

            return $this->json($reponse, 200, [], ['groups' => ['get-by-bdc']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/cout/horaire/{pays}", name="cout_horaire", methods={"GET"})
     * @param CoutHoraireRepository $coutHoraireRepository
     * @return Response
     */
    public function getCoutHoraireEntre2Date($pays, CoutHoraireRepository $coutHoraireRepository): Response {
        try {
            return $this->json($coutHoraireRepository->findWithTwoDate($pays), 200, [], ['groups' => ['cout-horaire']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/cout/horaire", name="cout_horaire_all", methods={"GET"})
     * @param CoutHoraireRepository $coutHoraireRepository
     * @return Response
     */
    public function getAllCoutHoraireDateCurrent(CoutHoraireRepository $coutHoraireRepository): Response {
        try {
            return $this->json($coutHoraireRepository->findAllDateCurrent(), 200, [], ['groups' => ['cout-horaire']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/bon/de/commande/operation/{id}", name="bon_de_commande_operation_id", methods={"GET"})
     * @param integer $id
     * @param BdcOperationRepository $repository
     * @return Response
     */
    public function getByIdBdcOperation(int $id, BdcOperationRepository $repository): Response {
        try {
            return $this->json($repository->find($id), 200, [], ['groups' => ['get-by-bdc']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/test/indic", name="teste_indic", methods={"GET"})
     * @return Response
     */
    public function testeIndic(): Response {
        $imgfile = file_get_contents($this->getParameter('bonus_malus_image_dir') . "img_630e5b317b01c4.50935714.jpg");

        if ($imgfile){
            return $this->json(base64_encode($imgfile), 200, [], ['groups' => ['update']]);
        } else {
            return $this->json("File not found", 200, [], ['groups' => ['update']]);
        }
    }

    /**
     * @Route("/check/imported/file", name="check", methods={"POST"})
     * @return Response
     */
    public function storeImageOnLocal(Request $request): Response {
        try {
            $jsonData = json_decode($request->getContent(), true);
            if (!empty($jsonData)){
                # Upload file on local
                $base64service = new CurrentBase64Service();

                $filename = $base64service->convertToImageFile(
                    $jsonData,
                    $this->getParameter('bonus_malus_image_dir')
                );

                return $this->json($filename, 200, [], []);
            }
        } catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/bdc/operation/{id}", name="bdc_operation_id", methods={"PUT"})
     * @param integer $id
     * @param Request $request
     * @param BdcOperationRepository $bdcOperationRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function updateBdcOperation(int $id, Request $request, BdcOperationRepository $bdcOperationRepository, EntityManagerInterface $em,
                                       SendMailTo $sendMailTo, UserRepository $userRepository, UserInterface $currentUser,
                                       IndicatorQuantitatifRepository $indicatorQuantitatifRepository, IndicatorQualitatifRepository $indicatorQualitatifRepository): Response
    {
        try {
            $bdcOperation = $bdcOperationRepository->find($id);
            $jsonResponse = json_decode($request->getContent(), true);

            $imgName = "";

            if ($bdcOperation && $jsonResponse) {

                $isPdfFile = true;
                $message = "Merci d'importer un fichier PDF SVP";

                if ($bdcOperation->getOperation()->getId() == $this->getParameter("param_id_operation_malbon")){
                    if (!empty($jsonResponse['pdfDescription']) && strpos($jsonResponse['pdfDescription'], "/pdf") === false){
                        $isPdfFile = false;
                    }
                }

                if ($isPdfFile){
                    # Appel methode pour avoir nouvel statut et notification par email
                    list($objNotif, $twigNotif, $newStatut, $respNotif) = $this->getNewStatutAndNotification($bdcOperation->getBdc());

                    $currentBdc = $bdcOperation->getBdc();

                    $allUsers = $userRepository->findAll();

                    $validators = [];
                    foreach ($allUsers as $user) {
                        $roles = $user->getRoles();
                        if (in_array($respNotif, $roles)) {
                            array_push($validators, $user);
                        }
                    }

                    $statutBdc = $currentBdc->getStatutLead();

                    $rejectedStatut = array_merge($this->getParameter("statut_lead_rejeter"), $this->getParameter("statut_lead_avenant_rejeter"));

                    # Mise à jour bon de commande suite au refus Dir prod ou Dir fin ou DG
                    if (in_array($statutBdc, $rejectedStatut))
                    {
                        ################################## Met à jour le prix unitaire ##################################
                        $this->setPrixUnitaireAndQuantity($bdcOperation, $jsonResponse);
                        #################################################################################################

                        $em->persist($bdcOperation);
                        $em->flush();

                        $avenant = null;
                        if (in_array($statutBdc, $this->getParameter('statut_lead_avenant_rejeter'))) {
                            $avenant = "avenant";
                        } else {
                            # Mise à jour lead detail operation associé
                            $this->updateLeadDetailOperation($bdcOperation, $jsonResponse, $em);
                        }

                        /*
                            # Notification validateur si current BDC a été Rejeté
                            if (count($validators) > 0){
                                foreach ($validators as $validator) {
                                    if ($respNotif == "ROLE_DIRPROD") {
                                        if ($validator->getPaysProduction() != null && $validator->getPaysProduction()->getId() == $currentBdc->getPaysProduction()->getId()) {
                                            $sendMailTo->sendEmailViaTwigTemplate($currentUser->getEmail(), $validator->getEmail(), $objNotif, 'emailContent/' . $twigNotif, $currentUser, $bdcOperation->getBdc()->getId());
                                        }
                                    } else {
                                        $sendMailTo->sendEmailViaTwigTemplate($currentUser->getEmail(), $validator->getEmail(), $objNotif, 'emailContent/' . $twigNotif, $currentUser, $bdcOperation->getBdc()->getId());
                                    }
                                }
                            }

                            # Generation d'un pdf pour le bdc
                            $this->setPdf($bdcOperation->getBdc(), "client", $avenant);
                        */
                    } else{
                        # Mise à jour bdc opération normal (process création vente)
                        if ($bdcOperation->getOperation()->getId() == $this->getParameter('param_id_operation_malbon')){
                            if (!empty($jsonResponse['pdfDescription'])) {
                                /**
                                 * On verifie d'abord s'il existe déja une fichier dans le champs description,
                                 * si oui, alors on supprime ce fichier
                                 */
                                if (!empty($bdcOperation->getDescription())){
                                    $fileTodelete = $this->getParameter('bonus_malus_image_dir').$bdcOperation->getDescription();

                                    # Suppression du fichier
                                    if (file_exists($fileTodelete)){
                                        unlink($fileTodelete);
                                    }
                                }

                                # Convert base64 to pdf
                                $base64service = new CurrentBase64Service();

                                $filename = $base64service->convertToFile($jsonResponse['pdfDescription'], $this->getParameter('bonus_malus_image_dir'), 'pdf_');

                                $file = $this->getParameter('bonus_malus_image_dir').$filename;

                                if (file_exists($file)){
                                    # Convert pdf to image
                                    $newImgFileName = uniqid("img_", true).".jpg";

                                    $imagick = new \Imagick();

                                    $imagick->setResolution(300, 300);
                                    $imagick->readImage($file);


                                    $imagick = $imagick->flattenImages();

                                    $imgFile = $this->getParameter('bonus_malus_image_dir').$newImgFileName;
                                    $imagick->trimImage(0);
                                    $imagick->writeImages($imgFile, false);

                                    $imgName = $newImgFileName;

                                    # convert image to base64
                                    $imageFile = file_get_contents($imgFile);

                                    $base64Img = str_replace("\/","/", base64_encode($imageFile));

                                    # Pour un affichage dans pdf
                                    $bdcOperation->setEncodedImage($base64Img);

                                    # Pour un affichage dans front
                                    $bdcOperation->setDescription($newImgFileName ?? null);

                                    # Suppression du fichier pdf
                                    unlink($file);
                                }
                            }
                        }

                        $bdcOperation->setTarifHoraireCible($jsonResponse['tarifHoraireCible'] ?? null);
                        $bdcOperation->setTarifHoraireFormation($jsonResponse['tarifHoraireFormation'] ?? null);
                        $bdcOperation->setDmt($jsonResponse['dmt'] ?? null);
                        $bdcOperation->setNbHeureMensuel($jsonResponse['nbHeureMensuel'] ?? null);
                        $bdcOperation->setNbEtp($jsonResponse['nbEtp'] ?? null);

                        if (isset($jsonResponse['designationActe'])){
                            $bdcOperation->setDesignationActe($this->getDoctrine()->getRepository(Operation::class)->find($jsonResponse['designationActe']));
                        }

                        if (!empty($jsonResponse['coutHoraire'])) {
                            $bdcOperation->setCoutHoraire($this->getDoctrine()->getRepository(CoutHoraire::class)->find($jsonResponse['coutHoraire']));
                        }

                        if (!empty($jsonResponse['bu'])) {
                            $res = intval($jsonResponse['bu']);
                            $bdcOperation->setBu($this->getDoctrine()->getRepository(Bu::class)->find($res));
                        }

                        if (!empty($jsonResponse['langueTrt'])) {
                            $res = intval($jsonResponse['langueTrt']);
                            $bdcOperation->setLangueTrt($this->getDoctrine()->getRepository(LangueTrt::class)->find($res));
                        }

                        if(!empty($jsonResponse['objectifQuantitatif']))
                        {
                            foreach($jsonResponse['objectifQuantitatif'] As $objectifQuantitatif)
                            {
                                $bdcOperation->addObjectifQuantitatif($this->getDoctrine()->getRepository(ObjectifQuantitatif::class)->find($objectifQuantitatif));
                            }
                        }

                        if(!empty($jsonResponse['objectifQualitatif']))
                        {
                            foreach($jsonResponse['objectifQualitatif'] As $objectifQualitatif)
                            {
                                $bdcOperation->addObjectifQualitatif($this->getDoctrine()->getRepository(ObjectifQualitatif::class)->find($objectifQualitatif));
                            }
                        }

                        $hisLeadDetailOperation = $this->getDoctrine()->getRepository(LeadDetailOperation::class)->findOneBy(
                            ['uniqBdcFqOperation' => $bdcOperation->getUniqBdcFqOperation()]
                        );

                        # Supression des indicateurs quantitatifs appartenant au ligne de facturation
                        $indicatorQuantitatifRepository->deleteByIdBdcOperation($bdcOperation->getId());

                        # Set indicateur quantitatifs
                        if(!empty($jsonResponse['indicateurQt'])) {
                            foreach($jsonResponse['indicateurQt'] As $indicQt)
                            {
                                $indicatorQt = new IndicatorQuantitatif();

                                $indicatorQt->setObjectifQuantitatif($this->getDoctrine()->getRepository(ObjectifQuantitatif::class)->find($indicQt['objectifQt']));
                                $indicatorQt->setIndicator($indicQt['indicator'] ?? null);
                                $indicatorQt->setUniqBdcFqOperation($bdcOperation->getUniqBdcFqOperation() ?? null);

                                $bdcOperation->addIndicatorQuantitatif($indicatorQt);

                                if ($hisLeadDetailOperation){
                                    $hisLeadDetailOperation->addIndicatorQuantitatif($indicatorQt);
                                }
                            }
                        }

                        # Supression des indicateurs qualitatifs appartenant au ligne de facturation
                        $indicatorQualitatifRepository->deleteByIdBdcOperation($bdcOperation->getId());

                        # Set indicateur qualitatifs
                        if(!empty($jsonResponse['indicateurQl'])) {
                            foreach($jsonResponse['indicateurQl'] As $indicQl)
                            {
                                $indicatorQl = new IndicatorQualitatif();

                                $indicatorQl->setObjectifQualitatif($this->getDoctrine()->getRepository(ObjectifQualitatif::class)->find($indicQl['objectifQl']));
                                $indicatorQl->setIndicator($indicQl['indicator'] ?? null);
                                $indicatorQl->setUniqBdcFqOperation($bdcOperation->getUniqBdcFqOperation() ?? null);

                                $bdcOperation->addIndicatorQualitatif($indicatorQl);

                                if ($hisLeadDetailOperation){
                                    $hisLeadDetailOperation->addIndicatorQualitatif($indicatorQl);
                                }
                            }
                        }

                        $dmtToMin = 0;
                        if(!empty($jsonResponse['dmt']))
                        {
                            $dmtToMin = $this->getTimeToNumber($jsonResponse['dmt']);
                        }

                        $tempProdToMin = 0;

                        if(!empty($jsonResponse['tempProd']))
                        {
                            $tempProdToMin = $this->getTimeToNumber($jsonResponse['tempProd']);
                        } else if(!empty($jsonResponse['tempsProductifs']))
                        {
                            $tempProdToMin = $this->getTimeToNumber($jsonResponse['tempsProductifs']);
                        }

                        # prod/h
                        if($dmtToMin > 0 && $tempProdToMin > 0)
                        {
                            $prodParHeure = round($tempProdToMin / $dmtToMin, 2);

                            $bdcOperation->setProdParHeure($prodParHeure);
                        } else{
                            $bdcOperation->setProdParHeure("");
                        }

                        # Duree de formation et ressource à former
                        $bdcOperation->setDuree($jsonResponse['Duree'] ?? null);
                        $bdcOperation->setRessourceFormer($jsonResponse['ressourceFormer'] ?? null);

                        $bdcOperation->setOffert($jsonResponse['offert'] ?? 0);
                        $bdcOperation->setIsParamPerformed(1);

                        ############################### Met à jour le prix unitaire #############################
                        $this->setPrixUnitaireAndQuantity($bdcOperation, $jsonResponse);
                        #########################################################################################

                        $em->persist($bdcOperation);

                        $em->flush();

                        # Mise à jour bdc opération avec une opération panne technique outsourcia
                        $this->editOperationPanneTechniqueOutsourcia($bdcOperation, $jsonResponse, $em);
                    }
                } else {
                    return $this->json(['error' => 1,'message' => $message], 200, [], []);
                }
            }

            return $this->json($imgName, 201, [], ['groups' => ['update']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @param BdcOperation $bdcOperation
     * @param $jsonResponse
     * Met à jour le prix unitaire d'une ligne de facturation
     * HNO ou non et ainsi que les quantité
     */
    private function setPrixUnitaireAndQuantity(BdcOperation $bdcOperation, $jsonResponse){
        # Pour une ligne de facturation mère
        if ($bdcOperation->getIsHnoHorsDimanche() == null && $bdcOperation->getIsHnoDimanche() == null) {
            # Mis à jours prix unitaire des lignes de facturations HNO
            if ($bdcOperation->getValueHno() == "Oui"){
                $this->updatePrixUnitOfLigneFacturationHNO($bdcOperation, $jsonResponse);
            }

            /**
             * Mis à jour des prix unitaire selon le type de facturation du ligne de facturation
             * et la quantité
             */
            $this->updatePrixUnitOfLigneFact($bdcOperation, $jsonResponse);
        }

        # Pour une ligne de facturation HNO
        if ($bdcOperation->getIsHnoHorsDimanche() == 1 || $bdcOperation->getIsHnoDimanche() == 1) {
            $unitPrice = null;

            # Prend le ligne de facturation mère
            $parentBdcOperation = $this->getDoctrine()->getRepository(BdcOperation::class)->findParentBdcOperation($bdcOperation->getOperation()->getId(), $bdcOperation->getBdc()->getId());

            # On recupère le prix unitaire du ligne de facturation mère
            $prixUnitMere = $this->getPrixUnitMere($parentBdcOperation[0], $bdcOperation);

            # Calcul prix unitaire ligne facturation HNO
            if ($bdcOperation->getIsHnoHorsDimanche() == 1) {
                $unitPrice = round(((($jsonResponse['majoriteHnoHorsDimanche'] * $prixUnitMere) / 100) + $prixUnitMere), 2);

                $bdcOperation->setMajoriteHnoHorsDimanche($jsonResponse['majoriteHnoHorsDimanche'] ?? null);
            } elseif ($bdcOperation->getIsHnoDimanche() == 1) {
                $unitPrice = round(((($jsonResponse['majoriteHnoDimanche'] * $prixUnitMere) / 100) + $prixUnitMere), 2);

                $bdcOperation->setMajoriteHnoDimanche($jsonResponse['majoriteHnoDimanche'] ?? null);
            }

            $bdcOperation->setPrixUnit($unitPrice);
        }
    }

    /**
     * @Route("/bon/de/commande/ref/{entityName}", name="bon_de_commande_ref", methods={"GET"})
     * @param string $entityName
     * @return Response
     */
    public function getRefBdc(string $entityName): Response
    {
        try {
            $data = $this->getDoctrine()->getRepository(sprintf('App\Entity\%s', $entityName))->findAll();

            return $this->json($data, 200, [], ['groups' => ['ref']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/purchase/order/{id}", name="purchase_order", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function viewBcd(int $id, BdcRepository $bdcRepository): Response
    {
        try {
            #Get BDC
            return $this->json($bdcRepository->find($id), 200, [], ['groups' => ['view']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }
    /**
     * @Route("/validate/bdc/hausse/{id}/{langue}/{idContactExaminateur}", name="signatureHausse", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function signatureHausse(int $id, $langue = "FR", $idContactExaminateur,EntityManagerInterface $em, BdcRepository $bdcRepository,HausseIndiceSyntecClientRepository $repoSyntecclient,ContactRepository $repoContact): Response
    {
        try {
            #Get bdc By Id Mere
            $bdcAllByMere = $bdcRepository->getBdcByIdMere($id);
            $bdcValidate = end( $bdcAllByMere);
            $contactExamin = $repoContact->find($idContactExaminateur);

            #Client Info
            $signataire = [];
            $client = $bdcValidate->getResumeLead()->getCustomer();

            #Changer la Status en Valider
            $repoSyntecclient->UpdateStatusHausse($client->getId());
            $signataire["name"] = $contactExamin->getNom();
            $signataire["email"] = $contactExamin->getEmail();

            #Changer le StatusLead
            if($langue == "FR"){
                copy($this->getParameter('bdc_dir') . $bdcValidate->getResumeLead()->getCustomer()->getRaisonSocial().' Avenant Revision taifaire 2023.pdf', $this->getParameter('bdc_dir') . $bdcValidate->getResumeLead()->getCustomer()->getRaisonSocial().' Pour Client Avenant Revision taifaire 2023.pdf');

                # Construction du fichier à envoyer
                $files = [
                    ['type' => 'doc1', 'fileName' => $bdcValidate->getResumeLead()->getCustomer()->getRaisonSocial().' Avenant Revision taifaire 2023.pdf']
                ];
            }
            else{
                copy($this->getParameter('bdc_dir') . $bdcValidate->getResumeLead()->getCustomer()->getRaisonSocial().' Avenant Revision taifaire 2023UK.pdf', $this->getParameter('bdc_dir') . $bdcValidate->getResumeLead()->getCustomer()->getRaisonSocial().' Pour Client Avenant Revision taifaire 2023UK.pdf');

                # Construction du fichier à envoyer
                $files = [
                    ['type' => 'doc1', 'fileName' => $bdcValidate->getResumeLead()->getCustomer()->getRaisonSocial().' Avenant Revision taifaire 2023UK.pdf']
                ];
            }
           
            $page = $this->nbr_pages($this->getParameter('bdc_dir') . $bdcValidate->getResumeLead()->getCustomer()->getRaisonSocial().' Avenant Revision taifaire 2023.pdf');
            $page = $page * -1;

            # On passe à la signature éléctronique
            $this->sendToSign($files, $signataire, $bdcValidate, $em, $page,1);

            return $this->json("Email envoyer!");
        }
        catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/validate/devis/{id}", name="validate_devis", methods={"GET"})
     * @param Bdc $currentDevis
     * @param SendMailTo $sendMailTo
     * @param Lead $lead
     * @param UserInterface $currentUser
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function validateBdc(Bdc $currentDevis, SendMailTo $sendMailTo, Lead $lead, UserInterface $currentUser, EntityManagerInterface $em): Response
    {
        $currentDevisId = $currentDevis->getId();
        $statutLeadDevis = $currentDevis->getStatutLead();

        # Trouver la position du statutlead parmi nouveaux statutlead après validation du validateur
        $statutLeadPos = array_search($statutLeadDevis, $this->getParameter('statut_lead_workflow_validation'));

        try {
            # Recuperation de l'objet pour la notification validateur.
            $emailObjectOfValidator = $this->getEmailObjectOfValidator($currentDevis);

            $customer = $currentDevis->getResumeLead()->getCustomer();
            $dest = $customer->getUser();

            $currentUserRole = $currentUser->getRoles()[0];
            $currentUserEmail = $currentUser->getEmail();
            $currentUserRolePos = array_search($currentUserRole, $this->getParameter('role_validators'));
            $superiorRole = $this->getParameter('role_validators')[$currentUserRolePos + 1];

            # Cas envoie signature
            if ($superiorRole == "SIGNATAIRE"){
                # Envoie signature electronique au signataire
                $currentDevisIdMere = $currentDevis->getIdMere();

                $signataire = [];
                $signataire["name"] = $dest->getUsername();
                $signataire["email"] = $dest->getEmail();

                if (!in_array($statutLeadDevis, $this->getParameter('statut_lead_bdc_avenant'))) {
                    # Créer une copie du pdf pour la signature commerciale
                    copy($this->getParameter('bdc_dir') . "devis_$currentDevisIdMere.pdf", $this->getParameter('bdc_dir') . "devis_com_$currentDevisIdMere.pdf");

                    # Construction du fichier à envoyer
                    $files = [
                        ['type' => 'doc1', 'fileName' => "devis_com_$currentDevisIdMere.pdf"]
                    ];

                    $page = $this->nbr_pages($this->getParameter('bdc_dir') . "devis_$currentDevisIdMere.pdf");
                } else {
                    # Créer une copie du pdf pour la signature commerciale
                    copy($this->getParameter('bdc_dir') . "devis_avenant_$currentDevisIdMere.pdf", $this->getParameter('bdc_dir') . "devis_avenant_com_$currentDevisIdMere.pdf");

                    # Construction du fichier à envoyer
                    $files = [
                        ['type' => 'doc1', 'fileName' => "devis_avenant_com_$currentDevisIdMere.pdf"]
                    ];

                    $page = $this->nbr_pages($this->getParameter('bdc_dir') . "devis_avenant_$currentDevisIdMere.pdf");
                }

                # On passe à la signature éléctronique
                $this->sendToSign($files, $signataire, $currentDevis, $em, $page);
            } else {
                # Cas envoie au validateur

                # Recupère les prochains validateurs
                $userDatas = $this->getDoctrine()->getRepository(User::class)->findAll();

                # Envoie email de notification aux validateurs
                foreach ($userDatas as $user) {
                    if (in_array($superiorRole, $user->getRoles())) {
                        $sendMailTo->sendEmailViaTwigTemplate($currentUserEmail, $user->getEmail(), $emailObjectOfValidator, 'emailContent/forValidationSuperior.html.twig', $currentUser, $currentDevisId);
                    }
                }
            }

            # Envoie d'un email au commercial
            $numBdc = $currentDevis->getNumBdc();
            $emailObjectForCommercial = "Devis n° $numBdc validé par $currentUserEmail";
            $sendMailTo->sendEmailViaTwigTemplate($currentUserEmail, $dest->getEmail(), $emailObjectForCommercial, 'emailContent/commercialeNotif.html.twig', $currentUser, $currentDevisId);

            # Mis à jour statutLead
            $newStatutToValidate = $this->getParameter('statut_lead_workflow_validation')[$statutLeadPos + 1];
            $this->updateStatutLead($currentDevisId, $newStatutToValidate, $customer, $lead);

            return $this->json('Devis validé avec succès !', 200, [], ['groups' => ['bdcs', 'sendtosign']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route ("/devis/rejected/{id}", name="devis_rejected", methods={"POST"})
     * @param Bdc $devisRejected
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserInterface $currentUser
     * @param Lead $lead
     * @param SendMailTo $sendMailTo
     * @param RejectBdcRepository $rejectBdcRepository
     * @return Response
     */
    public function rejectBcd(Bdc $devisRejected, Request $request,EntityManagerInterface $em, UserInterface $currentUser, Lead $lead, SendMailTo $sendMailTo, RejectBdcRepository $rejectBdcRepository): Response
    {
        try {
            $id = $devisRejected->getId();
            $dataReject = json_decode($request->getContent(), true);
            $comment = $dataReject["value"];

            $rejectedDevis = $rejectBdcRepository->findOneBy([
                "bdc" => $id
            ]);

            # Add or update row in rejectBdc table
            if ($rejectedDevis) {
                $rejectedDevis->setComment($comment)
                    ->setCreatedAt(new \DateTime());
                $em->persist($rejectedDevis);
            } else {
                $devisReject = new RejectBdc();
                $devisReject->setBdc($devisRejected)
                    ->setComment($comment)
                    ->setCreatedAt(new \DateTime());
                $em->persist($devisReject);
            }

            $customerBcd = $devisRejected->getResumeLead()->getCustomer();

            # Récupération du nouveau statut
            $statutLeadPos = array_search($devisRejected->getStatutLead(), $this->getParameter("statut_lead_validators_reject"));
            $newStatut = $this->getParameter("statut_lead_validators_reject")[$statutLeadPos + 1];

            # MAJ champ status lead dans la table Bdc
            $lead->updateStatusLeadBdc($id, $newStatut);

            # Ajout ou MAJ statut client dans la table StatutLead
            $lead->updateStatusLeadByCustomer($customerBcd, $newStatut);

            # Ajout d'une ligne dans la table WorkflowLead
            $lead->addWorkflowLead($customerBcd, $newStatut);

            $em->flush();

            $commercial = $devisRejected->getResumeLead()->getCustomer()->getUser();

            $sendMailTo->sendEmailViaTwigTemplate($currentUser->getEmail(), $commercial->getEmail(), "Devis n° " . $devisRejected->getNumBdc() . " rejeté par ".$currentUser->getUsername(), 'emailContent/rejectNotifForCommercial.html.twig', $currentUser, $id);

            return $this->json('Devis réjeté', 200, [], ['groups' => ['bdcs']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/relance/signature/{id}", name="relance_signature", methods={"GET"})
     * @return Response
     * Relance le commercial ou client pour un signature éléctronique
     */
    public function relancheSignature(Bdc $devis, ContactRepository $contactRepository, EntityManagerInterface $em): Response {
        try {
            $signataire = [];
            $statutlead = $devis->getStatutLead();

            list($commercialFile, $customerFile) = $this->bdcParams($statutlead);

            if (in_array($statutlead, $this->getParameter('statut_lead_signed_by_commercial'))){
                # Pour signature commercial
                $dest = $devis->getResumeLead()->getCustomer()->getUser();
                $signataire["name"] = $dest->getUsername();
                $signataire["email"] = $dest->getEmail();

                # Construction du fichier à envoyer
                $fileName = $commercialFile . $devis->getIdMere() . '.pdf';

                $files = [
                    ['type' => 'doc1', 'fileName' => $fileName]
                ];

                $page = $this->nbr_pages($this->getParameter('bdc_dir') . $fileName);
            } else {
                # Pour signature DG
                # Recuperation email de contact du client
                $contacts =$devis->getResumeLead()->getCustomer()->getContacts();

                # Recuperation destinataire du BDC
                $destinataires = $devis->getDestinataireSignataire();

                if(empty($destinataires))
                {
                    $destinataires = [];

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

                $fileName = $customerFile . $devis->getIdMere() . '.pdf';

                $files = [
                    ['type' => 'doc1', 'fileName' => $fileName]
                ];

                $page = $this->nbr_pages($this->getParameter('bdc_dir') . $fileName);
            }

            # On passe à la signature éléctronique
            $this->sendToSign($files, $signataire, $devis, $em, $page);

            return $this->json("Rélance éffectué", 200, [], ['groups' => ['bdcs', 'sendtosign']]);
        } catch (\Exception $exception) {
            return $this->json(["status" => 500, "message" => $exception->getMessage()], 500);
        }
    }

    /**
     * @param int $statulead
     * @return array
     */
    private function bdcParams(int $statulead): array
    {
        $commercialFile = null;
        $customerFile = null;

        switch ($statulead)
        {
            case $this->getParameter('statut_lead_bdc_valider_dg'):
            case $this->getParameter('statut_lead_bdc_signe_com'):
                $commercialFile = "devis_com_";
                $customerFile = "devis_";
                break;
            case $this->getParameter('statut_lead_bdc_avenant_valider_dg'):
            case $this->getParameter('statut_lead_bdc_avenant_signe_com'):
                $commercialFile = "devis_avenant_com_";
                $customerFile = "devis_avenant_";
                break;
        }

        return array($commercialFile, $customerFile);
    }

    /**
     * @Route("/get/profil/agent", name="profil_agent", methods={"POST"})
     * @return Response
     */
    public function getProfilAgent(Request $request, CoutHoraireRepository $coutHoraireRepository): Response
    {
        try {
            $data = json_decode($request->getContent(), true);

            if ($data['paysProd'] && $data['lnguetrt'] && $data['bu']) {
                $paysProd = $data['paysProd'];
                $lngtrt = $data['lnguetrt'];
                $bu = $data['bu'];

                $profils = $coutHoraireRepository->getRefProfilAgent($paysProd, $lngtrt, $bu);
                if (!empty($profils)) {
                    return $this->json($profils, 200, [], ['groups' => ['profil-agent']]);
                } else {
                    return $this->json("Vide", 200, [], []);
                }
            }
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/test/marge/cible/{id}", name="test_marge_cible", methods={"GET"})
     * @return Response
     */
    public function testeMargeCible(int $id, BdcRepository $bdcRepository): Response
    {
        try {
            $bdc = $bdcRepository->find($id);
            if ($bdc) {
                $res = $this->getMargeCible($bdc);

                return $this->json($res, 200, [], ['groups' => ['bdcs']]);
            }
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/bdc/update/hausse/{id}", name="bon_de_commande__update_AfterHausse", methods={"PUT"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function updateBdcAfterHausseSyntec(int $id, Request $request, EntityManagerInterface $em, UserInterface $currentUser, UserRepository $userRepository, Lead $lead, SendMailTo $sendMailTo,HauseIndiceLignefacturationRepository $repoHausseLigne): Response{
        $currentBdc = $this->getDoctrine()->getRepository(Bdc::class)->find($id);

        # Necessaire pour send mail au validateur, mise à jour statut lead du client et workflow lead.......
        list($client, $user, $clientObj) = $this->getUserConnecte($currentBdc);

        # Controle champs obligatoires......................
        $ligneFacturations = $currentBdc->getBdcOperations();
        $error = 0;
        $message = "Merci de remplir les champs suivants : ";
        # Verification des champs obligatoires dans les lignes de facturatons
        $tabIdOperationAuto = $this->getParameter('param_not_required_profil_agent');
        foreach($ligneFacturations As $ligneFacturation)
        {
            # Verification majorition pour ligne de facturation HNO
            if ($ligneFacturation->getIsHnoHorsDimanche() == 1) {
                if ($ligneFacturation->getMajoriteHnoHorsDimanche() == null  || strlen((string)$ligneFacturation->getMajoriteHnoHorsDimanche()) == 0) {
                    $message .= "- Majorité HNO (hors dimanche) de l'opération " . $ligneFacturation->getOperation()->getLibelle();

                    $error = 1;
                }
            }

            # Verification majorition pour ligne de facturation HNO
            if ($ligneFacturation->getIsHnoDimanche() == 1) {
                if ($ligneFacturation->getMajoriteHnoDimanche() == null || strlen((string)$ligneFacturation->getMajoriteHnoDimanche()) == 0) {
                    $message .= "- Majorité HNO (dimanche) de l'opération " . $ligneFacturation->getOperation()->getLibelle();

                    $error = 1;
                }
            }

            # Verification prix unitaire
            if ($ligneFacturation->getOperation()->getId() == 14 && $ligneFacturation->getPrixUnit() == null) {
                $message .= "- Prix Unitaire de l'opération " . $ligneFacturation->getOperation()->getLibelle();

                $error = 1;
            }

            # Verification champs obligatoire pour ligne de facturation de type à l'acte
            if (!in_array($ligneFacturation->getOperation()->getId(), $tabIdOperationAuto)){
                if (!empty($ligneFacturation->getUniqBdcFqOperation()))
                {
                    # Control ligne de facturation à l'acte
                    if(($ligneFacturation->getTypeFacturation()->getId() == 1))
                    {
                        if ($ligneFacturation->getIsHnoHorsDimanche() == null && $ligneFacturation->getIsHnoDimanche() == null) {
                            if(empty($ligneFacturation->getTarifHoraireCible()))
                            {
                                $message .= "- Tarif horaire cible de l'opération " . $ligneFacturation->getOperation()->getLibelle();

                                $error = 1;
                            }

                            if (empty($ligneFacturation->getTempsProductifs())){
                                {
                                    $message .= "- Temp productif de l'opération " . $ligneFacturation->getOperation()->getLibelle();

                                    $error = 1;
                                }
                            }

                            if (empty($ligneFacturation->getDmt())){
                                {
                                    $message .= "- Dmt de l'opération " . $ligneFacturation->getOperation()->getLibelle();

                                    $error = 1;
                                }
                            }
                        }
                    }

                    # Verification champs obligatoire pour ligne de facturation de type à l'heure et forfait
                    if(in_array($ligneFacturation->getTypeFacturation()->getId(), $this->getParameter('param_id_type_facte_heure_forfait')))
                    {
                        if ($ligneFacturation->getIsHnoHorsDimanche() == null && $ligneFacturation->getIsHnoDimanche() == null) {
                            if(empty($ligneFacturation->getNbHeureMensuel()))
                            {
                                $message .= "- Nombre d'heure mensuel de l'opération " . $ligneFacturation->getOperation()->getLibelle();

                                $error = 1;
                            }

                            if (empty($ligneFacturation->getNbEtp())){
                                {
                                    $message .= "- Nombre ETP de l'opération " . $ligneFacturation->getOperation()->getLibelle();

                                    $error = 1;
                                }
                            }
                        }
                    }
                }

                # Verification profil agent
                if(empty($ligneFacturation->getCoutHoraire()) && $ligneFacturation->getTypeFacturation()->getId() != 5 && !in_array($ligneFacturation->getOperation()->getId(), $this->getParameter('param_not_controlled_profilAgent_input')))
                {
                    $message .= "- Profil Agent de l'opération " . $ligneFacturation->getOperation()->getLibelle();

                    $error = 1;
                }
            }
        }

        if ($error) {
            return $this->json([
                'error' => 1,
                'message' => $message
            ], 200);
        } else {
            # Mise à jour bon de commande...............................................
            $bdcAdded = [];

            # Contient les uniqs id du nouveau bdc créer via les nouveau lignde de facturation
            $uniqIdBdcAddedViaNewLignFact = [];

            if (!empty($currentBdc)) {
                try {
                    $bdcArray = json_decode($request->getContent(), true);
                    $bdUpdated = $currentBdc->getBdcOperations();

                    $operationArray = $bdUpdated ?? [];

                    $newBdc = $this->saveBdcDupsAndNewVersion($currentBdc, $em, $request, $operationArray);
                    $createdBdc = $this->getDoctrine()->getRepository(Bdc::class)->find($newBdc->getId());
                    $lead->updateStatusLeadBdc($createdBdc->getId(), 19);
                    $lead->addWorkflowLead($createdBdc->getResumeLead()->getCustomer(), 19);
                    $this->setPdf($createdBdc, "client", "avenant", 1);

                    # Appel methode pour avoir nouvel statut et notification par email
                    list($objNotif, $twigNotif, $newStatut, $respNotif) = $this->getNewStatutAndNotification($currentBdc);

                    $allUsers = $userRepository->findAll();

                    $validators = [];
                    foreach ($allUsers as $user) {
                        $roles = $user->getRoles();
                        if (in_array($respNotif, $roles)) {
                            array_push($validators, $user);
                        }
                    }
                    #ETO ILAY SUPPRIMER
                    # Mise à jour bon de commande suite au refus Dir prod ou Dir fin ou DG ou simple modif par commerciale
                    # On duplique d'abord le bdc actuel, et ajout aussi le nouvelle operation s'il y en a
                    $filterLF = new FilterLigneFacturation();

                    /**
                     * Le premier contient la ligne de facturation avec prix unitaire modifié
                     * Le second contient la ligne de facturation à ajouter sur le bdc courant
                     * La troisieme contient les lignes de facturation qui ont besoin de creation d'un nouvelle bdc
                     */
                    list($lignFactEditedTarifForActualBdc, $lignFactToAddOnActualBdc, $lignFactToCreateBdc) = $filterLF->filterBdcOperationArray($currentBdc, $operationArray);

                    # Verification s'il y a une nouvelle operation ou modification tarif pour le bdc courrant
                    if (!empty($lignFactEditedTarifForActualBdc) || !empty($lignFactToAddOnActualBdc)) {
                        # Cas avenant : Modification tarif ou ajout nouvelle operation pour le bdc en production.
                        $newBdc = $this->saveBdcDupsAndNewVersion($currentBdc, $em, $request, $lignFactToAddOnActualBdc);

                        # Recupération du nouvel duplication bdc
                        $createdBdc = $this->getDoctrine()->getRepository(Bdc::class)->find($newBdc->getId());

                        # Mise à jour prix unitaire ou tarif de l'operation pour le cas modification tarif
                        $this->setTarifForUpdatedLignFact($createdBdc, $lignFactEditedTarifForActualBdc, $em);

                        # MAJ champ status lead dans la table Bdc
                        $lead->updateStatusLeadBdc($createdBdc->getId(), $newStatut);

                        # Ajout d'une ligne dans la table WorkflowLead
                        $lead->addWorkflowLead($createdBdc->getResumeLead()->getCustomer(), $newStatut);

                        # Generation d'un pdf pour le nouvel bdc
                        $this->setPdf($createdBdc, "client", "avenant", 1);

                        # Send mail au dir prod
                        if (count($validators) > 0){
                            foreach ($validators as $validator) {
                                if ($validator->getPaysProduction() != null && $validator->getPaysProduction()->getId() == $currentBdc->getPaysProduction()->getId()) {
                                    $sendMailTo->sendEmailViaTwigTemplate($currentUser->getEmail(), $validator->getEmail(), $objNotif, 'emailContent/' . $twigNotif, $currentUser, $createdBdc->getId());
                                }
                            }
                        }
                    }

                    # Verification si on a besoin de créer une nouvelle bon de commande
                    if (!empty($lignFactToCreateBdc)){
                        # Creation nouvelle bon de commande
                        $uniqIdBdcAddedViaNewLignFact = $this->createNewBdcOfAvenant($currentBdc, $lignFactToCreateBdc);
                    }


                    $nbBdcCreer = count($uniqIdBdcAddedViaNewLignFact);
                    $nbBdcAdded = count($bdcAdded);
                    if ($nbBdcAdded > 0) { # Cas multi-bdc
                        return $this->json([$currentBdc, $bdcAdded], 201, [], ['groups' => ['update-bdc', 'current-user']]);
                    } elseif ($nbBdcCreer > 0) { # Cas nouvelle creation bdc à partir de modification d'un bdc en production
                        return $this->json([$nbBdcCreer, $uniqIdBdcAddedViaNewLignFact, $currentBdc], 202, [], ['groups' => ['update-bdc', 'current-user']]);
                    } else { # Cas normal
                        return $this->json($currentBdc->getBdcOperations(), 200, [], ['groups' => ['update-bdc', 'current-user']]);
                    }
                } catch (\Exception $e) {
                    return $this->json([
                        'message' => $e->getMessage()
                    ], 500);
                }
            }
        }
    }

    /**
     * @Route("/bondeComande/commentaire/modfication/manuel", name="commentaireHausse", methods={"POST"})
     */
    public function commentaireModificationHausse(Request $request, HauseIndiceLignefacturationRepository $repoHausseLigne){
        $object=json_decode($request->getContent(), 'true');
        $idLigneFact= $object["idBdcO"];
        $commnetaire = $object["com"];
        $repoHausseLigne->setCommentHausseBdcO($idLigneFact,$commnetaire);
        return $this->json("Set Comme avec succes", 200, [], ['groups' => ['update-bdc', 'current-user']]);

    }
     /**
     * @Route("/bondeComande/modificationpdf/hausse/{idMere}/{langue}", name="modificationPdfContart", methods={"GET"})
     */
    public function modificationPdfContart($idMere,$langue,BdcRepository $bdcRepository,HauseIndiceLignefacturationRepository $repoHausseLigne,HausseIndiceSyntecClientRepository $repoHausseClient,BdcOperationRepository $repoBdcOperation): Response{
        $Titre = "Avenant au Devis";
        $arrayBdc=$bdcRepository->getBdcByIdMere($idMere);
        # Bdc ho ampiasaina
        $bdc=end($arrayBdc);
        $raisonSocialTitre=$bdc->getResumeLead()->getCustomer()->getRaisonSocial();
        $rms= $bdc->getResumeLead()->getCustomer()->getContacts()[0]->getNom() ." ". $bdc->getResumeLead()->getCustomer()->getContacts()[0]->getPrenom();
        $customer= $bdc->getResumeLead()->getCustomer();
        $hausseClient = $repoHausseClient->getByYearsCurrentByIdCustomer($customer->getId());
        $hausseLigne =$repoHausseLigne->getHausseBdcO($hausseClient[0]->getId());
        $hausseLigneDistinct = [];
        foreach($hausseLigne as $ligne){
            $hausseLigneDistinct[$ligne->getIdOperation()]=$ligne;
        }
        $BdcoperationHausseLibelle= [];
        $tempArray= [];
        $hausseReponse =[];
        foreach($hausseLigneDistinct as $IhausseLigneD){
            $bdcOperation= $repoBdcOperation->find($IhausseLigneD->getIdOperation());
            if(!in_array($bdcOperation->getOperation()->getLibelle(), $tempArray)){
                $BdcoperationHausseLibelle[$IhausseLigneD->getId()]=$bdcOperation->getOperation()->getLibelle();
                $tempArray [] = $bdcOperation->getOperation()->getLibelle();
                $hausseReponse [] =$IhausseLigneD;

            }
        }
        # varialble
        $datySign= $bdc->getDateSignature() ? $bdc->getDateSignature()->format('d/m/Y') :"";

        $istype = "IHCT-TS";
        if($hausseClient[0]->getIsType() == 1) $istype = "Syntec";
        else if ($hausseClient[0]->getIsType() == 2 ) $istype = "Smic";

        if ($langue == "FR"){
            #Debut
            $titrePdfComplet= strtoupper($Titre)."  DE PRESTATION DE SERVICES DEVIS SIGNE EN DATE DU ".$datySign;

            #1er P
            $societe = $bdc->getSocieteFacturation()->getLibelle() ?? $bdc->getSocieteFacturation()->getLibelle();
            $formjuridique = $bdc->getSocieteFacturation()->getFormeJuridique() ? $bdc->getSocieteFacturation()->getFormeJuridique() :"";
            $capital = $bdc->getSocieteFacturation()->getCapital() ? $bdc->getSocieteFacturation()->getCapital() :"";

            $premierP= " $societe , $formjuridique au capital de $capital , immatriculée au Registre du Commerce  et des Sociétés de ".$bdc->getSocieteFacturation()->getVille(). "sous le numéro  ".$bdc->getSocieteFacturation()->getIdentifiantFiscal().", dont le siège social est situé à ".$bdc->getSocieteFacturation()->getAdresse().", ".$bdc->getSocieteFacturation()->getVille()." , représentée par ".$bdc->getResumeLead()->getCustomer()->getUser()->getCurrentUsername().", en sa qualité de  ".$bdc->getResumeLead()->getCustomer()->getContacts()[0]->getFonction().", dûment habilité à l’effet des présentes,";


            $DexiemP= $bdc->getResumeLead()->getCustomer()->getRaisonSocial().", au capital social  de ".$bdc->getResumeLead()->getCustomer()->getCp()." ,dont le siège social est situé au ".$bdc->getResumeLead()->getCustomer()->getAdresse()." , immatriculée au Registre du Commerce ".$bdc->getSocieteFacturation()->getRegistreCommerce().
            " et des Sociétés de ".$bdc->getResumeLead()->getCustomer()->getVille()." sous le numéro ".$bdc->getSocieteFacturation()->getRegistreCommerce().", dont le siège social est situé à ".$bdc->getSocieteFacturation()->getAdresse().", ". $bdc->getSocieteFacturation()->getVille().
            " , représentée par ".$rms.", en sa qualité de  ".$bdc->getResumeLead()->getCustomer()->getCategorieClient()->getLibelle().", dûment habilité à l’effet des présentes,";


            $conclusionPage1 = "Le Prestataire et le ".$bdc->getResumeLead()->getCustomer()->getCategorieClient()->getLibelle()." étant individuellement désignés par une/la « Partie » et conjointement par les « Parties ».";

            $Titre1Page2 = "LES PARTIES ONT PREALABLEMENT EXPOSE CE QUI SUIT :";



            $Page2Parti1 = "Les Parties ont signé en date du ".$datySign.", un bon de commande avec les conditions générales de vente de ". $bdc->getSocieteFacturation()->getLibelle().", suivant lequel le ".$bdc->getResumeLead()->getCustomer()->getCategorieClient()->getLibelle()." met à disposition du
            Client une équipe dédiée composée de Développeurs Web confirmés (le « $istype »).";

            $Page2Parti2 = " Conformément à la section « Prix » des Conditions Générales de Vente, le montant des Prestations sera révisé chaque année suivant l’indice SYNTEC à la date d’anniversaire du contrat.";

            $Page2Parti3 = " Les Parties se sont donc rapprochées afin de définir dans le présent contrat (ci-après (le « $istype »)  les termes de leur accord.";


            $Titre2Page2 = "CECI ETANT EXPOSE, IL A ETE CONVENU CE QUI SUIT";

            $titreArticle1 = "Article 1 : Objet de l’Avenant.";

            $Page2Article1P1= " Le présent avenant a pour objet de mettre à jour la tarification des prestations conformément aux termes des Conditions Générales de Vente.";

            $Page2Article1P2 = " Toutes les dispositions contractuelles non expressément modifiées par les présentes demeurent en vigueur. En cas de contradiction avec celles des présentes, ces dernières prévaudront.  ";

            $titreArticle2 = "Article 2 : Modification de la section  « Tarification de Production » du Bon de Commande";

            $Article2p1 = "Par application de la formule suivante :P1 = P0 x (S1/S0)";

            $Article2p2 = " P1 = Prix révisé";

            $Article2p3 =" P0 = Prix d’origine";

            $dateContrat = $hausseClient[0]->getDateContrat() ? $hausseClient[0]->getDateContrat()->format('d/m/Y') : "";

            // $dateApp = $hausseClient[0]->getDateAplicatif() ? $hausseClient[0]->getDateAplicatif()->format('d/m/Y') : "";

            $Article2p4 ="S0 = Indice SYNTEC de référence retenue à la date contractuelle d’origine = $dateContrat : ". $hausseClient[0]->getInitial();

            $Article2p5 = " S1 = Indice du SYNTEC publié à la date de la révision = $dateContrat : ".$hausseClient[0]->getInitial();

            $Article2p6 = " La tarification des Prestations applicable à compter du ".$hausseClient[0]->getDateAplicatif()." sera comme suit :";
        }
        else{
            #Debut
            $titrePdfComplet= strtoupper($Titre)." PROVISION OF SERVICES PURCHASE ORDER SIGNED DATED ".$datySign;
                    

            #1er P
            $societe = $bdc->getSocieteFacturation()->getLibelle() ?? $bdc->getSocieteFacturation()->getLibelle();
            $formjuridique = $bdc->getSocieteFacturation()->getFormeJuridique() ? $bdc->getSocieteFacturation()->getFormeJuridique() :"";
            $capital = $bdc->getSocieteFacturation()->getCapital() ? $bdc->getSocieteFacturation()->getCapital() :"";

            $premierP= " $societe , $formjuridique to the capital of $capital ,registered in the Trade and Companies Register of ".$bdc->getSocieteFacturation()->getVille(). " under the number  ".$bdc->getSocieteFacturation()->getIdentifiantFiscal().", whose head office is located at ".$bdc->getSocieteFacturation()->getAdresse().", ".$bdc->getSocieteFacturation()->getVille()." , represented by ".$bdc->getResumeLead()->getCustomer()->getUser()->getCurrentUsername().", in his quality of  ".$bdc->getResumeLead()->getCustomer()->getContacts()[0]->getFonction().", duly authorized for the purpose of these,";


            $DexiemP= $bdc->getResumeLead()->getCustomer()->getRaisonSocial().", to the share capital of ".$bdc->getResumeLead()->getCustomer()->getCp()." 
            , whose head office is located at ".$bdc->getResumeLead()->getCustomer()->getAdresse()." , registered in the Commercial Register ".$bdc->getSocieteFacturation()->getRegistreCommerce().
            " and companies of ".$bdc->getResumeLead()->getCustomer()->getVille()." under the number ".$bdc->getSocieteFacturation()->getRegistreCommerce().",whose head office is located at ".$bdc->getSocieteFacturation()->getAdresse().", ". $bdc->getSocieteFacturation()->getVille().
            " , represented by ".$rms.", in his quality of  ".$bdc->getResumeLead()->getCustomer()->getCategorieClient()->getLibelle().", duly authorized for the purpose of these,";


            $conclusionPage1 = "The Provider and the ".$bdc->getResumeLead()->getCustomer()->getCategorieClient()->getLibelle()." being individually appointed by a/the “Party” and jointly by the <<Parties>> .";

            $Titre1Page2 = "THE PARTIES HAVE PREVIOUSLY DISCLOSED THE FOLLOWING:";



            $Page2Parti1 = "The Parties have signed dated ".$datySign.", an order form with the general conditions of sale of ". $bdc->getSocieteFacturation()->getLibelle().", according to which the ".$bdc->getResumeLead()->getCustomer()->getCategorieClient()->getLibelle()." provides the Customer with a dedicated team made up of confirmed Web Developers (the « $istype »).";

            $Page2Parti2 = "In accordance with the “Price” section of the General Conditions of Sale, the amount of the Services will be revised each year according to the SYNTEC index on the anniversary date of the contract..";

            $Page2Parti3 = " The Parties have therefore come together in order to define in this contract (hereinafter (the << $istype ») the terms of their agreement.";


            $Titre2Page2 = "THIS BEING PRESENTED, IT HAS BEEN AGREED AS FOLLOWS";

            $titreArticle1 = "Article 1: Purpose of the Amendment.";

            $Page2Article1P1= "The purpose of this amendment is to update the pricing of the services in accordance with the terms of the General Conditions of Sale.";

            $Page2Article1P2 = "All contractual provisions not expressly modified herein remain in force. In case of contradiction with those of the present, the latter will prevail. ";

            $titreArticle2 = "Article 2: Modification of the <<Production Pricing>> section of the Purchase Order";

            $Article2p1 = " By application of the following formula: P1 = P0 x (S1/S0)";

            $Article2p2 = " P1 = Revised price";

            $Article2p3 =" P0 = Original price";

            $dateContrat = $hausseClient[0]->getDateContrat() ? $hausseClient[0]->getDateContrat()->format('d/m/Y') : "";

            // $dateApp = $hausseClient[0]->getDateAplicatif() ? $hausseClient[0]->getDateAplicatif()->format('d/m/Y') : "";

            $Article2p4 ="S0 = Reference SYNTEC index retained on the original contractual date = = $dateContrat : ". $hausseClient[0]->getInitial();

            $Article2p5 = " S1 = SYNTEC index published on the date of the revision = $dateContrat : ".$hausseClient[0]->getInitial();

            $Article2p6 = " The pricing of the Services applicable from ".$hausseClient[0]->getDateAplicatif()." will be as follows:";
        }

        #Eto Manomboka

        
        return $this->json([
            'titrePdfComplet' => $titrePdfComplet,
            'premierP' => $premierP,
            'DexiemP' => $DexiemP,
            'conclusionPage1' => $conclusionPage1,
            'Titre1Page2' => $Titre1Page2,
            'Page2Parti1' => $Page2Parti1,
            'Page2Parti2' => $Page2Parti2,
            'Page2Parti3' => $Page2Parti3,
            'Titre2Page2' => $Titre2Page2,
            'titreArticle1' => $titreArticle1,
            'Page2Article1P1' => $Page2Article1P1,
            'Page2Article1P2' => $Page2Article1P2,
            'titreArticle2' => $titreArticle2,
            'CathegorieClient' => $bdc->getResumeLead()->getCustomer()->getCategorieClient()->getLibelle(),
            'Article2p1' => $Article2p1,
            'Article2p2' => $Article2p2,
            'Article2p3' => $Article2p3,
            'Article2p4' => $Article2p4,
            'Article2p5' => $Article2p5,
            'Article2p6' => $Article2p6,
            'MrsRepresente' => $rms,
            'Fonction' => $bdc->getResumeLead()->getCustomer()->getContacts()[0]->getFonction()
        ], 200, [], ['groups' => ['update-bdc', 'current-user']]);
    }
      /**
     * @Route("/bondeComande/creation/pdf/modifier/{idMere}", name="creationPdfContratModifier", methods={"POST"})
     */
    public function creationPdfContratModifier($idMere,Request $request,BdcRepository $bdcRepository,HauseIndiceLignefacturationRepository $repoHausseLigne,HausseIndiceSyntecClientRepository $repoHausseClient,BdcOperationRepository $repoBdcOperation){
        $object=json_decode($request->getContent(), 'true');
        $arrayBdc=$bdcRepository->getBdcByIdMere($idMere);
        # Bdc ho ampiasaina
        $bdc=end($arrayBdc);
        $raisonSocialTitre=$bdc->getResumeLead()->getCustomer()->getRaisonSocial();
        $rms= $bdc->getResumeLead()->getCustomer()->getContacts()[0]->getNom() ." ". $bdc->getResumeLead()->getCustomer()->getContacts()[0]->getPrenom();
        $customer= $bdc->getResumeLead()->getCustomer();
        $hausseClient = $repoHausseClient->getByYearsCurrentByIdCustomer($customer->getId());
        $hausseLigne =$repoHausseLigne->getHausseBdcO($hausseClient[0]->getId());
        $hausseLigneDistinct = [];
        foreach($hausseLigne as $ligne){
            $hausseLigneDistinct[$ligne->getIdOperation()]=$ligne;
        }
        $BdcoperationHausseLibelle= [];
        $tempArray= [];
        $hausseReponse =[];
        foreach($hausseLigneDistinct as $IhausseLigneD){
            $bdcOperation= $repoBdcOperation->find($IhausseLigneD->getIdOperation());
            if(!in_array($bdcOperation->getOperation()->getLibelle(), $tempArray)){
                $BdcoperationHausseLibelle[$IhausseLigneD->getId()]=$bdcOperation->getOperation()->getLibelle();
                $tempArray [] = $bdcOperation->getOperation()->getLibelle();
                $hausseReponse [] =$IhausseLigneD;

            }
        }
        $html = $this->renderView('ContartModifier.html.twig', [
            'titrePdfComplet' => $object["titrePdfComplet"],
            'premierP' => $object["premierP"],
            'DexiemP' => $object["DexiemP"],
            'conclusionPage1' => $object["conclusionPage1"],
            'Titre1Page2' => $object["Titre1Page2"],
            'Page2Parti1' => $object["Page2Parti1"],
            'Page2Parti2' => $object["Page2Parti2"],
            'Page2Parti3' => $object["Page2Parti3"],
            'Titre2Page2' => $object["Titre2Page2"],
            'titreArticle1' => $object["titreArticle1"],
            'Page2Article1P1' => $object["Page2Article1P1"],
            'Page2Article1P2' => $object["Page2Article1P2"],
            'titreArticle2' => $object["titreArticle2"],
            'CathegorieClient' => $bdc->getResumeLead()->getCustomer()->getCategorieClient()->getLibelle(),
            'Article2p1' => $object["Article2p1"],
            'Article2p2' => $object["Article2p2"],
            'Article2p3' => $object["Article2p3"],
            'Article2p4' => $object["Article2p4"],
            'Article2p5' => $object["Article2p5"],
            'Article2p6' => $object["Article2p6"],
            'MrsRepresente' => $rms,
            'Fonction' => $bdc->getResumeLead()->getCustomer()->getContacts()[0]->getFonction(),
            'hauseBdcO' => $hausseReponse,
            'BdcoperationHausseLibelle' => $BdcoperationHausseLibelle,
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
        file_put_contents($this->getParameter('bdc_dir') .$raisonSocialTitre. ' Avenant Revision taifaire.pdf', $output);
        return $this->json("OKOK", 200, [], []);
    }
    /**
     * @Route("/bondeComande/update/hausse", name="updateHausse22", methods={"POST"})
     */
    public function updateHausse(Request $request,Lead $lead,HausseIndiceSyntecClientRepository $repoSyntecclient,HauseIndiceLignefacturationRepository $repoLigne,BonDeCommandeController $bdcControlleur, EntityManagerInterface $em,HauseIndiceLignefacturationRepository $repoHausseLigne,BdcOperationRepository $repoBdc,OperationRepository $repoOp,ContratRepository $repoContrat): Response{
        $object=json_decode($request->getContent(), 'true');
        $actuel = $object["actuel"];
        $ligne = $object["ligne"];
        $taux =  $object["taux"];
        $clause = $object["clause"];
        $dateContart = $object["dateContart"];
        $idCustomer = $object["idCustomer"];
        $initial = $object["initial"];
        $isType = $object["isType"];
        $bdcArrayParIdMere = $object["bdcParIdmere"];
        $raisonSocial = $object["raisonSocial"];
        //dd($raisonSocial);
        $valide = $object["valide"];
        $DateAplicatif = $object["dateAplicatif"];
        $bdcOModifyManuel = $object["arrayBdcOModify"];
        $commentaire = $object["commantaire"];
        $arrayBdcModifManuel = [];
        $arrayIdBdcO =[];
        #manala null sy maka ny version farany isaky ny modification
        foreach($bdcOModifyManuel as $bdcManuel){
            if($bdcManuel){
                $arrayBdcModifManuel[$bdcManuel["idBdcO"]]=$bdcManuel;
                $arrayIdBdcO[$bdcManuel["idBdcO"]]=$bdcManuel["idBdcO"];
            }
        }
        $HausseClient = new HausseIndiceSyntecClient();
        $HausseClient->setActuel($actuel);
        $HausseClient->setCommentaire($commentaire);
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
        $HausseClient->setDateAplicatif($DateAplicatif);
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
        # Raha mbola tsisy client de Ajout raha efa misy de Update
        if($repoSyntecclient->CheckCustomerAJourOrNote($HausseClient) === []){
            #dd("tsisy"); Tsisy
            $repoSyntecclient->add($HausseClient);
        }
        else{
            #dd("misy"); Misy
            $repoSyntecclient->UpdateHausseIndiceSyntecClient($HausseClient);
        }

        # Maka Ny Hausse Indice Client Isatona .tona = Year Current
        $SyntecClient=$repoSyntecclient->GetByCustomerYears($idCustomer);
        $tab =array();
        $bdcOperationPourChaqueBDC = [];
        $tabtemp = [];
        //dd($arrayBdcModifManuel);
        # Ajout Ligne de Facturation Du Hausse BDC Par BDC by ID_mere
        foreach ($bdcArrayParIdMere as $bdcParIdMere){
            $tabtemp = [];
            foreach($bdcParIdMere["bdcOpe"] as $l){
                $HausseBdcO = new HauseIndiceLignefacturation();
                $HausseBdcO->setIdOperation($l["idBdcO"]);
                $prixfarany= ($taux+100)*$l["PrixUnitaire"]/100;
                if(str_contains($l["operationLabel"],"à l'Heure")){
                    $HausseBdcO->setAncienPrixHeure($l["PrixUnitaire"]);
                    $HausseBdcO->setNouveauPrixHeure($prixfarany);
                    if(in_array($l["idBdcO"],$arrayIdBdcO)){
                        $HausseBdcO->setNouveauPrixHeure($arrayBdcModifManuel[$l["idBdcO"]]["PrixUnitaire"]);
                    }
                }
                else if(str_contains($l["operationLabel"],"à l'Acte")){
                    //dd($l["operationLabel"]);
                    $HausseBdcO->setAncienPrixActe($l["PrixUnitaire"]);
                    $HausseBdcO->setNouveauPrixActe($prixfarany);
                    if(in_array($l["idBdcO"],$arrayIdBdcO)){
                        $HausseBdcO->setNouveauPrixActe($arrayBdcModifManuel[$l["idBdcO"]]["PrixUnitaire"]);
                    }
                }
                else{
                    $HausseBdcO->setAncienPrix($l["PrixUnitaire"]);
                    $HausseBdcO->setNouveauPrix($prixfarany);
                    if(in_array($l["idBdcO"],$arrayIdBdcO)){
                        $HausseBdcO->setNouveauPrix($arrayBdcModifManuel[$l["idBdcO"]]["PrixUnitaire"]);
                    }
                }
                if($SyntecClient)
                    $HausseBdcO->setHausseIndeceClientId($SyntecClient->getId());
                $repoLigne->add($HausseBdcO);
                array_push($tab,$HausseBdcO);
                $tabtemp[]= $HausseBdcO;
            }
            $bdcOperationPourChaqueBDC[$bdcParIdMere["idBdc"]]=$tabtemp;

        }
        $bdUpdated = $tab;
        $operationArray = $bdUpdated ?? [];
        $createdBdc= null;
        foreach($bdcArrayParIdMere as $bdcParIdMere){
            #Creation Nouveau BDC
            $newBdc = $this->saveBdcDupsAndNewVersion2($bdcParIdMere["idBdc"], $em, $request, $bdcOperationPourChaqueBDC[$bdcParIdMere["idBdc"]],$DateAplicatif);
            
            $createdBdc = $this->getDoctrine()->getRepository(Bdc::class)->find($newBdc->getId());
            $lead->updateStatusLeadBdc($createdBdc->getId(), $this->getParameter("statut_lead_bdc_avenant_signe_com"));
            $lead->addWorkflowLead($createdBdc->getResumeLead()->getCustomer(), $this->getParameter("statut_lead_bdc_avenant_signe_com"));

            # Bdc PDF
            $bdcControlleur->setPdf2($createdBdc, "client", "avenant", 1 ,$tab,$HausseClient->getDateAplicatif(),$repoOp,$repoBdc);
            # Contrat Francais
            
            $bdcControlleur->setPdfContart($createdBdc, $HausseClient,$tab,"FR",$repoBdc,$repoContrat);
             # Contrat Anglais
             $bdcControlleur->setPdfContart($createdBdc, $HausseClient,$tab,"UK",$repoBdc,$repoContrat);
        }
       
        return $this->json($createdBdc, 200, [], ['groups' => ['update-bdc', 'current-user']]);
    }

    /**
     * @Route("/bon/de/commande/{id}", name="bon_de_commande__update", methods={"PUT"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function update(int $id, Request $request, EntityManagerInterface $em, UserInterface $currentUser, UserRepository $userRepository, Lead $lead, SendMailTo $sendMailTo): Response
    {
        $currentBdc = $this->getDoctrine()->getRepository(Bdc::class)->find($id);

        # Necessaire pour send mail au validateur, mise à jour statut lead du client et workflow lead.......
        list($client, $user, $clientObj) = $this->getUserConnecte($currentBdc);

        # Controle champs obligatoires......................
        $ligneFacturations = $currentBdc->getBdcOperations();
        $error = 0;
        $message = "Merci de remplir les champs suivants : ";

        # Verification des champs obligatoires dans les lignes de facturatons
        $tabIdOperationAuto = $this->getParameter('param_not_required_profil_agent');
        foreach($ligneFacturations As $ligneFacturation)
        {
            # Verification majorition pour ligne de facturation HNO
            if ($ligneFacturation->getIsHnoHorsDimanche() == 1) {
                if ($ligneFacturation->getMajoriteHnoHorsDimanche() == null  || strlen((string)$ligneFacturation->getMajoriteHnoHorsDimanche()) == 0) {
                    $message .= "- Majorité HNO (hors dimanche) de l'opération " . $ligneFacturation->getOperation()->getLibelle();

                    $error = 1;
                }
            }

            # Verification majorition pour ligne de facturation HNO
            if ($ligneFacturation->getIsHnoDimanche() == 1) {
                if ($ligneFacturation->getMajoriteHnoDimanche() == null || strlen((string)$ligneFacturation->getMajoriteHnoDimanche()) == 0) {
                    $message .= "- Majorité HNO (dimanche) de l'opération " . $ligneFacturation->getOperation()->getLibelle();

                    $error = 1;
                }
            }

            # Verification prix unitaire
            if ($ligneFacturation->getOperation()->getId() == 14 && $ligneFacturation->getPrixUnit() == null) {
                $message .= "- Prix Unitaire de l'opération " . $ligneFacturation->getOperation()->getLibelle();

                $error = 1;
            }

            # Verification champs obligatoire pour ligne de facturation de type à l'acte
            if (!in_array($ligneFacturation->getOperation()->getId(), $tabIdOperationAuto)){
                if (!empty($ligneFacturation->getUniqBdcFqOperation()))
                {
                    # Control ligne de facturation à l'acte
                    if(($ligneFacturation->getTypeFacturation()->getId() == 1))
                    {
                        if ($ligneFacturation->getIsHnoHorsDimanche() == null && $ligneFacturation->getIsHnoDimanche() == null) {
                            if(empty($ligneFacturation->getTarifHoraireCible()))
                            {
                                $message .= "- Tarif horaire cible de l'opération " . $ligneFacturation->getOperation()->getLibelle();

                                $error = 1;
                            }

                            if (empty($ligneFacturation->getTempsProductifs())){
                                {
                                    $message .= "- Temp productif de l'opération " . $ligneFacturation->getOperation()->getLibelle();

                                    $error = 1;
                                }
                            }

                            if (empty($ligneFacturation->getDmt())){
                                {
                                    $message .= "- Dmt de l'opération " . $ligneFacturation->getOperation()->getLibelle();

                                    $error = 1;
                                }
                            }
                        }
                    }

                    # Verification champs obligatoire pour ligne de facturation de type à l'heure et forfait
                    if(in_array($ligneFacturation->getTypeFacturation()->getId(), $this->getParameter('param_id_type_facte_heure_forfait')))
                    {
                        if ($ligneFacturation->getIsHnoHorsDimanche() == null && $ligneFacturation->getIsHnoDimanche() == null) {
                            if(empty($ligneFacturation->getNbHeureMensuel()))
                            {
                                $message .= "- Nombre d'heure mensuel de l'opération " . $ligneFacturation->getOperation()->getLibelle();

                                $error = 1;
                            }

                            if (empty($ligneFacturation->getNbEtp())){
                                {
                                    $message .= "- Nombre ETP de l'opération " . $ligneFacturation->getOperation()->getLibelle();

                                    $error = 1;
                                }
                            }
                        }
                    }
                }

                # Verification profil agent
                if(empty($ligneFacturation->getCoutHoraire()) && $ligneFacturation->getTypeFacturation()->getId() != 5 && !in_array($ligneFacturation->getOperation()->getId(), $this->getParameter('param_not_controlled_profilAgent_input')))
                {
                    $message .= "- Profil Agent de l'opération " . $ligneFacturation->getOperation()->getLibelle();

                    $error = 1;
                }
            }
        }

        if ($error) {
            return $this->json([
                'error' => 1,
                'message' => $message
            ], 200);
        } else {
            # Mise à jour bon de commande...............................................

            # Contient les uniqs id du nouveau bdc créer via les nouveau lignde de facturation
            $idDevisAddedViaNewLignFact = null;

            if (!empty($currentBdc)) {
                try {
                    $bdcArray = json_decode($request->getContent(), true);
                    $operationArray = $bdcArray['bdcOperations'] ?? [];

                    # Appel methode pour avoir nouvel statut et notification par email
                    list($objNotif, $twigNotif, $newStatut, $respNotif) = $this->getNewStatutAndNotification($currentBdc);

                    $allUsers = $userRepository->findAll();

                    $validators = [];
                    foreach ($allUsers as $user) {
                        $roles = $user->getRoles();
                        if (in_array($respNotif, $roles)) {
                            array_push($validators, $user);
                        }
                    }

                    $statutLead = $currentBdc->getStatutLead();

                    # Mise à jour bon de commande suite au refus Dir prod ou Dir fin ou DG ou simple modif par commerciale
                    if(in_array($statutLead, $this->getParameter('statut_lead_workflow_reject'))) {
                        if (in_array($statutLead, $this->getParameter('statut_lead_avenant_rejeter'))) {
                            $avenant = "avenant";
                        } else {
                            $avenant = null;

							# Mise à jour information lié à la table bdc
							$this->updateInformationBdc($currentBdc, $bdcArray, $em, null);
                        }

                        # Tout d'abord, s'il y a une nouvelle ligne de facturation, on ajoute
                        if (sizeof($operationArray) > sizeof($currentBdc->getBdcOperations())) {
                            $this->createNewLignFactAndLeadDetailOperation($currentBdc, $operationArray);
                        }

                        #Notification validateur si current BDC a été Rejeté
                        if($statutLead != $this->getParameter('statut_lead_bdc_draft'))
                        {
                            if (count($validators) > 0){
                                foreach ($validators as $validator) {
                                    if ($respNotif == "ROLE_DIRPROD") {
                                        if ($validator->getPaysProduction() != null && $validator->getPaysProduction()->getId() == $currentBdc->getPaysProduction()->getId()) {
                                            $sendMailTo->sendEmailViaTwigTemplate($currentUser->getEmail(), $validator->getEmail(), $objNotif, 'emailContent/' . $twigNotif, $currentUser, $id);
                                        }
                                    } else {
                                        $sendMailTo->sendEmailViaTwigTemplate($currentUser->getEmail(), $validator->getEmail(), $objNotif, 'emailContent/' . $twigNotif, $currentUser, $id);
                                    }
                                }
                            }
                        }

                        # MAJ champ status lead dans la table Bdc
                        $lead->updateStatusLeadBdc($id, $newStatut);

                        # Ajout ou MAJ statut client dans la table StatutLead
                        $lead->updateStatusLeadByCustomer($clientObj, $newStatut);

						# Generation d'un pdf pour le bdc
                        $this->setPdf($this->getDoctrine()->getRepository(Bdc::class)->find($currentBdc->getId()), "client", $avenant);

                    } elseif (in_array($statutLead, $this->getParameter("statut_lead_signed_by_commercial"))) {
                        # Cas demande de modification par le client.
                        /**
                         * Le premier contient la ligne de facturation avec prix unitaire modifié
                         * Le second contient la ligne de facturation à ajouter sur le bdc courant
                         */

                        $filterLF = new FilterLigneFacturation();

                        list($lignFactEditedTarifForActualBdc, $lignFactToAddOnActualBdc) = $filterLF->filterBdcOperationArrayForBdcSignedByCom($operationArray);

                        # Verification s'il y a une nouvelle operation ou modification tarif pour le bdc courrant
                        if (!empty($lignFactEditedTarifForActualBdc) || !empty($lignFactToAddOnActualBdc)) {
                            # Creation du duplication : Modification tarif ou ajout nouvelle operation pour le bdc.
                            $newBdc = $this->saveBdcDupsAndNewVersion($currentBdc, $em, $request, $lignFactToAddOnActualBdc);

                            # Recupération du nouvel duplication bdc
                            $createdBdc = $this->getDoctrine()->getRepository(Bdc::class)->find($newBdc->getId());

                            # Mise à jour prix unitaire ou tarif de l'operation pour le cas modification tarif
                            $this->setTarifForUpdatedLignFact($createdBdc, $lignFactEditedTarifForActualBdc, $em);

                            # MAJ champ status lead dans la table Bdc
                            $lead->updateStatusLeadBdc($createdBdc->getId(), $newStatut);

                            # Ajout d'une ligne dans la table WorkflowLead
                            $lead->addWorkflowLead($createdBdc->getResumeLead()->getCustomer(), $newStatut);

                            # Generation d'un pdf pour le nouvel bdc
                            $valAvenant = null;

                            if ($statutLead == $this->getParameter('statut_lead_bdc_avenant_signe_com')) {
                                $valAvenant = "avenant";
                            }

                            $this->setPdf($createdBdc, "client", $valAvenant, 1);

                            # Send mail au dir prod
                            if (count($validators) > 0){
                                foreach ($validators as $validator) {
                                    if ($validator->getPaysProduction() != null && $validator->getPaysProduction()->getId() == $currentBdc->getPaysProduction()->getId()) {
                                        $sendMailTo->sendEmailViaTwigTemplate($currentUser->getEmail(), $validator->getEmail(), $objNotif, 'emailContent/' . $twigNotif, $currentUser, $createdBdc->getId());
                                    }
                                }
                            }
                        }
                    } elseif (in_array($statutLead, $this->getParameter("statut_lead_bdc_in_prod"))) {
                        # On duplique d'abord le bdc actuel, et ajout aussi le nouvelle operation s'il y en a
                        $filterLF = new FilterLigneFacturation();

                        /**
                         * Le premier contient la ligne de facturation avec prix unitaire modifié
                         * Le second contient la ligne de facturation à ajouter sur le bdc courant
                         * La troisieme contient les lignes de facturation qui ont besoin de creation d'un nouvelle bdc
                         */
                        list($lignFactEditedTarifForActualBdc, $lignFactToAddOnActualBdc, $lignFactToCreateBdc) = $filterLF->filterBdcOperationArray($currentBdc, $operationArray);

                        # Verification s'il y a une nouvelle operation ou modification tarif pour le bdc courrant
                        if (!empty($lignFactEditedTarifForActualBdc) || !empty($lignFactToAddOnActualBdc)) {
                            # Cas avenant : Modification tarif ou ajout nouvelle operation pour le bdc en production.
                            $newBdc = $this->saveBdcDupsAndNewVersion($currentBdc, $em, $request, $lignFactToAddOnActualBdc);

                            # Recupération du nouvel duplication bdc
                            $createdBdc = $this->getDoctrine()->getRepository(Bdc::class)->find($newBdc->getId());

                            # Mise à jour prix unitaire ou tarif de l'operation pour le cas modification tarif
                            $this->setTarifForUpdatedLignFact($createdBdc, $lignFactEditedTarifForActualBdc, $em);

                            # MAJ champ status lead dans la table Bdc
                            $lead->updateStatusLeadBdc($createdBdc->getId(), $newStatut);

                            # Ajout d'une ligne dans la table WorkflowLead
                            $lead->addWorkflowLead($createdBdc->getResumeLead()->getCustomer(), $newStatut);

                            # Generation d'un pdf pour le nouvel bdc
                            $this->setPdf($createdBdc, "client", "avenant", 1);

                            # Send mail au dir prod
                            if (count($validators) > 0){
                                foreach ($validators as $validator) {
                                    if ($validator->getPaysProduction() != null && $validator->getPaysProduction()->getId() == $currentBdc->getPaysProduction()->getId()) {
                                        $sendMailTo->sendEmailViaTwigTemplate($currentUser->getEmail(), $validator->getEmail(), $objNotif, 'emailContent/' . $twigNotif, $currentUser, $createdBdc->getId());
                                    }
                                }
                            }
                        }

                        # Verification si on a besoin de créer une nouvelle bon de commande
                        if (!empty($lignFactToCreateBdc)){
                            # Creation nouvelle bon de commande
                            $idDevisAddedViaNewLignFact = $this->createNewBdcOfAvenant($currentBdc, $lignFactToCreateBdc);
                        }
                    } else {
                        # Mise à jour bon de commande en mode creation vente.........
                        # Mise à jour bon de commande en brouillon.........
                        $this->updateInformationBdc($currentBdc, $bdcArray, $em, $id);

                        # Marge cible
                        $currentBdc->setMargeCible($this->getMargeCible($currentBdc));

                        # Definition suite du process
                        $suiteProcess = new SuiteProcess();

                        $suiteProcess->setBdc($currentBdc)
                            ->setIsCustomerWillSendBdc($bdcArray["isCustomerWillSendBdc"] ?? 0)
                            ->setIsSeizureContract($bdcArray["isSeizureContract"] ?? 0)
                            ->setIsDevisPassToProdAfterSign($bdcArray["isDevisPassToProdAfterSign"] ?? 0);

                        $em->persist($suiteProcess);

                        # Tout d'abord, s'il y a une nouvelle ligne de facturation, on ajoute
                        if (sizeof($operationArray) > sizeof($currentBdc->getBdcOperations())) {
                            $this->createNewLignFactAndLeadDetailOperation($currentBdc, $operationArray);
                        }

                        # MAJ champ status lead dans la table Bdc
                        $lead->updateStatusLeadBdc($currentBdc->getId(), $newStatut);

                        /*
                         * Ajouter une ligne dans la table workflow_lead, avec comme statut = 3, date = date courant
                         * Envoie un email de notification au N+1 du commercial ou gestionnaire de compte
                         */
                        # Ajout ou MAJ statut client dans la table StatutLead
                        $lead->updateStatusLeadByCustomer($clientObj, $newStatut);

                        # Ajout d'une ligne dans la table WorkflowLead
                        $lead->addWorkflowLead($clientObj, $newStatut);

                        $em->persist($currentBdc);
                        $em->flush();

                        $this->setPdf($currentBdc, "client", null);
						
                    }

                    return $this->json(['idOfAllCreatedDevis' => $idDevisAddedViaNewLignFact], 200, [], ['groups' => 'post:read']);
                } catch (\Exception $e) {
                    return $this->json([
                        'message' => $e->getMessage()
                    ], 500);
                }
            }
        }
    }

    ######################## Après avoir cliqué sur le bouton Status lead qui a pour statut lead egal 3, Ce fonction renvoie une notification au N+1 ##########################

    /**
     * @Route("/send/notification/to/superior/{lead}/{idcustomer}", name="send_mail_to_superior", methods={"GET"})
     * @param int $idcustomer
     * @param CustomerRepository $customerRepository
     * @param BdcRepository $bdcRepository
     * @param ResumeLeadRepository $resumeLeadRepository
     * @param SendMailTo $sendMailTo
     * @param Lead $lead
     * @param UserInterface $currentUser
     * @return Response
     */
    public function SendNotificationToSuperior(int $idcustomer, CustomerRepository $customerRepository, BdcRepository $bdcRepository, ResumeLeadRepository $resumeLeadRepository, SendMailTo $sendMailTo, Lead $lead, UserInterface $currentUser): Response
    {
        try {
            $customer = $customerRepository->find($idcustomer);

            $resumeLead = $resumeLeadRepository->findOneBy(
                ['customer' => $customer]
            );

            $bdc = $bdcRepository->findOneBy(
                ['resumeLead' => $resumeLead]
            );

            # MAJ champ status lead dans la table Bdc
            $lead->updateStatusLeadBdc($bdc->getId(),intval($this->getParameter('statut_lead_bdc_creer')));

            # Ajout ou MAJ statut client dans la table StatutLead
            $lead->updateStatusLeadByCustomer($customer, intval($this->getParameter('statut_lead_bdc_creer')));

            # Ajout d'une ligne dans la table WorkflowLead
            $lead->addWorkflowLead($customer, intval($this->getParameter('statut_lead_bdc_creer')));

            if ($customer){
                $user = $customer->getUser();
                if (!empty($user->getParent())) {

                    $parent = $user->getParent();

                    $sendMailTo->sendEmailViaTwigTemplate($user->getEmail(), $parent->getEmail(), 'Bon De Commande à valider', 'emailContent/forValidationSuperior.html.twig', $currentUser, $bdc->getId());
                }
                return $this->json('Notification envoyé avec succès ...', 200, [], ['groups' => ['update-bdc']]);
            }

        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    ///////////////Après avoir cliqué sur le bouton Status lead qui a pour statut lead egal 4, Ce fonction renvoie une notification au N-1 ////////////////
    /**
     * @Route("/send/notification/to/customer/{lead}/{idcustomer}", name="send_mail_to_customer", methods={"GET"})
     * @param int $lead
     * @param int $idcustomer
     * @return Response
     */
    public function SendNotificationToCustomer(int $idcustomer, CustomerRepository $customerRepository, BdcRepository $bdcRepository, ResumeLeadRepository $resumeLeadRepository, SendMailTo $sendMailTo, Lead $lead): Response
    {
        try {
            $customer = $customerRepository->find($idcustomer);

            $resumeLead = $resumeLeadRepository->findOneBy(
                ['customer' => $customer]
            );

            $bdc = $bdcRepository->findOneBy(
                ['resumeLead' => $resumeLead]
            );

            # MAJ champ status lead dans la table Bdc
            $lead->updateStatusLeadBdc($bdc->getId(),intval($this->getParameter('statut_lead_bdc_valider_dir_prod')));

            # Ajout ou MAJ statut client dans la table StatutLead
            $lead->updateStatusLeadByCustomer($customer, intval($this->getParameter('statut_lead_bdc_valider_dir_prod')));

            # Ajout d'une ligne dans la table WorkflowLead
            $lead->addWorkflowLead($customer, intval($this->getParameter('statut_lead_bdc_valider_dir_prod')));

            if ($customer){
                $user = $customer->getUser();
                $parent = $user->getParent();
                if (!empty($user->getParent())) {
                    # Envoie du bon de commande au client
                    /*$msg = 'Bonjour,<br/><br/>';
                    $msg .= 'Merci d\'envoyer le Bon De Commande numéro ' . $this->addValueToNumBdc($bdc->getId()) . ' au client.<br/>';*/

                    $sendMailTo->sendEmail($parent->getEmail(), $user->getEmail(), 'Bon de commande n° ' . $bdc->getNumBdc() . ' validé', 'emailContent/commercialeNotif.html.twig', $bdc->getId());
                }

                return $this->json('Notification envoyé avec succès ...', 200, [], ['groups' => ['update-bdc']]);

            }
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/imported/file/{id}", name="imported_file", methods={"POST"})
     * @param int $id
     * @return Response
     */
    public function importedFile(int $id, Request $request, BdcRepository $bdcRepository, Lead $lead): Response
    {

        $data = json_decode($request->getContent(), 'true');

        if ($data['fil'] != null ) {
            try {
                $stringPdf = str_replace('data:application/pdf;base64,', '', $data['fil']);
                $openFile = fopen($this->getParameter('bdc_dir') . 'bdc_' . $id . '.pdf',"w");
                fwrite($openFile, base64_decode($stringPdf));
                fclose($openFile);

                $bdc = $bdcRepository->find($id);

                if ($bdc) {
                    $customer = $bdc->getResumeLead()->getCustomer();

                    # Donner une date au champ dateSignature du table Bdc
                    $bdc->setDateSignature(new \DateTime());

                    # MAJ champ status lead dans la table Bdc
                    $lead->updateStatusLeadBdc($bdc->getId(),intval($this->getParameter('statut_lead_bdc_rejeter_dir_prod')));

                    # Ajout ou MAJ statut client dans la table StatutLead
                    $lead->updateStatusLeadByCustomer($customer, intval($this->getParameter('statut_lead_bdc_rejeter_dir_prod')));

                    # Ajout d'une ligne dans la table WorkflowLead
                    $lead->addWorkflowLead($customer, intval($this->getParameter('statut_lead_bdc_rejeter_dir_prod')));

                    return $this->json('Bon de commande importé', 200, [], ['groups' => ['view']]);
                }
            } catch (\Exception $e) {
                return $this->json([
                    "status" => 500,
                    "message" => $e->getMessage()
                ], 500);
            }
        } else {
            return $this->json('Erreur', 200, [], ['groups' => ['view']]);
        }
    }

    /**
     * @Route ("/save/bdc/document", name="save_bdc_doc", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * Import document bdc.........................
     */
    public function saveBdcDocument(Request $request, EntityManagerInterface $entityManager): Response {
        try {
            $jsonRecu = json_decode($request->getContent(), true);
            $doc = new BdcDocument();

            if (isset($jsonRecu)) {
                $doc->setDateSignature(isset($jsonRecu['dateSignature']) ? (\DateTime::createFromFormat('Y-m-d', $jsonRecu['dateSignature'])) : null);
                $doc->setDateDebutPriseCompte(isset($jsonRecu['dateDebutPriseCompte']) ? (\DateTime::createFromFormat('Y-m-d', $jsonRecu['dateDebutPriseCompte'])) : null);
                $doc->setDateFinPriseCompte(isset($jsonRecu['dateFinPriseCompte']) ? (\DateTime::createFromFormat('Y-m-d', $jsonRecu['dateFinPriseCompte'])) : null);
                $doc->setTypeDocument($this->getDoctrine()->getRepository(TypeDocument::class)->find($jsonRecu['type'] ?? null));
                $doc->setBdc($this->getDoctrine()->getRepository(Bdc::class)->find($jsonRecu['choosedBdc']));

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

    /////////////// Notification service financier ////////////////
    /**
     * @Route("/send/notification/to/financial/{lead}/{idcustomer}", name="send_mail_to_finance", methods={"GET"})
     * @param int $lead
     * @param int $idcustomer
     * @return Response
     */
    public function SendNotificationToFinancial(int $idcustomer, CustomerRepository $customerRepository, BdcRepository $bdcRepository, ResumeLeadRepository $resumeLeadRepository, UserRepository $userRepository, Lead $lead, SendMailTo $sendMailTo): Response
    {
        try {
            $customer = $customerRepository->find($idcustomer);

            $resumeLead = $resumeLeadRepository->findOneBy(
                ['customer' => $customer]
            );

            $bdc = $bdcRepository->findOneBy(
                ['resumeLead' => $resumeLead]
            );

            # MAJ champ status lead dans la table Bdc
            $lead->updateStatusLeadBdc($bdc->getId(),intval($this->getParameter('statut_lead_bdc_rejeter_dir_prod')));

            # Ajout ou MAJ statut client dans la table StatutLead
            $lead->updateStatusLeadByCustomer($customer, intval($this->getParameter('statut_lead_bdc_rejeter_dir_prod')));

            # Ajout d'une ligne dans la table WorkflowLead
            $lead->addWorkflowLead($customer, intval($this->getParameter('statut_lead_bdc_rejeter_dir_prod')));

            /*
            * Code pour notifier le service financier
            */
            if ($customer){
                $user = $customer->getUser();
                $financialeService = $userRepository->findOneBy(
                    ['roles' => "ROLE_FINANCE"]
                );

                # Envoie du bon de commande au client
                $msg = 'Bonjour,<br/><br/>';
                $msg .= 'Merci de valider le Bon De Commande numéro ' . $bdc->getNumBdc() . ' SVP.<br/>';

                $sendMailTo->sendEmail($user->getEmail(), $financialeService->getEmail(), 'Bon De Commande numéro ' . $bdc->getNumBdc() . ' à valider par le Service Financier', $msg, null);

                return $this->json('Notification envoyé avec succès ...', 200, [], ['groups' => ['update-bdc']]);
            }
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/check/irm/value/{id}", name="check_irm_value", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function checkIrm(int $id, BdcRepository $bdcRepository): Response
    {
        try {
            $results = $bdcRepository->find($id);

            $bdcOperations = $results->getBdcOperations();

            $tabIrm = array();

            foreach ($bdcOperations as $bdcOperation) {
                if ($bdcOperation->getIrm() == 1 || $bdcOperation->getIrm() == true) {
                    array_push($tabIrm, $bdcOperation->getId());
                }
            }
            return $this->json($tabIrm,200, [], ['groups' => ['get-by-bdc']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/check/marge/cible/{id}", name="check_marge_cible", methods={"GET"})
     * @return Response
     */
    public function checkMargeCible(Bdc $bdc): Response
    {
        //Verifier si les lignes de facturations sont tous paramétrés
        $isLignFactParametre = true;

        foreach($bdc->getBdcOperations() As $bdcOperation)
        {
            if (!in_array($bdcOperation->getOperation()->getId(),$this->getParameter('param_id_operation_not_in_lign_fact_list'))){
                if(!$bdcOperation->getIsParamPerformed())
                {
                    $isLignFactParametre = false;
                    break;
                }
            }
        }

        if(!$isLignFactParametre)
        {
            return $this->json("Merci de paramétrés SVP tous les lignes de facturations !", 200, []);
        }

        try {
            $margeCible = $this->getMargeCible($bdc);

            $res = ($margeCible * 100) > $this->getParameter('seuilMargeCible') ? 1 : $this->getParameter('seuilMargeCible');

            return $this->json($res,200, [], ['groups' => ['update']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/inject/data/to/irm/{idBdc}", name="inject_data", methods={"GET"})
     * @param int $idBdc
     * @param BdcRepository $bdcRepository
     * @param Lead $lead
     * @param EntityManagerInterface $em
     * @return Response
     * Injection client, opération dans suivi renta et IRM
     */
    public function ValidateBdcByFinanciale(int $idBdc, BdcRepository $bdcRepository, Lead $lead, EntityManagerInterface $em): Response
    {
        try {
            $bdc = $bdcRepository->find($idBdc);
            if ($bdc) {
                # Variable locale necessaire.......
                $httpClient = HttpClient::create();
                $irmTab = array();
                $customer = $bdc->getResumeLead()->getCustomer();

                /*
                 * Validation service juridique (cas avenant Bdc signé par le client)
                 * Injection uniquement des nouvelles opération dans IRM et Suivi Renta
                */
                if ($bdc->getStatutLead() == intval($this->getParameter('statut_lead_bdc_avenant_creer'))) {
                    foreach ($bdc->getBdcOperations() as $indice) {
                        if ($indice->getAvenant() == 1) {

                            if ($indice->getSiRenta() == 1 || $indice->getSiRenta() == true) {
                                # Injection nouvelle opération dans Suivi Renta.............
                                $httpClient->request('POST', $this->getParameter('suivi_renta_operation_url_post'), [
                                    'body' => [
                                        'operation1' => $indice->getOperation()->getLibelle(),
                                        'pays' => $bdc->getPaysProduction()->getLibelle(),
                                        'bu' => $indice->getBu()->getLibelle(),
                                        'client' => $bdc->getResumeLead()->getCustomer()->getRaisonSocial()
                                    ]
                                ]);
                            }

                            if ($indice->getIrm() == 1 || $indice->getIrm() == true) {
                                # Injection nouvelle opération dans IRM..................
                                $httpClient->request('POST', $this->getParameter('irm_operation_url_post'), [
                                    'body' => [
                                        'libelle' => $indice->getOperation()->getLibelle(),
                                        'operation_client_id' => $bdc->getResumeLead()->getCustomer()->getIrm(),
                                        'Site_id' => '',
                                        'Prime_base' => '0',
                                        'Type' => 'PR'
                                    ]
                                ]);
                            }
                        }
                    }
                } else {

                    # Injection client dans IRM
                    $responseClient = $httpClient->request('POST', $this->getParameter('irm_client_url_post'), [
                        'body' => [
                            'parcours_client_id' => $bdc->getResumeLead()->getCustomer()->getNumClient(),
                            'libelle' => $bdc->getResumeLead()->getCustomer()->getRaisonSocial()
                        ]
                    ]);

                    # Injection Pays dans suivi renta....................
                    if ($bdc->getPaysProduction()->getLibelle() != null) {
                        $httpClient->request('POST', $this->getParameter('suivi_renta_pays_url_post'), [
                            'body' => [
                                'pays1' => $bdc->getPaysProduction()->getLibelle()
                            ]
                        ]);
                    }

                    foreach ($bdc->getBdcOperations() as $bdcoperation)
                    {
                        if ($bdcoperation->getSiRenta() == 1 || $bdcoperation->getSiRenta() == true) {
                            # Injection Client dans suivi renta....................
                            $httpClient->request('POST', $this->getParameter('suivi_renta_client_url_post'), [
                                'body' => [
                                    'client1' => $bdc->getResumeLead()->getCustomer()->getRaisonSocial(),
                                    'bu' => $bdcoperation->getBu()->getLibelle(),
                                    'pays' => $bdc->getPaysProduction()->getLibelle(),
                                ]
                            ]);

                            # Injection Bu dans suivi renta.....................
                            $httpClient->request('POST', $this->getParameter('suivi_renta_bu_url_post'), [
                                'body' => [
                                    'bu1' => $bdcoperation->getBu()->getLibelle(),
                                    'Pays' => $bdc->getPaysProduction()->getLibelle()
                                ]
                            ]);

                            # Injection Operation dans suivi renta.......
                            $httpClient->request('POST', $this->getParameter('suivi_renta_operation_url_post'), [
                                'body' => [
                                    'operation1' => $bdcoperation->getOperation()->getLibelle(),
                                    'pays' => $bdc->getPaysProduction()->getLibelle(),
                                    'bu' => $bdcoperation->getBu()->getLibelle(),
                                    'client' => $bdc->getResumeLead()->getCustomer()->getRaisonSocial()
                                ]
                            ]);
                        }

                        # Inject Operation dans IRM............................
                        if ($bdcoperation->getIrm() == 1 || $bdcoperation->getIrm() == true) {

                            $operation = $bdcoperation->getOperation();
                            $responseOperation = $httpClient->request('POST', $this->getParameter('irm_operation_url_post'), [
                                'body' => [
                                    'libelle' => $operation->getLibelle(),
                                    'operation_client_id' => $responseClient->getContent(),
                                    'Site_id' => '',
                                    'Prime_base' => '0',
                                    'Type' => 'PR'
                                ]
                            ]);

                            if (($responseClient->getStatusCode() == 200) && ($responseOperation->getStatusCode() == 200)) {
                                # Attribuer une valeur au champ IRM dans la table Customer
                                $clientId = str_replace('"', '', $responseClient->getContent());
                                $customer->setIrm($clientId);
                                $em->persist($customer);

                                # Attribuer une valeur au champ IRM dans la table BdcOperation
                                $operationId = str_replace('"', '', $responseOperation->getContent());
                                $bdcoperation->setIrmOperation($operationId);
                                $em->persist($bdcoperation);
                            }
                            array_push($irmTab, true);
                        }
                    }
                }

                if (in_array(true, $irmTab)) {
                    # MAJ champ status lead dans la table Bdc
                    $lead->updateStatusLeadBdc($idBdc, intval($this->getParameter('statut_lead_bdc_valider_dir_fin')));

                    # Ajout ou MAJ statut client dans la table StatutLead
                    $lead->updateStatusLeadByCustomer($customer, intval($this->getParameter('statut_lead_bdc_valider_dir_fin')));

                    # Ajout d'une ligne dans la table WorkflowLead
                    $lead->addWorkflowLead($customer, intval($this->getParameter('statut_lead_bdc_valider_dir_fin')));

                    return $this->json('Client et Operation injecté dans IRM', 200, [], ['groups' => ['update-bdc']]);
                }
            } else {
                return $this->json('Bon de commande non trouvé', 200, [], []);
            }
        } catch (Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /*
    * Calcul marge Cible
    */
    private function getMargeCible(Bdc $bdc)
    {
        /*
            * Marge cible production par opï¿½ration  = (tarif horaire des opï¿½rations ï¿½ cout horaire des opï¿½rations) / tarif horaire des opï¿½rations
            * Cout horaire des opï¿½rations  = cout horaire agent (issus du choix profil agent). Prendre le coï¿½t de lï¿½annï¿½e en cours
            * Marge cible globale = Moyenne pondï¿½rer des marges opï¿½rations (pondï¿½rer par la quantitï¿½)
            * Exemple :

            * Operation A : Quantitï¿½ = 200 / Marge = 40%

            * Operation B : Quantitï¿½ = 50 / Marge = 45%

            * Operation C : Quantitï¿½ = 2000 / Marge = 36%

            * Marge globale = ((200 x 40%) + (50 x 45%) + (2000 x 36%)) / SOMME (quantitï¿½s)
        */

        $sommePonderer = 0;
        $sommeQuantite = 0;
        $margeGlobale = 0;

        foreach($bdc->getBdcOperations() As $bdcOperation)
        {
            # Si type facturation = Acte, alors tarif horaire des operations = CALCULER A PARTIR DE TARIF HORAIRE CIBLE
            # Si non tarif horaire des operations = PRIX UNITAIRE

            # Prix horaire operation
            $tarifHoraireOperations = 0;

            if ($bdcOperation->getTypeFacturation()){
                switch ($bdcOperation->getTypeFacturation()->getId()) {
                    case $this->getParameter('param_id_type_fact_acte'):
                        $tarifHoraireOperations = $bdcOperation->getTarifHoraireCible();
                        break;
                    case $this->getParameter('param_id_type_fact_mixte'):
                        $tarifHoraireOperations = $bdcOperation->getTarifHoraireCible() + $bdcOperation->getPrixUnitaireHeure();
                        break;
                    default:
                        $tarifHoraireOperations = $bdcOperation->getPrixUnit();
                        break;
                }
            }

            $tabIdOperationAuto = $this->getParameter('param_id_operation_automatique');

            $coutHoraire = 0;
            if (!in_array($bdcOperation->getOperation()->getId(), $tabIdOperationAuto)) {
                $coutHoraire = floatval($bdcOperation->getCoutHoraire()->getCoutHoraire());
            }

            $margeOperation = 0;

            if($tarifHoraireOperations > 0)
            {
                $margeOperation = ($tarifHoraireOperations - $coutHoraire) / $tarifHoraireOperations;
            }

            $quantite = 0;

            if ($bdcOperation->getTypeFacturation()) {
                list($quantity, $quantiteActe, $quantiteHeure) = $this->getQuantityOfLignFact($bdcOperation);

                if ($bdcOperation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_mixte')){
                    $quantite = $quantiteActe + $quantiteHeure;
                } else {
                    $quantite = $quantity;
                }
            }

            $sommePonderer += $quantite * $margeOperation;

            $sommeQuantite += $quantite;
        }

        if ($sommeQuantite > 0) {
            $margeGlobale = $sommePonderer / $sommeQuantite;
        }

        return round($margeGlobale, 2);
    }

    private function getMargeCible_old($bdc)
    {
        /*
            * Marge cible production par opï¿½ration  = (tarif horaire des opï¿½rations ï¿½ cout horaire des opï¿½rations) / tarif horaire des opï¿½rations
            * Cout horaire des opï¿½rations  = cout horaire agent (issus du choix profil agent). Prendre le coï¿½t de lï¿½annï¿½e en cours
            * Marge cible globale = Moyenne pondï¿½rer des marges opï¿½rations (pondï¿½rer par la quantitï¿½)
            * Exemple :

            * Operation A : Quantitï¿½ = 200 / Marge = 40%

            * Operation B : Quantitï¿½ = 50 / Marge = 45%

            * Operation C : Quantitï¿½ = 2000 / Marge = 36%

            * Marge globale = ((200 x 40%) + (50 x 45%) + (2000 x 36%)) / SOMME (quantitï¿½s)
        */

        $sommePonderer = 0;
        $sommeQuantite = 0;
        $margeGlobale = 0;

        foreach($bdc->getBdcOperations() As $bdcOperation)
        {
            //Si type facturation = Acte, alors tarif horaire des operations = CALCULER A PARTIR DE TARIF HORAIRE CIBLE
            //Si non tarif horaire des operations = PRIX UNITAIRE

            //prod/h

            $dmtToMin = 0;
            if(!empty($bdcOperation->getDmt()))
            {
                $tDmt = explode(":", $bdcOperation->getDmt());
                $dmtToMin = $tDmt[0];
                $dmtToMin += $tDmt[1] / 60;
            }

            $tempProdToMin = 0;
            $tTempsProd = array();
            if(!empty($bdcOperation->getTempsProductifs()))
            {
                $tTempsProd = explode(":", $bdcOperation->getTempsProductifs());
            }

            if(count($tTempsProd) > 0)
            {
                $tempProdToMin = $tTempsProd[0];
                $tempProdToMin += $tTempsProd[1] / 60;
            }

            $prodParHeure = "";

            if($dmtToMin > 0 && $tempProdToMin > 0)
            {
                $prodParHeure = round($tempProdToMin / $dmtToMin, 2);
            }

            //Prix horaire operation
            $tarifHoraireOperations = $bdcOperation->getPrixUnit();

            if($bdcOperation->getTypeFacturation() != null && $bdcOperation->getTypeFacturation()->getId() == 1)
            {
                $tarifHoraireOperations = 0;

                if($prodParHeure > 0)
                {
                    $tarifHoraireOperations = $bdcOperation->getTarifHoraireCible() / $prodParHeure;
                }
            }

            $tabIdOperationAuto = $this->getParameter('param_id_operation_automatique');
            $coutHoraire = 0;
            if (!in_array($bdcOperation->getOperation()->getId(), $tabIdOperationAuto)) {
                $coutHoraire = floatval($bdcOperation->getCoutHoraire()->getCoutHoraire());
            }

            $margeOperation = 0;

            if($tarifHoraireOperations > 0)
            {
                $margeOperation = ($tarifHoraireOperations - $coutHoraire) / $tarifHoraireOperations;
            }

            $quantite = 0;

            if ($bdcOperation->getTypeFacturation()) {
                list($quantity, $quantiteActe, $quantiteHeure) = $this->getQuantityOfLignFact($bdcOperation);

                if ($bdcOperation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_mixte')){
                    $quantite = $quantiteActe + $quantiteHeure;
                } else {
                    $quantite = $quantity;
                }
            }

            $sommePonderer += $quantite * $margeOperation;

            $sommeQuantite += $quantite;
        }

        if ($sommeQuantite > 0) {
            $margeGlobale = $sommePonderer / $sommeQuantite;
        }

        return round($margeGlobale, 2);
    }

    /**
     * @Route("/bdc/pdf/{id}", name="bdc_pdf", methods={"GET"})
     * @return Response
     */
    public function getBdcPDFById(Bdc $bdc): Response
    {
        $avenant = null;

        if (in_array($bdc->getStatutLead(), $this->getParameter('statut_lead_bdc_avenant'))) {
            $avenant = 'avenant';
        }

        $this->setPdf($bdc, "client", $avenant);

        return $this->json("Ok", 200, [], ['groups' => ['update-bdc']]);
    }

    private function getLastVersionOfBdc(string $numVersion = null): int
    {
        $tmpNum = explode("_V", $numVersion);

        $tmpLastVersion = explode("_", $tmpNum[1]);

        # Dernière version
        return $tmpLastVersion[0];
    }

    /**
     * Géneration d'un PDF pour le bon de commande en question
     * @param Bdc $bdc
     * @param $type
     * @param $avenant
     * Generation pdf bdc
     * @param int|null $isBdcEnProd
     */
    private function setPdf(Bdc $bdc, $type, $avenant, int $isBdcEnProd = null)
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
                $textNmoins1Version = "Avenant qui annule et remplace le devis numéro ". $bdc->getNumBdc() . " version " . "V" . $nmoinsVersion . " daté du : " . date_format($bdc->getDateCreate(), 'd/m/Y');
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
                        "langueTrt" => $index->getLangueTrt(),
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
        # Load HTML to Dompdf
        $dompdf->loadHtml($html);

        # (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');
        # Render the HTML as PDF
        $dompdf->render();

        $output = $dompdf->output();

        if ($type == "client") {
            if ($avenant != null) {
                file_put_contents($this->getParameter('bdc_dir') . 'devis_avenant_' . $bdc->getIdMere() . '.pdf', $output);
            } else {
                file_put_contents($this->getParameter('bdc_dir') . 'devis_' . $bdc->getIdMere() . '.pdf', $output);
            }
        } else {
            if ($avenant != null) {
                file_put_contents($this->getParameter('bdc_dir') . 'devis_interne_avenant_' . $bdc->getIdMere() . '.pdf', $output);
            } else {
                file_put_contents($this->getParameter('bdc_dir') . 'devis_interne_' . $bdc->getIdMere() . '.pdf', $output);
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
                $textNmoins1Version = "Avenant qui annule et remplace le devis numéro ". $bdc->getNumBdc() . " version " . "V" . $nmoinsVersion . " daté du : " . date_format($bdc->getDateCreate(), 'd/m/Y');
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
                        "langueTrt" => $index->getLangueTrt(),
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
                'nombre' => $dompdf->getCanvas()->get_page_count(),
                'dateApp' => null
            ]);
        } else {
            $html = $this->renderView('bdc_interne.html.twig', [
                'bdc' => $bdc,
                'totalHT' => $totalHT,
                'montantTva' => $montantTva,
                'date_edit' => date("d/m/Y")
            ]);
        }
        # Load HTML to Dompdf
        $dompdf2->loadHtml($html);

        # (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf2->setPaper('A4', 'portrait');
        # Render the HTML as PDF
        $dompdf2->render();

        $output = $dompdf2->output();

        if ($type == "client") {
            if ($avenant != null) {
                file_put_contents($this->getParameter('bdc_dir') . 'devis_avenant_' . $bdc->getIdMere() . '.pdf', $output);
            } else {
                file_put_contents($this->getParameter('bdc_dir') . 'devis_' . $bdc->getIdMere() . '.pdf', $output);
            }
        } else {
            if ($avenant != null) {
                file_put_contents($this->getParameter('bdc_dir') . 'devis_interne_avenant_' . $bdc->getIdMere() . '.pdf', $output);
            } else {
                file_put_contents($this->getParameter('bdc_dir') . 'devis_interne_' . $bdc->getIdMere() . '.pdf', $output);
            }
        }
    }

    private function setPdf2(Bdc $bdc, $type, $avenant, int $isBdcEnProd = null,$ligne,$dateApp, OperationRepository $repoOpe,BdcOperationRepository $repoBdc)
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
                $textNmoins1Version = "Avenant qui annule et remplace le devis numéro ". $bdc->getNumBdc() . " version " . "V" . $nmoinsVersion . " daté du : " . date_format($bdc->getDateCreate(), 'd/m/Y');
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
                        "langueTrt" => $index->getLangueTrt(),
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
                'nombre' => 0
            ]);
        } else {
            $html = $this->renderView('bdc_interne.html.twig', [
                'bdc' => $bdc,
                'totalHT' => $totalHT,
                'montantTva' => $montantTva,
                'date_edit' => date("d/m/Y")
            ]);
        }
        # Load HTML to Dompdf
        $dompdf->loadHtml($html);

        # (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');
        # Render the HTML as PDF
        $dompdf->render();

        $output = $dompdf->output();

        if ($type == "client") {
            if ($avenant != null) {
                file_put_contents($this->getParameter('bdc_dir') . 'devis_avenant_' . $bdc->getIdMere() . '.pdf', $output);
                file_put_contents($this->getParameter('bdc_dir') . 'devis_avenant_Hausse_' . $bdc->getIdMere() . '.pdf', $output);
            } else {
                file_put_contents($this->getParameter('bdc_dir') . 'devis_' . $bdc->getIdMere() . '.pdf', $output);
            }
        } else {
            if ($avenant != null) {
                file_put_contents($this->getParameter('bdc_dir') . 'devis_interne_avenant_' . $bdc->getIdMere() . '.pdf', $output);
            } else {
                file_put_contents($this->getParameter('bdc_dir') . 'devis_interne_' . $bdc->getIdMere() . '.pdf', $output);
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
                $textNmoins1Version = "Avenant qui annule et remplace le devis numéro ". $bdc->getNumBdc() . " version " . "V" . $nmoinsVersion . " daté du : " . date_format($bdc->getDateCreate(), 'd/m/Y');
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
                        "langueTrt" => $index->getLangueTrt(),
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

        $BdcoperationHausse=[];
        foreach($ligne as $l){
            $bdcLigne=$repoBdc->find($l->getIdOperation());
            $bdcLigne->setOldPrixUnit($l->getAncienPrix());
            $bdcLigne->setPrixUnit($l->getNouveauPrix());
            array_push($BdcoperationHausse, $repoBdc->find($l->getIdOperation()));
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
                'nombre' => $dompdf->getCanvas()->get_page_count(),
                'dateApp' => $dateApp
            ]);
        } else {
            $html = $this->renderView('bdc_interne.html.twig', [
                'bdc' => $bdc,
                'totalHT' => $totalHT,
                'montantTva' => $montantTva,
                'date_edit' => date("d/m/Y")
            ]);
        }
        # Load HTML to Dompdf
        $dompdf2->loadHtml($html);

        # (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf2->setPaper('A4', 'portrait');
        # Render the HTML as PDF
        $dompdf2->render();

        $output = $dompdf2->output();

        if ($type == "client") {
            if ($avenant != null) {
                file_put_contents($this->getParameter('bdc_dir') . 'devis_avenant_' . $bdc->getIdMere() . '.pdf', $output);
                file_put_contents($this->getParameter('bdc_dir') . 'devis_avenant_Hausse_' . $bdc->getIdMere() . '.pdf', $output);
            } else {
                file_put_contents($this->getParameter('bdc_dir') . 'devis_' . $bdc->getIdMere() . '.pdf', $output);
            }
        } else {
            if ($avenant != null) {
                file_put_contents($this->getParameter('bdc_dir') . 'devis_interne_avenant_' . $bdc->getIdMere() . '.pdf', $output);
            } else {
                file_put_contents($this->getParameter('bdc_dir') . 'devis_interne_' . $bdc->getIdMere() . '.pdf', $output);
            }
        }
    }

    /**
     * Géneration d'un PDF pour le bon de commande en question
     * @param Bdc $bdc
     * @param $hausseClient
     * @param $hauseBdcO
     * @param string $langue
     * @param BdcOperationRepository $repoBdc
     */
    private function setPdfContart(Bdc $bdc, $hausseClient,$hauseBdcO,string $langue,BdcOperationRepository $repoBdc,ContratRepository $repoContart)
    {
        # Configure Dompdf according to your needs
        $pdfOptions = new Options();

        # $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->set('defaultFont', 'Arial');

        $textNmoins1Version = null;
        # Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $customer = $bdc->getResumeLead()->getCustomer();
        $fistContact = $customer->getContacts()[0];
        $rms= $fistContact->getNom() ." ". $fistContact->getPrenom();
        // $isHasContrat = $customer->getIsHasContract();
        $isHasContrat = $repoContart->findOneBy(['idCustomer'=> $customer->getId()]);
        $raisonSocialTitre = $customer->getRaisonSocial();
        $BdcoperationHausseLibelle=[];
        $tempArray= [];
        $hausseReponse =[];

        foreach($hauseBdcO as $l){
            $bdcLigne=$repoBdc->find($l->getIdOperation());
            if(!in_array($bdcLigne->getOperation()->getLibelle(), $tempArray)){
                $BdcoperationHausseLibelle[$l->getId()]=$bdcLigne->getOperation()->getLibelle();
                $tempArray [] = $bdcLigne->getOperation()->getLibelle();
                $hausseReponse [] =$l;
            }
        }

        # Logique description si ligne de facturation = HNO
        if ($langue == "FR"){
            $Titre = "Avenant au Devis";
            
            if($isHasContrat){
                $Titre = "Avenant au contrat";
            }
            $html = $this->renderView('Contrat.html.twig', [
                'DateSignature' => $bdc->getDateSignature(),
                'Societe' => $bdc->getSocieteFacturation()->getLibelle(),
                'capital' => $bdc->getSocieteFacturation()->getCapital(),
                'form_juridique' =>$bdc->getSocieteFacturation()->getFormeJuridique(),
                'numeroFiscal' => $bdc->getSocieteFacturation()->getIdentifiantFiscal(),
                'registreNumero' => $bdc->getSocieteFacturation()->getRegistreCommerce(),
                'adresse' => $bdc->getSocieteFacturation()->getAdresse(),
                'ville' => $bdc->getSocieteFacturation()->getVille(),
                'MrsRepresente' => $rms,
                'Fonction' => $bdc->getResumeLead()->getCustomer()->getContacts()[0]->getFonction(),
                'CathegorieClient' => $bdc->getResumeLead()->getCustomer()->getCategorieClient()->getLibelle(),
                'hausseClient' => $hausseClient,
                'hauseBdcO' => $hausseReponse,
                'BdcoperationHausseLibelle' => $BdcoperationHausseLibelle,
                'bdc' => $bdc,
                'SocieteFacture' => $bdc->getSocieteFacturation(),
                'titre' => strtoupper($Titre)
            ]);
        }
        else{
            $TitreUs = "Amendment to the purchase order";
            
            if($isHasContrat){
                $TitreUs = "Amendment to the contract";
            }
            $html = $this->renderView('ContratVersionUk.html.twig', [
                'DateSignature' => $bdc->getDateSignature(),
                'Societe' => $bdc->getSocieteFacturation()->getLibelle(),
                'capital' => $bdc->getSocieteFacturation()->getCapital(),
                'form_juridique' =>$bdc->getSocieteFacturation()->getFormeJuridique(),
                'numeroFiscal' => $bdc->getSocieteFacturation()->getIdentifiantFiscal(),
                'registreNumero' => $bdc->getSocieteFacturation()->getRegistreCommerce(),
                'adresse' => $bdc->getSocieteFacturation()->getAdresse(),
                'ville' => $bdc->getSocieteFacturation()->getVille(),
                'MrsRepresente' => $rms,
                'Fonction' => $bdc->getResumeLead()->getCustomer()->getContacts()[0]->getFonction(),
                'CathegorieClient' => $bdc->getResumeLead()->getCustomer()->getCategorieClient()->getLibelle(),
                'hausseClient' => $hausseClient,
                'hauseBdcO' => $hausseReponse,
                'BdcoperationHausseLibelle' => $BdcoperationHausseLibelle,
                'bdc' => $bdc,
                'SocieteFacture' => $bdc->getSocieteFacturation(),
                'titre' => strtoupper($TitreUs)
            ]);
        }
        # Load HTML to Dompdf
        $dompdf->loadHtml($html);

        # (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');
        # Render the HTML as PDF
        $dompdf->render();
        $output = $dompdf->output();
        if ($langue == "FR")
        file_put_contents($this->getParameter('bdc_dir') .$raisonSocialTitre. ' Avenant Revision taifaire.pdf', $output);
        else
        file_put_contents($this->getParameter('bdc_dir') .$raisonSocialTitre. ' Avenant Revision taifaireUK.pdf', $output);
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

    /**
     * @param $id
     * @param $manager
     * @param $request
     * @param null $arrayNewOperation
     * @return Bdc|null
     * Methode créer une nouvelle version du BDC (Duplication du bdc en question)..................
     */
    public function saveBdcDupsAndNewVersion(Bdc $dataUpdate, $manager, $request, $arrayNewOperation = null): ?Bdc
    {
        # Contient la majorité des données à jour du bdc (venant du front)
        $jsonRecu = null;
        # Contient la juste les bdcOperations à jour du bdc (venant du front aussi)
        $bdcOperationArray = null;
        # Recupération des données venant du front, et on les stockes dans les variables ci-dessus
        if ($request !== null) {
            $jsonRecu = json_decode($request->getContent(), true);
            $bdcOperationArray = $jsonRecu['bdcOperations'] ?? [];
        }

        $bdc = null;
        if (!empty($dataUpdate)) {

            # Logique num version
            if ($dataUpdate->getNumVersion() == null) {
                $number = 1;
            } else {
                # On va decouper le numero de version
                $mot = explode("V", $dataUpdate->getNumVersion());
                $mot2 = explode("_", $mot[1]);
                $number = intval($mot2[0]) + 1;
            }

            # Nouvel enregistrement BDC dans la base
            $bdc = $this->getBdc($dataUpdate, $jsonRecu);
            $bdc->setNumVersion($dataUpdate->getIdMere() . '_' . 'V' . $number . '_' . date("Y-m-d"));
            $bdc->setUniqId(uniqid());

            $this->addOperationBdc($bdcOperationArray, $bdc, $dataUpdate, $arrayNewOperation);

            $manager->persist($bdc);
            $manager->flush();
        }
        return $bdc;
    }

    private function saveBdcDupsAndNewVersion2($id, $manager, $request, $arrayNewOperation = null, $DateAplicatif): ?Bdc
    {
        # Recupération de l'actuel bon de commande (ancien)
        $dataUpdate = $this->getDoctrine()->getRepository(Bdc::class)->find($id);
        # Contient la majorité des données à jour du bdc (venant du front)
        $jsonRecu = null;
        # Contient la juste les bdcOperations à jour du bdc (venant du front aussi)
        $bdcOperationArray = null;
        # Recupération des données venant du front, et on les stockes dans les variables ci-dessus
        if ($request !== null) {
            $jsonRecu = json_decode($request->getContent(), true);
            $bdcOperationArray = $jsonRecu['bdcOperations'] ?? [];
        }

        $bdc = null;
        if (!empty($dataUpdate)) {

            # Logique num version
            if ($dataUpdate->getNumVersion() == null) {
                $number = 1;
            } else {
                # On va decouper le numero de version
                $mot = explode("V", $dataUpdate->getNumVersion());
                $mot2 = explode("_", $mot[1]);
                $number = intval($mot2[0]) + 1;
            }
            # Nouvel enregistrement BDC dans la base
            $bdc = $this->getBdc2($dataUpdate, $jsonRecu);
            $bdc->setNumVersion($dataUpdate->getIdMere() . '_' . 'V' . $number . '_' . date("Y-m-d"));
            $bdc->setUniqId(uniqid());

            #Manala Sign Commercial sy Client
            $bdc->setSignaturePackageComId(null);
            $bdc->setSignaturePackageId(null);

            $this->addOperationBdc2($bdcOperationArray, $bdc, $dataUpdate, $arrayNewOperation,$DateAplicatif);

            $manager->persist($bdc);
            $manager->flush();
        }
        return $bdc;
    }

    # Méthode pour mettre un nouvel enregistrement dans la table tarif
    /*private function saveTarif($id, $manager) {

        $dataBdcOperation = $this->getDoctrine()->getRepository(BdcOperation::class)->find($id);

        # Mise à jour date fin de l'ancien tarif (RG: J-1 de la date debut du nouveau tarif)
        $dateNow = new \DateTime();
        $idAncienTarif = $dataBdcOperation->getTarif()->getId();
        if ($idAncienTarif != null) {
            $dataTarif = $this->getDoctrine()->getRepository(Tarif::class)->find($idAncienTarif);
            $dataTarif->setDateFin($dateNow->modify('-1 day'));
            $manager->persist($dataTarif);
        }

        # Ajout nouveau tarif
        $tarif = new Tarif();
        if ($dataBdcOperation) {
            $tarif->setDateDebut($dateNow)
                ->setPaysProduction($this->getDoctrine()->getRepository(PaysProduction::class)->find($dataBdcOperation->getPaysProduction()))
                ->setBu($this->getDoctrine()->getRepository(Bu::class)->find($dataBdcOperation->getBu()))
                ->setLangueTraitement($this->getDoctrine()->getRepository(LangueTrt::class)->find($dataBdcOperation->getLangueTraitement()))
                ->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($dataBdcOperation->getOperation()))
                ->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($dataBdcOperation->getTypeFacturation()));

            $manager->persist($tarif);
        }

        $manager->flush();
    }*/

    /**
     * @Route("/delete/operation/{id}", name="delete_ligne_facturation", methods={"DELETE"})
     * @param int $id
     * @param BdcOperationRepository $repository
     * @param EntityManagerInterface $manager
     * @param SendMailTo $sendMailTo
     * @param Lead $lead
     * @return Response
     * Suppression d'une ligne de facturation par rapport à son ID....
     */
    public function deleteLigneFacturation(int $id, BdcOperationRepository $repository,
                                           EntityManagerInterface $manager, SendMailTo $sendMailTo,
                                           Lead $lead, UserRepository $userRepository, UserInterface $currentUser): Response
    {
        $dataDeleted = $repository->find($id);
        if ($dataDeleted) {
            try {
                $currentBdc = $dataDeleted->getBdc();

                # Logique send mail au N+1..................
                list($client, $user, $clientObj) = $this->getUserConnecte($dataDeleted->getBdc());

                # Appel methode pour avoir nouvel statut et notification par email
                list($objNotif, $twigNotif, $newStatut, $respNotif) = $this->getNewStatutAndNotification($dataDeleted->getBdc());

                $allUsers = $userRepository->findAll();

                $validators = [];
                foreach ($allUsers as $user) {
                    $roles = $user->getRoles();
                    if (in_array($respNotif, $roles)) {
                        array_push($validators, $user);
                    }
                }

                if (in_array($currentBdc->getStatutLead(), $this->getParameter('param_statut_lead_bdc_for_bdc_operation_to_delete'))
                    || in_array($dataDeleted->getOperation()->getId(), $this->getParameter('param_id_operation_supprimable_dans_lign_fact'))
                    || $currentBdc->getStatutLead() == null) {
                    # Supression d'une ligne de fact cas bdc refusé par dir prod ou dir fin ou dg ou bdc créer
                    if ($currentBdc->getStatutLead() == $this->getParameter('statut_lead_bdc_draft') || $currentBdc->getStatutLead() == $this->getParameter('statut_lead_bdc_rejeter_dir_prod') || $currentBdc->getStatutLead() == $this->getParameter('statut_lead_bdc_rejeter_dir_fin') || $currentBdc->getStatutLead() == $this->getParameter('statut_lead_bdc_rejeter_dg')) {
                        # Supression
                        $manager->remove($dataDeleted);
                        $manager->flush();

                        # Suppression lead detail operation associé
                        $this->deleteLeadDetailOperation($dataDeleted, $manager);

                        # Mise à jour statut bdc
                        $lead->updateStatusLeadBdc($dataDeleted->getBdc()->getId(), $newStatut);

                        # Ajout ou MAJ statut client dans la table StatutLead
                        $lead->updateStatusLeadByCustomer($clientObj, $newStatut);

                        #Notification validateur si current BDC a été Rejeté
                        if($currentBdc->getStatutLead() != $this->getParameter('statut_lead_bdc_draft'))
                        {
                            if (count($validators) > 0){
                                foreach ($validators as $validator) {
                                    if ($respNotif == "ROLE_DIRPROD") {
                                        if ($validator->getPaysProduction() != null && $validator->getPaysProduction()->getId() == $currentBdc->getPaysProduction()->getId()) {
                                            $sendMailTo->sendEmailViaTwigTemplate($currentUser->getEmail(), $validator->getEmail(), $objNotif, 'emailContent/' . $twigNotif, $currentUser, $currentBdc->getId());
                                        }
                                    } else {
                                        $sendMailTo->sendEmailViaTwigTemplate($currentUser->getEmail(), $validator->getEmail(), $objNotif, 'emailContent/' . $twigNotif, $currentUser, $currentBdc->getId());
                                    }
                                }
                            }
                        }

                        # Generation d'un pdf pour le bdc
                        $this->setPdf($dataDeleted->getBdc(), "client", null);
                    }

                    # Supression d'une ligne de fact cas demande de modification par le client. BDC envoyé au client si statut lead bdc = signé par le commercial
                    if ($dataDeleted->getBdc()->getStatutLead() == $this->getParameter('statut_lead_bdc_signe_com')) {
                        # Supression
                        $manager->remove($dataDeleted);
                        $manager->flush();

                        # Suppression lead detail operation associé
                        $this->deleteLeadDetailOperation($dataDeleted, $manager);

                        # Duplication bdc avec nouvelle version
                        $bdc2 = $this->saveBdcDupsAndNewVersion($dataDeleted->getBdc(), $manager, null);

                        # Nouvel bdc
                        $createdBdc = $this->getDoctrine()->getRepository(Bdc::class)->find($bdc2->getId());

                        # MAJ champ status lead dans la table Bdc
                        $lead->updateStatusLeadBdc($createdBdc->getId(), $newStatut);

                        # Ajout d'une ligne dans la table WorkflowLead
                        $lead->addWorkflowLead($createdBdc->getResumeLead()->getCustomer(), $newStatut);

                        #Notification validateur
                        if (count($validators) > 0){
                            foreach ($validators as $validator) {
                                if ($validator->getPaysProduction() != null && $validator->getPaysProduction()->getId() == $currentBdc->getPaysProduction()->getId()) {
                                    $sendMailTo->sendEmailViaTwigTemplate($currentUser->getEmail(), $validator->getEmail(), $objNotif, 'emailContent/' . $twigNotif, $currentUser, $currentBdc->getId());
                                }
                            }
                        }

                        # Generation d'un pdf pour le nouvel bdc
                        $this->setPdf($createdBdc, "client", null);
                    }

                    # Suppression ligne de facturation cas Bdc qui a pour statutlead égal null ou fiche qualification créer
                    if ($currentBdc->getStatutLead() == null) {
                        # Supression
                        $manager->remove($dataDeleted);
                        $manager->flush();
                    }
                    return  $this->json("Delete data successfully !", 200, [], []);
                }
            } catch (\Exception $exception) {
                return $this->json([
                    "status" => 500,
                    "message" => $exception->getMessage()
                ], 500);
            }
        } else {
            return $this->json("Ce ligne de facturation n'existe pas", 200, [], []);
        }
    }

    /**
     * @param $user
     * @param $dataFront
     * @return array
     * Recupere les bdcs en fonction du filtre selectionné
     */
    private function getBdcByMultiSelect($user, $dataFront)
    {
        $results = [];

        foreach ($dataFront["tabStatut"] as $tab) {
            if ($tab != 1) {
                # Recuperation des bons de commande en fonction du status lead
                $res = $this->getDoctrine()->getRepository(Bdc::class)->getBdcViaStatutLead($user->getId(), $tab);

                $results[] = $res;
            }
        }

        $bdcTab = [];

        for ($i=0; $i < count($results); $i++){
            if (count($results[$i]) > 0){
                foreach ($results[$i] as $bdc){
                    $bdcTab[] = $bdc;
                }
            }
        }

        return $bdcTab;
    }

    /**
     * @param $user
     * @param $dataFront
     * @return array
     * Recupere les bdcs en fonction du dure de traitement selectionné
     */
    private function getBdcByMultiSelectDureTrt($user, $dataFront)
    {
        $results = [];

        foreach ($dataFront["dmtSearch"] as $tab) {
            # Recuperation des bons de commande en fonction du status lead
            $res = $this->getDoctrine()->getRepository(Bdc::class)->getBdcViaDureTrt($user->getId(), $tab);

            $results[] = $res;
        }

        $bdcTab = [];

        if ($results){
            for ($i=0; $i < count($results); $i++){
                if (count($results[$i]) > 0){
                    foreach ($results[$i] as $bdc){
                        $bdcTab[] = $bdc;
                    }
                }
            }
        }

        return $bdcTab;
    }

    /**
     * @param $user
     * @param $dataFront
     * @return array
     * Recupere les bdcs en fonction du dure de traitement selectionné
     */
    private function getBdcByCaMensuelSearch($user, $dataFront)
    {
        $results = [];

        # Recuperation des bons de commande en fonction du status lead
        $res = $this->getDoctrine()->getRepository(Bdc::class)->getBdcViaDureTrt($user->getId(), $dataFront);

        $bdcTab = [];

        if ($results){
            for ($i=0; $i < count($results); $i++){
                if (count($results[$i]) > 0){
                    foreach ($results[$i] as $bdc){
                        $bdcTab[] = $bdc;
                    }
                }
            }
        }

        return $bdcTab;
    }

    /**
     * @param Bdc $dataUpdate
     * @param $jsonRecu
     * @param int|null $statutLead
     * @return Bdc
     */
    private function getBdc(Bdc $dataUpdate, $jsonRecu, int $statutLead = null): Bdc
    {
        $bdc = new Bdc();
        $bdc->setDateDebut($dataUpdate->getDateDebut() ?? null);
        $bdc->setAdresseFacturation($dataUpdate->getAdresseFacturation() ?? null);
        $bdc->setCdc($dataUpdate->getCdc() ?? null);
        $bdc->setCgv($dataUpdate->getCgv() ?? null);
        $bdc->setDateCreate($dataUpdate->getDateCreate() ?? null);
        $bdc->setDateFin($dataUpdate->getDateFin() ?? null);
        $bdc->setDateModification(new \DateTime());
        $bdc->setDiffusions($dataUpdate->getDiffusions() ?? null);
        $bdc->setMargeCible($dataUpdate->getMargeCible() ?? null);
        $bdc->setDateSignature($dataUpdate->getDateSignature() ?? null);
        $bdc->setPaysProduction($this->getDoctrine()->getRepository(PaysProduction::class)->find($dataUpdate->getPaysProduction()));
        $bdc->setTitre($dataUpdate->getTitre() ?? null);
        $bdc->setDescriptionGlobale($dataUpdate->getDescriptionGlobale() ?? null);
        $bdc->setResumePrestation($dataUpdate->getResumePrestation() ?? null);
        $bdc->setResumeLead($this->getDoctrine()->getRepository(ResumeLead::class)->find($dataUpdate->getResumeLead()));
        $bdc->setPaysFacturation($this->getDoctrine()->getRepository(PaysFacturation::class)->find($dataUpdate->getPaysFacturation()));
        $bdc->setNumBdc($dataUpdate->getNumBdc() ?? null);
        $bdc->setIdMere($dataUpdate->getIdMere() ?? null);
        $bdc->setDateSignature($dataUpdate->getDateSignature() ?? null);
        $bdc->setSignaturePackageId($dataUpdate->getSignaturePackageId() ?? null);
        $bdc->setSignaturePackageComId($dataUpdate->getSignaturePackageComId() ?? null);
        $bdc->setClientIrmId($dataUpdate->getClientIrmId() ?? null);

        $currentSuiteProcess = $dataUpdate->getSuiteProcess();

        $suiteProcess = new SuiteProcess();
        $suiteProcess->setIsCustomerWillSendBdc($currentSuiteProcess->getIsCustomerWillSendBdc());
        $suiteProcess->setIsSeizureContract($currentSuiteProcess->getIsSeizureContract());
        $suiteProcess->setIsDevisPassToProdAfterSign($currentSuiteProcess->getIsDevisPassToProdAfterSign());
        $bdc->setSuiteProcess($suiteProcess);

        if (!empty($statutLead)){
            $bdc->setStatutLead($statutLead);
        } else {
            $bdc->setStatutLead($dataUpdate->getStatutLead() ?? null);
        }

        # Ajout destinataire facture................
        $tabDestinaFacture = [];
        if (!empty($dataUpdate->getDestinataireFacture())) {
            foreach ($dataUpdate->getDestinataireFacture() as $facture) {
                $tabDestinaFacture[] = $facture;
            }
            $bdc->setDestinataireFacture($tabDestinaFacture);
        }

        # Ajout destinataire signataire........
        $tabDestinataireSignataire = [];
        if (!empty($dataUpdate->getDestinataireSignataire())) {
            foreach ($dataUpdate->getDestinataireSignataire() as $signataire) {
                $tabDestinataireSignataire[] = $signataire;
            }
            $bdc->setDestinataireSignataire($tabDestinataireSignataire);
        }

        if ($jsonRecu !== null) {
            $bdc->setDelaisPaiment($jsonRecu['delaisPaiment']);
            $bdc->setDevise($this->getDoctrine()->getRepository(Devise::class)->find($jsonRecu['devise']));
            $bdc->setModeReglement($jsonRecu['modeReglement']);
            $bdc->setTva($this->getDoctrine()->getRepository(Tva::class)->find($jsonRecu['tva']));
            $bdc->setSocieteFacturation($this->getDoctrine()->getRepository(SocieteFacturation::class)->find($jsonRecu['societeFacturation']));
        } else {
            $bdc->setDelaisPaiment($dataUpdate->getDelaisPaiment());
            $bdc->setDevise($this->getDoctrine()->getRepository(Devise::class)->find($dataUpdate->getDevise()));
            $bdc->setModeReglement($dataUpdate->getModeReglement());
            $bdc->setTva($this->getDoctrine()->getRepository(Tva::class)->find($dataUpdate->getTva()));
            $bdc->setSocieteFacturation($this->getDoctrine()->getRepository(SocieteFacturation::class)->find($dataUpdate->getSocieteFacturation()));
        }

        return $bdc;
    }

    private function getBdc2($dataUpdate, $jsonRecu, int $statutLead = null): Bdc
    {
        $bdc = new Bdc();
        $bdc->setDateDebut($dataUpdate->getDateDebut() ?? null);
        $bdc->setAdresseFacturation($dataUpdate->getAdresseFacturation() ?? null);
        $bdc->setCdc($dataUpdate->getCdc() ?? null);
        $bdc->setCgv($dataUpdate->getCgv() ?? null);
        $bdc->setDateCreate($dataUpdate->getDateCreate() ?? null);
        $bdc->setDateFin($dataUpdate->getDateFin() ?? null);
        $bdc->setDateModification(new \DateTime());
        $bdc->setDiffusions($dataUpdate->getDiffusions() ?? null);
        $bdc->setMargeCible($dataUpdate->getMargeCible() ?? null);
        $bdc->setDateSignature($dataUpdate->getDateSignature() ?? null);
        $bdc->setPaysProduction($this->getDoctrine()->getRepository(PaysProduction::class)->find($dataUpdate->getPaysProduction()));
        $bdc->setTitre($dataUpdate->getTitre() ?? null);
        $bdc->setDescriptionGlobale($dataUpdate->getDescriptionGlobale() ?? null);
        $bdc->setResumePrestation($dataUpdate->getResumePrestation() ?? null);
        $bdc->setResumeLead($this->getDoctrine()->getRepository(ResumeLead::class)->find($dataUpdate->getResumeLead()));
        $bdc->setPaysFacturation($this->getDoctrine()->getRepository(PaysFacturation::class)->find($dataUpdate->getPaysFacturation()));
        $bdc->setNumBdc($dataUpdate->getNumBdc() ?? null);
        $bdc->setIdMere($dataUpdate->getIdMere() ?? null);
        $bdc->setDateSignature($dataUpdate->getDateSignature() ?? null);
        // $bdc->setSignaturePackageId($dataUpdate->getSignaturePackageId() ?? null);
        $bdc->setSignaturePackageComId($dataUpdate->getSignaturePackageComId() ?? null);
        $bdc->setClientIrmId($dataUpdate->getClientIrmId() ?? null);

        $currentSuiteProcess = $dataUpdate->getSuiteProcess();

        $suiteProcess = new SuiteProcess();
        $suiteProcess->setIsCustomerWillSendBdc($currentSuiteProcess->getIsCustomerWillSendBdc());
        $suiteProcess->setIsSeizureContract($currentSuiteProcess->getIsSeizureContract());
        $suiteProcess->setIsDevisPassToProdAfterSign($currentSuiteProcess->getIsDevisPassToProdAfterSign());
        $bdc->setSuiteProcess($suiteProcess);

        if (!empty($statutLead)){
            $bdc->setStatutLead($statutLead);
        } else {
            $bdc->setStatutLead($dataUpdate->getStatutLead() ?? null);
        }

        # Ajout destinataire facture................
        $tabDestinaFacture = [];
        if (!empty($dataUpdate->getDestinataireFacture())) {
            foreach ($dataUpdate->getDestinataireFacture() as $facture) {
                $tabDestinaFacture[] = $facture;
            }
            $bdc->setDestinataireFacture($tabDestinaFacture);
        }

        # Ajout destinataire signataire........
        $tabDestinataireSignataire = [];
        if (!empty($dataUpdate->getDestinataireSignataire())) {
            foreach ($dataUpdate->getDestinataireSignataire() as $signataire) {
                $tabDestinataireSignataire[] = $signataire;
            }
            $bdc->setDestinataireSignataire($tabDestinataireSignataire);
        }

        $bdc->setDelaisPaiment($dataUpdate->getDelaisPaiment());
        $bdc->setDevise($this->getDoctrine()->getRepository(Devise::class)->find($dataUpdate->getDevise()));
        $bdc->setModeReglement($dataUpdate->getModeReglement());
        $bdc->setTva($this->getDoctrine()->getRepository(Tva::class)->find($dataUpdate->getTva()));
        $bdc->setSocieteFacturation($this->getDoctrine()->getRepository(SocieteFacturation::class)->find($dataUpdate->getSocieteFacturation()));

        return $bdc;
    }

    public function sendToSign($files, $signataire, $devis, $em, $page, int $isSendToCustomer = null, $Contrat = null){
        /* if($isSendToCustomer)
        {
            switch($page)
            {
                case 1:
                    $top = 61.90625;
                    $left = 30.99609375;
                    $right = 480.23828125;
                    $bottom = 10.16015625;
                    $tabIndex = 0;
                    break;
                case 2:
                    $top = 81.90625;
                    $left = 30.99609375;
                    $right = 480.23828125;
                    $bottom = 20.16015625;
                    $tabIndex = 0;
                    break;
                case 3:
                    $top = 461.90625;
                    $left = 30.99609375;
                    $right = 480.23828125;
                    $bottom = 400.16015625;
                    $tabIndex = 0;
                    break;
				default:
					$top = 461.90625;
                    $left = 30.99609375;
                    $right = 480.23828125;
                    $bottom = 400.16015625;
                    $tabIndex = 0;
                    break;

            }
        }
        else
        {
            switch($page)
            {
                case 1:
                    $top = 61.90625;
                    $left = 30.99609375;
                    $right = 160.23828125;
                    $bottom = 10.16015625;
                    $tabIndex = 0;
                    break;
                case 2:
                    $top = 81.90625;
                    $left = 30.99609375;
                    $right = 160.23828125;
                    $bottom = 20.16015625;
                    $tabIndex = 0;
                    break;
                case 3:
                    $top = 461.90625;
                    $left = 30.99609375;
                    $right = 160.23828125;
                    $bottom = 400.16015625;
                    $tabIndex = 0;
                    break;
				default:
					$top = 461.90625;
                    $left = 30.99609375;
                    $right = 160.23828125;
                    $bottom = 400.16015625;
                    $tabIndex = 0;
                    break;
            }
        } */

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

        if (in_array($devis->getStatutLead(), $this->getParameter('statut_lead_validate_by_dir_fin'))){
            $objNotif = 'Demande de signature pour le devis n° ' . $devis->getNumBdc() . ' pour la société ' . $devis->getResumeLead()->getCustomer()->getRaisonSocial();
        } else {
            $objNotif = "Devis à signer de la part de Outsourcia";
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
            "mailSubject" => $objNotif,
            "mailMessage" => "Merci de cliquer sur le bouton ci-dessous pour passer à la signature électronique du Devis SVP.<br/>Cordialement, ",
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

                # Stocké dans la BDD l'id du package
                if ($isSendToCustomer == 1) {
                    $devis->setSignaturePackageId($response->id);
                } else {
                    $devis->setSignaturePackageComId($response->id);
                }

                $em->persist($devis);
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

    public function nbr_pages($pdf){
        if (false !== ($fichier = file_get_contents($pdf))){
            $pages = preg_match_all("/\/Page\W/", $fichier, $matches);
            return $pages;
        }
    }

    /**
     * @param $bdcOperationArray
     * @param Bdc $bdc
     * @param $dataUpdate
     */
    private function addOperationBdc($bdcOperationArray, Bdc $bdc, $dataUpdate, $arrayNewOperation = null): void
    {
        if ($dataUpdate == null) {
            if (!empty($bdcOperationArray)) {
                foreach ($bdcOperationArray as $item) {
                    $dataOperation = new BdcOperation();
                    $this->addOperationBdcViaFront($dataOperation, $item);
                    $bdc->addBdcOperation($dataOperation);
                }
            }
        } else {
            # Pour la nouvelle diplucation du bdc
            if (!empty($dataUpdate->getBdcOperations())) {
                foreach ($dataUpdate->getBdcOperations() as $bdcOperation) {

                    $dataOperation = new BdcOperation();

                    $dataOperation->setTarif($bdcOperation->getTarif() ?? null);
                    $dataOperation->setCategorieLead($bdcOperation->getCategorieLead() ?? null);
                    $dataOperation->setDmt($bdcOperation->getDmt() ?? null);
                    $dataOperation->setIrm($bdcOperation->getIrm() ?? null);
                    $dataOperation->setObjectif($bdcOperation->getObjectif() ?? null);
                    $dataOperation->setPrixUnit($bdcOperation->getPrixUnit() ?? null);
                    $dataOperation->setProdParHeure($bdcOperation->getProdParHeure() ?? null);
                    $dataOperation->setQuantite($bdcOperation->getQuantite() ?? null);
                    $dataOperation->setSage($bdcOperation->getSage() ?? null);
                    $dataOperation->setSiRenta($bdcOperation->getSiRenta() ?? null);
                    $dataOperation->setTarifHoraireCible($bdcOperation->getTarifHoraireCible() ?? null);
                    $dataOperation->setTarifHoraireFormation($bdcOperation->getTarifHoraireFormation() ?? null);
                    $dataOperation->setTempsProductifs($bdcOperation->getTempsProductifs() ?? null);
                    $dataOperation->setVolumeATraite($bdcOperation->getVolumeATraite() ?? null);
                    $dataOperation->setUniqBdcFqOperation($bdcOperation->getUniqBdcFqOperation() ?? null);
                    $dataOperation->setDescription($bdcOperation->getDescription() ?? null);
                    $dataOperation->setIsHnoDimanche($bdcOperation->getIsHnoDimanche() ?? null);
                    $dataOperation->setIsHnoHorsDimanche($bdcOperation->getIsHnoHorsDimanche() ?? null);
                    $dataOperation->setMajoriteHnoDimanche($bdcOperation->getMajoriteHnoDimanche() ?? null);
                    $dataOperation->setMajoriteHnoHorsDimanche($bdcOperation->getMajoriteHnoHorsDimanche() ?? null);
                    $dataOperation->setValueHno($bdcOperation->getValueHno() ?? null);
                    $dataOperation->setOffert($bdcOperation->getOffert() ?? null);
                    $dataOperation->setDuree($bdcOperation->getDuree() ?? null);
                    $dataOperation->setRessourceFormer($bdcOperation->getRessourceFormer() ?? null);
                    $dataOperation->setNbHeureMensuel($bdcOperation->getNbHeureMensuel() ?? null);
                    $dataOperation->setNbEtp($bdcOperation->getNbEtp() ?? null);
                    $dataOperation->setProductiviteActe($bdcOperation->getProductiviteActe() ?? null);
                    $dataOperation->setQuantiteHeure($bdcOperation->getQuantiteHeure() ?? null);
                    $dataOperation->setQuantiteActe($bdcOperation->getQuantiteActe() ?? null);
                    $dataOperation->setPrixUnitaireActe($bdcOperation->getPrixUnitaireActe() ?? null);
                    $dataOperation->setPrixUnitaireHeure($bdcOperation->getPrixUnitaireHeure() ?? null);
                    $dataOperation->setDesignationActe($bdcOperation->getDesignationActe() ?? null);
                    $dataOperation->setIsParamPerformed(1);
                    $dataOperation->setEncodedImage($bdcOperation->getEncodedImage() ?? null);

                    if(empty($dataUpdate->getSignaturePackageId())){
                        if ($bdcOperation->getOldPrixUnit()){
                            $dataOperation->setOldPrixUnit($bdcOperation->getOldPrixUnit());
                        }
                        if($bdcOperation->getOldPrixUnitHeure()){
                            $dataOperation->setOldPrixUnitHeure($bdcOperation->getOldPrixUnitHeure());
                        }
                        if($bdcOperation->getOldPrixUnitActe()){
                            $dataOperation->setOldPrixUnitActe($bdcOperation->getOldPrixUnitHeure());
                        }
                    }

                    if ($bdcOperation->getBu() !== null) {
                        $dataOperation->setBu($this->getDoctrine()->getRepository(Bu::class)->find($bdcOperation->getBu()->getId()));
                    }

                    if ($bdcOperation->getOperation() !== null) {
                        $dataOperation->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($bdcOperation->getOperation()->getId()));
                    }

                    if ($bdcOperation->getFamilleOperation() !== null) {
                        $dataOperation->setFamilleOperation($this->getDoctrine()->getRepository(FamilleOperation::class)->find($bdcOperation->getFamilleOperation()->getId()));
                    }

                    if ($bdcOperation->getLangueTrt() !== null) {
                        $dataOperation->setLangueTrt($this->getDoctrine()->getRepository(LangueTrt::class)->find($bdcOperation->getLangueTrt()->getId()));
                    }

                    if ($bdcOperation->getTypeFacturation() !== null) {
                        $dataOperation->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($bdcOperation->getTypeFacturation()->getId()));
                    }

                    if ($bdcOperation->getCoutHoraire() !== null) {
                        $dataOperation->setCoutHoraire($this->getDoctrine()->getRepository(CoutHoraire::class)->find($bdcOperation->getCoutHoraire()->getId()));
                    }

                    if ($dataUpdate->getStatutLead() == intval($this->getParameter('statut_lead_bdc_valider_dir_fin'))) {
                        $dataOperation->setAvenant(1);
                    }

                    # Duplication des objectif quantitatifs avec ses indicateurs vers le nouveau ligne de facturation
                    if (!empty($bdcOperation->getObjectifQuantitatif())) {
                        # Duplication des objectifs quantitatifs
                        foreach ($bdcOperation->getObjectifQuantitatif() as $objQtf) {
                            $dataOperation->addObjectifQuantitatif($this->getDoctrine()->getRepository(ObjectifQuantitatif::class)->find($objQtf));
                        }

                        # Recuperation des indicateurs liées au ancien ligne de facturation
                        $getIndicatorsQt = $this->getDoctrine()->getRepository(IndicatorQuantitatif::class)->findBy(
                            ["bdcOperation" => $bdcOperation]
                        );

                        # Duplication des indicateurs
                        if (!empty($getIndicatorsQt)){
                            foreach ($getIndicatorsQt as $getedindicatorQt){
                                $indicatorQt = new IndicatorQuantitatif();

                                $indicatorQt->setObjectifQuantitatif($getedindicatorQt->getObjectifQuantitatif());
                                $indicatorQt->setIndicator($getedindicatorQt->getIndicator());
                                $indicatorQt->setUniqBdcFqOperation($getedindicatorQt->getUniqBdcFqOperation());

                                $dataOperation->addIndicatorQuantitatif($indicatorQt);
                            }
                        }
                    }

                    # Duplication des objectif quanlitatifs avec ses indicateurs vers le nouveau ligne de facturation
                    if (!empty($bdcOperation->getObjectifQualitatif())) {
                        # Duplication des objectifs quanlitatifs
                        foreach ($bdcOperation->getObjectifQualitatif() as $objQtt) {
                            $dataOperation->addObjectifQualitatif($this->getDoctrine()->getRepository(ObjectifQualitatif::class)->find($objQtt));
                        }

                        # Recuperation des indicateurs liées au ancien ligne de facturation
                        $getIndicatorsQl = $this->getDoctrine()->getRepository(IndicatorQualitatif::class)->findBy(
                            ["bdcOperation" => $bdcOperation]
                        );

                        # Duplication des indicateurs
                        if (!empty($getIndicatorsQl)){
                            foreach ($getIndicatorsQl as $getedindicatorQl){
                                $indicatorQl = new IndicatorQualitatif();

                                $indicatorQl->setObjectifQualitatif($getedindicatorQl->getObjectifQualitatif());
                                $indicatorQl->setIndicator($getedindicatorQl->getIndicator());
                                $indicatorQl->setUniqBdcFqOperation($getedindicatorQl->getUniqBdcFqOperation());

                                $dataOperation->addIndicatorQualitatif($indicatorQl);
                            }
                        }
                    }

                    $bdc->addBdcOperation($dataOperation);
                }
            }

            # Pour la nouvelle operation ajouté
            if ($dataUpdate->getStatutLead() == $this->getParameter('statut_lead_bdc_signe_client')){
                if (!empty($arrayNewOperation)){
                    foreach ($arrayNewOperation as $newItem) {
                        $dataOperation = new BdcOperation();
                        $this->addOperationBdcViaFront($dataOperation, $newItem);
                        $dataOperation->setAvenant(1);

                        $bdc->addBdcOperation($dataOperation);
                    }
                }
            } else {
                if (!empty($bdcOperationArray)) {
                    foreach ($bdcOperationArray as $item) {
                        if (isset($item['newOperation']) == "ok") {
                            $dataOperation = new BdcOperation();
                            $this->addOperationBdcViaFront($dataOperation, $item);
                            $dataOperation->setAvenant(1);

                            $bdc->addBdcOperation($dataOperation);
                        }
                    }
                }
            }
        }
    }

    private function addOperationBdc2($bdcOperationArray, Bdc $bdc, $dataUpdate, $arrayNewOperation = null,$DateAplicatif): void
    {
        # Pour la nouvelle diplucation du bdc
        if (!empty($dataUpdate->getBdcOperations())) {
            foreach ($dataUpdate->getBdcOperations() as $bdcOperation) {
                $Check_ok=0;
                $dataOperation = new BdcOperation();

                $dataOperation->setTarif($bdcOperation->getTarif() ?? null);
                $dataOperation->setCategorieLead($bdcOperation->getCategorieLead() ?? null);
                $dataOperation->setDmt($bdcOperation->getDmt() ?? null);
                $dataOperation->setIrm($bdcOperation->getIrm() ?? null);
                $dataOperation->setObjectif($bdcOperation->getObjectif() ?? null);
                foreach($arrayNewOperation as $newOperation){
                    if($bdcOperation->getId()===$newOperation->getIdOperation()){
                        $dataOperation->setPrixUnit($newOperation->getNouveauPrix() ?? null);
                        $Check_ok=1;
                    }
                }
                if($Check_ok===0){
                    $dataOperation->setPrixUnit($bdcOperation->getPrixUnit() ?? null);
                }
                $dataOperation->setProdParHeure($bdcOperation->getProdParHeure() ?? null);
                $dataOperation->setQuantite($bdcOperation->getQuantite() ?? null);
                $dataOperation->setSage($bdcOperation->getSage() ?? null);
                $dataOperation->setSiRenta($bdcOperation->getSiRenta() ?? null);
                $dataOperation->setTarifHoraireCible($bdcOperation->getTarifHoraireCible() ?? null);
                $dataOperation->setTarifHoraireFormation($bdcOperation->getTarifHoraireFormation() ?? null);
                $dataOperation->setTempsProductifs($bdcOperation->getTempsProductifs() ?? null);
                $dataOperation->setVolumeATraite($bdcOperation->getVolumeATraite() ?? null);
                $dataOperation->setUniqBdcFqOperation($bdcOperation->getUniqBdcFqOperation() ?? null);
                $dataOperation->setDescription($bdcOperation->getDescription() ?? null);
                $dataOperation->setIsHnoDimanche($bdcOperation->getIsHnoDimanche() ?? null);
                $dataOperation->setIsHnoHorsDimanche($bdcOperation->getIsHnoHorsDimanche() ?? null);
                $dataOperation->setMajoriteHnoDimanche($bdcOperation->getMajoriteHnoDimanche() ?? null);
                $dataOperation->setMajoriteHnoHorsDimanche($bdcOperation->getMajoriteHnoHorsDimanche() ?? null);
                $dataOperation->setValueHno($bdcOperation->getValueHno() ?? null);
                $dataOperation->setOffert($bdcOperation->getOffert() ?? null);
                $dataOperation->setDuree($bdcOperation->getDuree() ?? null);
                $dataOperation->setRessourceFormer($bdcOperation->getRessourceFormer() ?? null);
                $dataOperation->setNbHeureMensuel($bdcOperation->getNbHeureMensuel() ?? null);
                $dataOperation->setNbEtp($bdcOperation->getNbEtp() ?? null);
                $dataOperation->setProductiviteActe($bdcOperation->getProductiviteActe() ?? null);
                $dataOperation->setQuantiteHeure($bdcOperation->getQuantiteHeure() ?? null);
                $dataOperation->setQuantiteActe($bdcOperation->getQuantiteActe() ?? null);
                $dataOperation->setPrixUnitaireActe($bdcOperation->getPrixUnitaireActe() ?? null);
                $dataOperation->setPrixUnitaireHeure($bdcOperation->getPrixUnitaireHeure() ?? null);
                $dataOperation->setDesignationActe($bdcOperation->getDesignationActe() ?? null);
                $dataOperation->setIsParamPerformed(1);
                $dataOperation->setEncodedImage($bdcOperation->getEncodedImage() ?? null);
                $dataOperation->setAvenant($bdcOperation->getAvenant() ?? null);

                /*if(empty($dataUpdate->getSignaturePackageId())){
                    if ($bdcOperation->getOldPrixUnit()){
                        $dataOperation->setOldPrixUnit($bdcOperation->getOldPrixUnit());
                    }
                    if($bdcOperation->getOldPrixUnitHeure()){
                        $dataOperation->setOldPrixUnitHeure($bdcOperation->getOldPrixUnitHeure());
                    }
                    if($bdcOperation->getOldPrixUnitActe()){
                        $dataOperation->setOldPrixUnitActe($bdcOperation->getOldPrixUnitHeure());
                    }
                }*/

                if ($bdcOperation->getBu() !== null) {
                    $dataOperation->setBu($this->getDoctrine()->getRepository(Bu::class)->find($bdcOperation->getBu()->getId()));
                }

                if ($bdcOperation->getOperation() !== null) {
                    $dataOperation->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($bdcOperation->getOperation()->getId()));
                }

                if ($bdcOperation->getFamilleOperation() !== null) {
                    $dataOperation->setFamilleOperation($this->getDoctrine()->getRepository(FamilleOperation::class)->find($bdcOperation->getFamilleOperation()->getId()));
                }

                if ($bdcOperation->getLangueTrt() !== null) {
                    $dataOperation->setLangueTrt($this->getDoctrine()->getRepository(LangueTrt::class)->find($bdcOperation->getLangueTrt()->getId()));
                }

                if ($bdcOperation->getTypeFacturation() !== null) {
                    $dataOperation->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($bdcOperation->getTypeFacturation()->getId()));
                }

                if ($bdcOperation->getCoutHoraire() !== null) {
                    $dataOperation->setCoutHoraire($this->getDoctrine()->getRepository(CoutHoraire::class)->find($bdcOperation->getCoutHoraire()->getId()));
                }

                # Duplication des objectif quantitatifs avec ses indicateurs vers le nouveau ligne de facturation
                if (!empty($bdcOperation->getObjectifQuantitatif())) {
                    # Duplication des objectifs quantitatifs
                    foreach ($bdcOperation->getObjectifQuantitatif() as $objQtf) {
                        $dataOperation->addObjectifQuantitatif($this->getDoctrine()->getRepository(ObjectifQuantitatif::class)->find($objQtf));
                    }

                    # Recuperation des indicateurs liées au ancien ligne de facturation
                    $getIndicatorsQt = $this->getDoctrine()->getRepository(IndicatorQuantitatif::class)->findBy(
                        ["bdcOperation" => $bdcOperation]
                    );

                    # Duplication des indicateurs
                    if (!empty($getIndicatorsQt)){
                        foreach ($getIndicatorsQt as $getedindicatorQt){
                            $indicatorQt = new IndicatorQuantitatif();

                            $indicatorQt->setObjectifQuantitatif($getedindicatorQt->getObjectifQuantitatif());
                            $indicatorQt->setIndicator($getedindicatorQt->getIndicator());
                            $indicatorQt->setUniqBdcFqOperation($getedindicatorQt->getUniqBdcFqOperation());

                            $dataOperation->addIndicatorQuantitatif($indicatorQt);
                        }
                    }
                }

                # Duplication des objectif quanlitatifs avec ses indicateurs vers le nouveau ligne de facturation
                if (!empty($bdcOperation->getObjectifQualitatif())) {
                    # Duplication des objectifs quanlitatifs
                    foreach ($bdcOperation->getObjectifQualitatif() as $objQtt) {
                        $dataOperation->addObjectifQualitatif($this->getDoctrine()->getRepository(ObjectifQualitatif::class)->find($objQtt));
                    }

                    # Recuperation des indicateurs liées au ancien ligne de facturation
                    $getIndicatorsQl = $this->getDoctrine()->getRepository(IndicatorQualitatif::class)->findBy(
                        ["bdcOperation" => $bdcOperation]
                    );

                    # Duplication des indicateurs
                    if (!empty($getIndicatorsQl)){
                        foreach ($getIndicatorsQl as $getedindicatorQl){
                            $indicatorQl = new IndicatorQualitatif();

                            $indicatorQl->setObjectifQualitatif($getedindicatorQl->getObjectifQualitatif());
                            $indicatorQl->setIndicator($getedindicatorQl->getIndicator());
                            $indicatorQl->setUniqBdcFqOperation($getedindicatorQl->getUniqBdcFqOperation());

                            $dataOperation->addIndicatorQualitatif($indicatorQl);
                        }
                    }
                }

                $dataOperation->setApplicatifDate(\DateTime::createFromFormat('Y-m-d', $DateAplicatif));
                $tmp=0;

                foreach($arrayNewOperation as $newOp){
                    if($newOp->getIdOperation() === $bdcOperation->getId()){
                        if($bdcOperation->getTypeFacturation()->getId() === 7){
                            $dataOperation->setOldPrixUnitActe($bdcOperation->getPrixUnitaireActe());
                            $dataOperation->setOldPrixUnitHeure($bdcOperation->getPrixUnitaireHeure());

                            if($newOp->getNouveauPrixActe()){
                                $dataOperation->setPrixUnitaireActe($newOp->getNouveauPrixActe());
                            }

                            if($newOp->getNouveauPrixHeure()){
                                $dataOperation->setPrixUnitaireHeure($newOp->getNouveauPrixHeure());
                            }
                        } else {
                            $dataOperation->setOldPrixUnit($newOp->getAncienPrix());
                        }
                    }
                }

                $bdc->addBdcOperation($dataOperation);
            }
        }


    }

    /**
     * @param $dataOperation
     * @param $item
     */
    private function addOperationBdcViaFront($dataOperation, $item) {
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
            $dataOperation->setBu($this->getDoctrine()->getRepository(Bu::class)->find($item['bu']));
        }

        if (isset($item['operation'])) {
            $dataOperation->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($item['operation']));
        }

        if (isset($item['familleOperation'])) {
            $familleOperation = $this->getDoctrine()->getRepository(FamilleOperation::class)->find($item['familleOperation']);

            # Donner la valeur aux irm, siRenta, sage en fonction du valeur qui se trouve dans famille operation
            if (!empty($familleOperation)){
                $dataOperation->setSiRenta($familleOperation->getIsSiRenta() ?? null);
                $dataOperation->setIrm($familleOperation->getIsIrm() ?? null);
                $dataOperation->setSage($familleOperation->getIsSage() ?? null);

                $dataOperation->setFamilleOperation($familleOperation);
            }
        }

        if (isset($item['designationActe'])){
            $dataOperation->setDesignationActe($this->getDoctrine()->getRepository(Operation::class)->find($item['designationActe']) ?? null);
        }

        if (isset($item['langueTrt'])) {
            $dataOperation->setLangueTrt($this->getDoctrine()->getRepository(LangueTrt::class)->find($item['langueTrt']));
        }

        if (isset($item['typeFacturation'])) {
            $dataOperation->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($item['typeFacturation']));

            # Quantité
            $typeFacturation = intval($item['typeFacturation']) ?? null;

            if ($typeFacturation != null)  {
                $this->setPrixUnitaireAndQuantity($dataOperation, $item);
            }
        }

        if (isset($item['coutHoraire'])) {
            $dataOperation->setCoutHoraire($this->getDoctrine()->getRepository(CoutHoraire::class)->find($item['coutHoraire']));
        }

        if (isset($item['uniq'])) {
            $dataOperation->setUniqBdcFqOperation($item['uniq']);
        }

        if (!empty($item['objectifQuantitatif'])) {
            foreach ($item['objectifQuantitatif'] as $objQtf) {
                $dataOperation->addObjectifQuantitatif($this->getDoctrine()->getRepository(ObjectifQuantitatif::class)->find($objQtf));
            }
        }
        if (!empty($item['objectifQualitatif'])) {
            foreach ($item['objectifQualitatif'] as $objQtt) {
                $dataOperation->addObjectifQualitatif($this->getDoctrine()->getRepository(ObjectifQualitatif::class)->find($objQtt));
            }
        }

        # Ajouter ses objectifs et ses indicateurs dans la table IndicatorQualitatif
        if (!empty($item['indicateurQl'])){
            foreach ($item['indicateurQl'] as $indicQl) {
                $indicatorQl = new IndicatorQualitatif();

                $indicatorQl->setObjectifQualitatif($this->getDoctrine()->getRepository(ObjectifQualitatif::class)->find($indicQl['objectifQl']));
                $indicatorQl->setIndicator($indicQl['indicator'] ?? null);
                $indicatorQl->setUniqBdcFqOperation($value['uniq'] ?? null);

                $dataOperation->addIndicatorQualitatif($indicatorQl);
            }
        }

        # Ajouter ses objectifs et ses indicateurs dans la table IndicatorQuantitatif
        if (!empty($item['indicateurQt'])){
            foreach ($item['indicateurQt'] as $indicQt) {
                $indicatorQt = new IndicatorQuantitatif();

                $indicatorQt->setObjectifQuantitatif($this->getDoctrine()->getRepository(ObjectifQuantitatif::class)->find($indicQt['objectifQt']));
                $indicatorQt->setIndicator($indicQt['indicator'] ?? null);
                $indicatorQt->setUniqBdcFqOperation($value['uniq'] ?? null);

                $dataOperation->addIndicatorQuantitatif($indicatorQt);
            }
        }
    }

    /**
     * @param Bdc $currentBdc
     * @param $bdcArray
     * @param EntityManagerInterface $em
     * @param $id
     * @return mixed
     */
    private function updateInformationBdc(Bdc $currentBdc, $bdcArray, EntityManagerInterface $em, $id)
    {
        $currentBdc->setSocieteFacturation(isset($bdcArray['societeFacturation']) ? $this->getDoctrine()->getRepository(SocieteFacturation::class)->find($bdcArray['societeFacturation']) : NULL);
        $currentBdc->setPaysFacturation(isset($bdcArray['paysFact']) ? $this->getDoctrine()->getRepository(PaysFacturation::class)->find($bdcArray['paysFact']) : NULL);
        $currentBdc->setNumVersion(null);
        $currentBdc->setDevise(isset($bdcArray['devise']) ? $this->getDoctrine()->getRepository(Devise::class)->find($bdcArray['devise']) : NULL);
        $currentBdc->setTva(isset($bdcArray['tva']) ? $this->getDoctrine()->getRepository(Tva::class)->find($bdcArray['tva']) : NULL);
        $currentBdc->setModeReglement($bdcArray['modeReglement']);
        $currentBdc->setDelaisPaiment($bdcArray['delaisPaiment']);
        $currentBdc->setDescriptionGlobale($bdcArray['descriptionGlobale'] ?? null);

        if (empty($currentBdc->getNumVersion())){
            $currentBdc->setNumVersion($currentBdc->getId() . '_' . 'V' . 1 . '_' . date("Y-m-d"));
        }

        if ($id != null) {
            $currentBdc->setNumBdc($this->addValueToNumBdc($id));
            $currentBdc->setIdMere($id);
        }

        # Ajout destinataire signataire................
        $tabDestinaSign = [];
        if (!empty($bdcArray['destinataireSignataire'])) {
            foreach ($bdcArray['destinataireSignataire'] as $item) {
                $tabDestinaSign[] = $item;
            }
            $currentBdc->setDestinataireSignataire($tabDestinaSign);
        }

        # Ajout destinataire facture................
        $tabDestinaFacture = [];
        if (!empty($bdcArray['destinataireFacture'])) {
            foreach ($bdcArray['destinataireFacture'] as $item) {
                $tabDestinaFacture[] = $item;
            }
            $currentBdc->setDestinataireFacture($tabDestinaFacture);
        }

        $em->persist($currentBdc);
        $em->flush();
        return $bdcArray;
    }

    /**
     * @param $currentBdc
     * @return array
     */
    private function getUserConnecte($currentBdc): array
    {
        $resumeLeadObj = $currentBdc->getResumeLead();
        $resumeLead = $this->getDoctrine()->getRepository(ResumeLead::class)->find($resumeLeadObj);
        $clientObj = $resumeLead->getCustomer();

        $client = $this->getDoctrine()->getRepository(Customer::class)->find($clientObj);
        $userObj = $client->getUser();
        $user = $this->getDoctrine()->getRepository(User::class)->find($userObj);
        return array($client, $user, $clientObj);
    }

    /**
     * @param int $idBdc
     * @return string
     */
    private function addValueToNumBdc(int $idBdc): string
    {
        $currentBdc = $this->getDoctrine()->getRepository(Bdc::class)->find($idBdc);

        return $currentBdc->getPaysProduction()->getId().".".$currentBdc->getPaysFacturation()->getId().".".$currentBdc->getBdcOperations()[0]->getBu()->getId().".".$currentBdc->getResumeLead()->getCustomer()->getUser()->getId().".".$currentBdc->getId();
    }

    /**
     * @param $bdcResults
     * @param $serviceCaMensual
     * @param $dataFront
     * @return array
     */
    private function calculMensuality($bdcResults, $serviceCaMensual, $dataFront)
    {
        $finalData = [];

        # Si le bon de commande n'est pas vide
        foreach ($bdcResults as $bdcResult) {
            $pot12mois = null;
            $potCurrentYear = null;
            $totalHT = 0;

            #CA Mensuel
            $caMensuels = $serviceCaMensual->getCaMensuel($bdcResult->getBdcOperations());

            if ($bdcResult->getResumeLead()->getDureeTrt()->getId() == 2) {
                #CA Potentiel sur 12 mois
                $pot12mois = $caMensuels * 12;

                # CA Potentiel du mois en cours
                $potCurrentYear = $caMensuels * (12 - (date("m")));
            } elseif (number_format($bdcResult->getResumeLead()->getDureeTrt()->getId()) == 1) {

                #CA Potentiel sur 12 mois
                $pot12mois = $caMensuels;

                # CA Potentiel du mois en cours
                $potCurrentYear = $caMensuels * 12;
            }

            # Calcul montant
            foreach ($bdcResult->getBdcOperations() As $operation){
                if ($operation->getQuantite()) {
                    /**
                     * Si ligne fact est mixte,
                     * $totalHT = (prixActe * qteActe) + (prixHeure * qteHeure)
                     * sinon, $totalHT = prixUnitaire * quantite
                     */
                    if ($operation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_mixte')){
                        $totalHT += ($operation->getPrixUnitaireActe() * $operation->getQuantiteActe()) + ($operation->getPrixUnitaireHeure() * $operation->getQuantiteHeure());
                    } else {
                        $totalHT += $operation->getPrixUnit() * $operation->getQuantite();
                    }
                }
            }

            $finalProperty = [
                "id" => $bdcResult->getId(),
                "numBdc" => $bdcResult->getNumBdc(),
                "numVersion" => $bdcResult->getNumVersion(),
                "titre" => $bdcResult->getTitre(),
                "dateCreate" => $bdcResult->getDateCreate(),
                "societeFacturation" => $bdcResult->getSocieteFacturation(),
                "resumeLead" => $bdcResult->getResumeLead(),
                "paysProduction" => $bdcResult->getPaysProduction(),
                "margeCible" => $bdcResult->getMargeCible(),
                "statutLead" => $bdcResult->getStatutLead(),
                "idMere" => $bdcResult->getIdMere(),
                "caMensuels" => $caMensuels,
                "pot12mois" => $pot12mois,
                "potCurrentYear" => $potCurrentYear,
                "totalHT" => $totalHT
            ];

            if (!empty($dataFront["case"]) && in_array($dataFront["case"], [11, 12, 13])){
                $bdcValue = null;
                $searchValue = null;
                $sign = "";

                switch ($dataFront["case"]){
                    case 11:
                        $bdcValue = $caMensuels;
                        $searchValue = $dataFront["caMensuelSearch"];
                        $sign = $dataFront["caMensuelSign"];
                        break;
                    case 12:
                        $bdcValue = $pot12mois;
                        $searchValue = $dataFront["caPot12Search"];
                        $sign = $dataFront["caPot12Sign"];
                        break;
                    case 13:
                        $bdcValue = $potCurrentYear;
                        $searchValue = $dataFront["caPotCurrentYearSearch"];
                        $sign = $dataFront["caPotCurrentYearSign"];
                        break;
                }

                $isTrue = $this->comparaisonOfSearchValue($bdcValue, $searchValue, $sign);

                $isTrue && $finalData[] = $finalProperty;

            } else {
                $finalData[] = $finalProperty;
            }
        }

        return $finalData;
    }

    /**
     * @param $bdcValue
     * @param $searchValue
     * @param $sign
     * @return bool
     * Compare deux valeurs
     */
    private function comparaisonOfSearchValue($bdcValue, $searchValue, $sign){
        $res = false;

        if ($sign == "Sup"){
            if ($bdcValue > $searchValue){
                $res = true;
            }
        }

        if ($sign == "Inf"){
            if ($bdcValue < $searchValue){
                $res = true;
            }
        }

        return $res;
    }

    /* private function getNewStatutAndNotification($currentBdc): array
    {
        $objNotif = null;
        $respNotif = null;
        $twigNotif = null;

        $emailObject = 'Demande validation du Bon de commande numéro '. $currentBdc->getNumBdc() . ' pour la société '. $currentBdc->getResumeLead()->getCustomer()->getRaisonSocial();

        switch($currentBdc->getStatutLead())
        {
            case $this->getParameter('statut_lead_bdc_draft'): //Simple Modif
                $objNotif = $emailObject;
                $twigNotif = 'forValidationSuperior.html.twig';
                $newStatut = $this->getParameter('statut_lead_bdc_draft');
                $respNotif = "ROLE_DIRPROD";
                break;
            case $this->getParameter('statut_lead_bdc_rejeter_dir_prod'): // Rejeter par dir prod
                $objNotif = $emailObject;
                $twigNotif = 'forValidationSuperiorSuiteModifRejetct.html.twig';
                $newStatut = $this->getParameter('statut_lead_bdc_creer');
                $respNotif = "ROLE_DIRPROD";
                break;
            case $this->getParameter('statut_lead_bdc_rejeter_dir_fin'): // Rejeter par dir fin
                $objNotif = $emailObject;
                $twigNotif = 'forValidationSuperiorSuiteModifRejetct.html.twig';
                $newStatut = $this->getParameter('statut_lead_bdc_valider_dir_prod');
                $respNotif = "ROLE_FINANCE";
                break;
            case $this->getParameter('statut_lead_bdc_rejeter_dg'): // Rejeter par dg
                $objNotif = $emailObject;
                $twigNotif = 'forValidationSuperiorSuiteModifRejetct.html.twig';
                $newStatut = $this->getParameter('statut_lead_bdc_valider_dir_fin');
                $respNotif = "ROLE_DG";
                break;
            case $this->getParameter('statut_lead_bdc_signe_com'): // Signe par commercial (=Envoye au client)
                $objNotif = $emailObject;
                $twigNotif = 'forValidationSuperiorSuiteModifDemandeClt.html.twig';
                $newStatut = $this->getParameter('statut_lead_bdc_creer');
                $respNotif = "ROLE_DIRPROD";
                break;
            case $this->getParameter('statut_lead_bdc_signe_client'): // En production
            case $this->getParameter('statut_lead_bdc_avenant_signe_client'):
                $objNotif = "Avenant à valider";
                $twigNotif = "forValidationSuperiorSuiteCreationAvenant.html.twig";
                $newStatut = $this->getParameter('statut_lead_bdc_avenant_creer');
                $respNotif = "ROLE_DIRPROD";
                break;
            case $this->getParameter('statut_lead_bdc_avenant_rejeter_dir_prod'): // Avenant rejeté par dir prod
                $objNotif = "Avenant à valider";
                $twigNotif = "forValidationSuperiorSuiteModifRejetctAvenant.html.twig";
                $newStatut = $this->getParameter('statut_lead_bdc_avenant_creer');
                $respNotif = "ROLE_DIRPROD";
                break;
            case $this->getParameter('statut_lead_bdc_avenant_rejeter_dir_fin'): // Avenant rejeté par dir fin
                $objNotif = "Avenant à valider";
                $twigNotif = "forValidationSuperiorSuiteModifRejetctAvenant.html.twig";
                $newStatut = $this->getParameter('statut_lead_bdc_avenant_valider_dir_prod');
                $respNotif = "ROLE_FINANCE";
                break;
            case $this->getParameter('statut_lead_bdc_avenant_rejeter_dg'): // Avenant rejeté par dg
                $objNotif = "Avenant à valider";
                $twigNotif = "forValidationSuperiorSuiteModifRejetctAvenant.html.twig";
                $newStatut = $this->getParameter('statut_lead_bdc_avenant_valider_dir_fin');
                $respNotif = "ROLE_DG";
                break;
            case $this->getParameter('statut_lead_bdc_avenant_signe_com'): // Signe par commercial (=Envoye au client)
                $objNotif = $emailObject;
                $twigNotif = 'forValidationSuperiorSuiteModifDemandeClt.html.twig';
                $newStatut = $this->getParameter('statut_lead_bdc_avenant_creer');
                $respNotif = "ROLE_DIRPROD";
                break;
            default:
                $newStatut = $this->getParameter('statut_lead_bdc_draft');
        }

        return array($objNotif, $twigNotif, $newStatut, $respNotif);
    } */

    /**
     * @param Bdc $currentBdc
     * @return array
     */
    private function getNewStatutAndNotification(Bdc $currentBdc): array
    {
        $statutlead = $currentBdc->getStatutLead();

        # Trouver la position du statutlead du bdc parmi les statutlead bdc modifiable.
        $statutLeadPos = array_search($statutlead, $this->getParameter('statut_lead_bdc_before_edit'));

        # Récupération du nouveau statut
        $newStatut = $this->getParameter("statut_lead_bdc_after_edit")[$statutLeadPos];
        # Récupération du role de validateur à notifier
        $respNotif = $this->getParameter("role_to_notif_after_edit")[$statutLeadPos];

        # Objet de notification pour le validateur
        $objNotif = 'Demande validation du devis numéro '. $currentBdc->getNumBdc() . ' pour la société '. $currentBdc->getResumeLead()->getCustomer()->getRaisonSocial();

        # Le template twig qui contient la notification
        if ($statutlead == $this->getParameter("statut_lead_bdc_draft")){
            $twigNotif = "forValidationSuperior.html.twig";
        } elseif (in_array($statutlead, $this->getParameter("statut_lead_rejeter"))){ # Rejet normal
            $twigNotif = "forValidationSuperiorSuiteModifRejetct.html.twig";
        } elseif (in_array($statutlead, $this->getParameter("statut_lead_signed_by_commercial"))){ # Signé par commercial
            $twigNotif = "forValidationSuperiorSuiteModifDemandeClt.html.twig";
        } elseif (in_array($statutlead, $this->getParameter("statut_lead_bdc_in_prod"))){ # En production
            $twigNotif = "forValidationSuperiorSuiteCreationAvenant.html.twig";
        } elseif (in_array($statutlead, $this->getParameter("statut_lead_avenant_rejeter"))){ # Rejet avenant
            $twigNotif = "forValidationSuperiorSuiteModifRejetctAvenant.html.twig";
        } else {
            $twigNotif = null;
        }

        return array($objNotif, $twigNotif, $newStatut, $respNotif);
    }

    /**
     * @param $bdcOperation
     * @param $jsonResponse
     * @param $em
     * Après avoir mise à jour une opération Panne technique DO,
     * il faut mettre à jour aussi l'opération Panne technique outsourcia
     * Modification au niveau cout horaire ou profil agent
     */
    private function editOperationPanneTechniqueOutsourcia($bdcOperation, $jsonResponse, $em) {
        if ($bdcOperation->getOperation()->getId() == $this->getParameter('param_id_operation_heure_panne_technique')) {
            $idOperationPanneOut = $this->getParameter('param_id_operation_panne_technique_outsourcia'); // id de l'operation panne technique DO
            $dataLigneFacturationPanneOut = $this->getDoctrine()->getRepository(BdcOperation::class)->findOneBy([
                'operation' => $idOperationPanneOut,
                'bdc' => $bdcOperation->getBdc()->getId()
            ]);
            $dataLigneFacturationPanneOut->setCoutHoraire($this->getDoctrine()->getRepository(CoutHoraire::class)->find($jsonResponse['coutHoraire'] ?? null));
            $em->persist($dataLigneFacturationPanneOut);
            $em->flush();
        }
    }

    /**
     * @param $idBdc
     * @param $newLigneFact
     * Ajout nouvelle lead detail operation du FQ par rapport à la modification du BDC
     * Modification bdc (Cas ajout nouvelle ligne de facturation)
     */
    private function newLedDetailOperation($idBdc, $newLigneFact) {
        # On va recupérer le bdc en question
        $dataBdc = $this->getDoctrine()->getRepository(Bdc::class)->find($idBdc);

        # On va recupérer le FQ correspond à la bdc
        $fq = $this->getDoctrine()->getRepository(ResumeLead::class)->find($dataBdc->getResumeLead());

        # On va créer une nouvelle lead detail operation venant du bdc
        foreach ($newLigneFact as $ligneFact) {

            # Il faut chequer s'il ya une nouvelle ligne ajouté
            $newOperation = null;
            if (isset($ligneFact['newOperation'])) {
                $newOperation = $ligneFact['newOperation'];
            }

            if ($newOperation == "ok") {

                $leadDetailOperation = new LeadDetailOperation();

                if ($dataBdc->getPaysProduction() && $dataBdc->getPaysFacturation()) {
                    $leadDetailOperation->setPaysProduction($this->getDoctrine()->getRepository(PaysProduction::class)->find($dataBdc->getPaysProduction()));
                    $leadDetailOperation->setPaysFacturation($this->getDoctrine()->getRepository(PaysFacturation::class)->find($dataBdc->getPaysFacturation()));
                }

                $leadDetailOperation->setPrixUnit($ligneFact['prixUnit'] ?? null);

                # Champ obligatoire
                $leadDetailOperation->setLangueTrt($this->getDoctrine()->getRepository(LangueTrt::class)->find($ligneFact['langueTrt']));

                $leadDetailOperation->setCategorieLead($ligneFact['categorieLead'] ?? null);
                $leadDetailOperation->setVolumeATraite($ligneFact['volumeATraite'] ?? null);
                $leadDetailOperation->setNbHeureMensuel($ligneFact['nbHeureMensuel'] ?? null);
                $leadDetailOperation->setNbEtp($ligneFact['nbEtp'] ?? null);

                # Champ obligatoire
                $leadDetailOperation->setBu($this->getDoctrine()->getRepository(Bu::class)->find($ligneFact['bu']));
                $leadDetailOperation->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find($ligneFact['typeFacturation']));
                $leadDetailOperation->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($ligneFact['operation']));
                $leadDetailOperation->setHoraireProduction($this->getDoctrine()->getRepository(HoraireProduction::class)->find($ligneFact['horaireProduction']) ?? null);

                $leadDetailOperation->setTempsProductifs($ligneFact['tempsProductifs'] ?? null);
                $leadDetailOperation->setTarifHoraireCible($ligneFact['tarifHoraireCible'] ?? null);
                $leadDetailOperation->setDmt($ligneFact['dmt'] ?? null);
                $leadDetailOperation->setCoutHoraire($this->getDoctrine()->getRepository(CoutHoraire::class)->find($ligneFact['coutHoraire'] ?? null));

                if (intval($ligneFact['typeFacturation']) === $this->getParameter('param_id_type_fact_mixte')){
                    $leadDetailOperation->setProductiviteActe($ligneFact['productiviteActe'] ?? null);
                    $leadDetailOperation->setPrixUnitaireActe($ligneFact['prixUnitaireActe'] ?? null);
                    $leadDetailOperation->setPrixUnitaireHeure($ligneFact['prixUnitaireHeure'] ?? null);

                    if (isset($ligneFact['designationActe'])) {
                        $leadDetailOperation->setDesignationActe($this->getDoctrine()->getRepository(Operation::class)->find($ligneFact['designationActe']) ?? null);
                    }
                }

                # Champ obligatoire
                $leadDetailOperation->setFamilleOperation($this->getDoctrine()->getRepository(FamilleOperation::class)->find($ligneFact['familleOperation']));

                if (isset($ligneFact['uniq'])) {
                    $leadDetailOperation->setUniqBdcFqOperation($ligneFact['uniq']);
                }

                # Logique exceptionnel pour les deux objectifs
                if (!empty($ligneFact['objectifQualitatif'])) {
                    foreach ($ligneFact['objectifQualitatif'] as $objQual) {
                        $objectifQual = $this->getDoctrine()->getRepository(ObjectifQualitatif::class)->find($objQual);
                        $leadDetailOperation->addObjectifQualitatif($objectifQual);
                    }
                }
                if (!empty($ligneFact['objectifQuantitatif'])) {
                    foreach ($ligneFact['objectifQuantitatif'] as $objQuant) {
                        $objectifQuantitatif = $this->getDoctrine()->getRepository(ObjectifQuantitatif::class)->find($objQuant);
                        $leadDetailOperation->addObjectifQuantitatif($objectifQuantitatif);
                    }
                }

                $fq->addLeadDetailOperation($leadDetailOperation);
            }
        }

        $this->entityManager->persist($fq);
        $this->entityManager->flush();
    }

    /**
     * @param $dataLigneFact
     * @param $manager
     * Suppression lead detail opération associé à la ligne de facturation en question
     */
    private function deleteLeadDetailOperation ($dataLigneFact, $manager) {
        # On cherche leadDetailOperation associé
        $dataLeadDetailOperation = $this->getDoctrine()->getRepository(LeadDetailOperation::class)->findOneBy(['uniqBdcFqOperation' => $dataLigneFact->getUniqBdcFqOperation()]);

        # Puis on supprime
        if ($dataLeadDetailOperation) {
            $manager->remove($dataLeadDetailOperation);
            $manager->flush();
        }
    }

    /**
     * @param $bdcOperation
     * @param $jsonRecu
     * @param $manager
     * Mise à jour leadDetailOperation associé
     */
    private function updateLeadDetailOperation ($bdcOperation, $jsonRecu, $manager) {
        # On recupere d'abord lead detail operation associé
        $leadDetailOperation = $this->getDoctrine()->getRepository(LeadDetailOperation::class)->findOneBy(['uniqBdcFqOperation' => $bdcOperation->getUniqBdcFqOperation()]);

        # On va faire le mise à jour
        if ($leadDetailOperation) {
            $leadDetailOperation->setPrixUnit($jsonRecu['prixUnit'] ?? null);
            $manager->persist($leadDetailOperation);
            $manager->flush();
        }
    }

    private function updateStatutLead($id, $statut, $client, $lead) {
        # MAJ champ status lead dans la table Bdc
        $lead->updateStatusLeadBdc($id,$statut);

        $lead->updateStatusLeadByCustomer($client, $statut);

        # Ajout d'une ligne dans la table WorkflowLead
        $lead->addWorkflowLead($client, $statut);
    }

    /**
     * @Route ("view/pdf/bdc", name="view_pdf_bdc", methods={"GET"})
     * @return Response
     */
    public function showNewPdf(): Response {
        $data = "Bonjour Tramaki";
        return $this->render('bdc.v1.html.twig.back', [
            'dataTest' => $data
        ]);
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

    private function isMontantSuperiorToSeuil($currentBdc) {
        # Calcul montant
        $totalHT = 0;
        foreach($currentBdc->getBdcOperations() As $operation)
        {
            if (intval($operation->getQuantite()) != null) {
                $totalHT += $operation->getPrixUnit() * $operation->getQuantite();
            }
        }

        if ($totalHT > $this->getParameter('seuilToStartValidationProcess')) {
            return true;
        } else {
            return false;
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

    private function calculMontantBdc($currentBdc) {
        # Calcul montant
        $totalHT = 0;
        foreach($currentBdc->getBdcOperations() As $operation)
        {
            if (intval($operation->getQuantite()) != null) {
                $totalHT += $operation->getPrixUnit() * $operation->getQuantite();
            }
        }

        return $totalHT;
    }

/*
    private function getNewStatutForValidateBdc($statut) {
        switch ($statut)
        {
            case $this->getParameter('statut_lead_bdc_creer'):
                return $this->getParameter('statut_lead_bdc_valider_dir_prod');
                break;
            case $this->getParameter('statut_lead_bdc_avenant_creer'):
                return $this->getParameter('statut_lead_bdc_avenant_valider_dir_prod');
                break;
            case $this->getParameter('statut_lead_bdc_valider_dir_prod'):
                return $this->getParameter('statut_lead_bdc_valider_dir_fin');
                break;
            case $this->getParameter('statut_lead_bdc_avenant_valider_dir_prod'):
                return $this->getParameter('statut_lead_bdc_avenant_valider_dir_fin');
                break;
            case $this->getParameter('statut_lead_bdc_valider_dir_fin'):
                return $this->getParameter('statut_lead_bdc_valider_dg');
                break;
            case $this->getParameter('statut_lead_bdc_avenant_valider_dir_fin'):
                return $this->getParameter('statut_lead_bdc_avenant_valider_dg');
                break;
        }
    }
	*/

    /**
     * @param BdcOperation $bdcOperation
     * @param $ligneFacturationFront
     * @param int|null $isInProdBdcOperation
     * Mis à jour le prix unitaire d'une ligne de facturation
     */
    private function updatePrixUnitOfLigneFact(BdcOperation $bdcOperation, $ligneFacturationFront, int $isInProdBdcOperation = null) {
        list($quantite, $quantiteActe, $quantiteHeure) = $this->getQuantityOfLignFact($bdcOperation, $ligneFacturationFront);

        # Si type de facturation est égal à acte, alors on refait le calcul du prix unit à partir du tarifHoraireCible, tempsProductifs, et dmt
        if ($bdcOperation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_acte')){
            # Enregistrer de l'ancien prix unitaire dans l'attribut oldPrixUnit
            if (!empty($isInProdBdcOperation)){
                $bdcOperation->setOldPrixUnit($bdcOperation->getPrixUnit());
            }

            # Verification si le tarifHoraireCible, tempsProductifs et dmt existe bien
            if (isset($ligneFacturationFront['tarifHoraireCible']) && isset($ligneFacturationFront['tempsProductifs']) && isset($ligneFacturationFront['dmt'])){
                # Calcul du nouveau prix unitaire
                // $newPrixUnit = $ligneFacturationFront['tarifHoraireCible'] / ($this->getTimeToNumber($ligneFacturationFront['tempsProductifs']) / $this->getTimeToNumber($ligneFacturationFront['dmt']));

                # On met à jour les tarifHoraireCible, tempsProductifs, dmt et prix unitaire
                $bdcOperation->setTarifHoraireCible($ligneFacturationFront['tarifHoraireCible']);
                $bdcOperation->setTempsProductifs($ligneFacturationFront['tempsProductifs']);
                $bdcOperation->setDmt($ligneFacturationFront['dmt']);
                $bdcOperation->setPrixUnit($ligneFacturationFront['prixUnit']);
            }

            $bdcOperation->setQuantite($quantite);
        } elseif ($bdcOperation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_mixte')) {
            # Enregistrer de l'ancien prix unitaire dans l'attribut oldPrixUnit
            if (!empty($isInProdBdcOperation)){
                $bdcOperation->setOldPrixUnitActe($bdcOperation->getPrixUnitaireActe());
                $bdcOperation->setOldPrixUnitHeure($bdcOperation->getPrixUnitaireHeure());
            }

            if (!empty($ligneFacturationFront['nbEtp']) && !empty($ligneFacturationFront['nbHeureMensuel']) && !empty($ligneFacturationFront['productiviteActe'])) {
                /**
                 * quantiteActe = (nbEtp * nbHeureMensuel) * productiviteActe
                 */
                $bdcOperation->setQuantiteActe($quantiteActe);

                /**
                 * quantiteHeure = nbEtp * nbHeureMensuel
                 */
                $bdcOperation->setQuantiteHeure($quantiteHeure);
            }

            $bdcOperation->setProductiviteActe($ligneFacturationFront['productiviteActe'] ?? null);
            $bdcOperation->setPrixUnitaireHeure($ligneFacturationFront['prixUnitaireHeure'] ?? null);
            $bdcOperation->setPrixUnitaireActe($ligneFacturationFront['prixUnitaireActe'] ?? null);
        } else {
            # Enregistrer l'ancien prix unitaire dans l'attribut oldPrixUnit
            if (!empty($isInProdBdcOperation)){
                $bdcOperation->setOldPrixUnit($bdcOperation->getPrixUnit());
            }

            # Si le type de facturation n'est pas acte, alors on met à jour directement son prix unitaire via le prix unitaire venant du front.
            if (isset($ligneFacturationFront['prixUnit'])) {
                $bdcOperation->setPrixUnit($ligneFacturationFront['prixUnit']);
            }

            $bdcOperation->setQuantite($quantite ?? null);
        }
    }

    /**
     * @param BdcOperation $bdcOperation
     * @param $ligneFacturationFront
     * Mis à jour le prix unitaire des lignes de facturations HNO
     */
    private function updatePrixUnitOfLigneFacturationHNO(BdcOperation $bdcOperation, $ligneFacturationFront){
        # Prend les lignes de facturation HNO
        $hnoBdcOperations = $this->getDoctrine()->getRepository(BdcOperation::class)->findBdcOperationChild($bdcOperation->getId(), $bdcOperation->getOperation()->getId(), $bdcOperation->getBdc()->getId());

        # MAJ prix unitaire des lignes facturations HNO
        if (!empty($hnoBdcOperations)) {
            foreach ($hnoBdcOperations as $hnoBdcOperation) {
                $nouvellePrixUnitMere = null;

                # Modifier la valeur du Prix Unitaire en fonction du type de facturation du mere et des HNO
                if ($bdcOperation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_mixte')) {
                    if ($hnoBdcOperation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_acte')){
                        $nouvellePrixUnitMere = $ligneFacturationFront['prixUnitaireActe'];
                    }
                    if ($hnoBdcOperation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_heure')){
                        $nouvellePrixUnitMere = $ligneFacturationFront['prixUnitaireHeure'];
                    }
                } else {
                    $nouvellePrixUnitMere = $ligneFacturationFront['prixUnit'];
                }

                # Mis à jour PU des lignes de facturations HNO.
                if ($hnoBdcOperation->getIsHnoHorsDimanche() == 1 && !empty($hnoBdcOperation->getMajoriteHnoHorsDimanche())) {
                    $unitPrice = round(((($hnoBdcOperation->getMajoriteHnoHorsDimanche() * $nouvellePrixUnitMere) / 100) + $nouvellePrixUnitMere), 2);
                    $hnoBdcOperation->setPrixUnit($unitPrice);
                }

                if ($hnoBdcOperation->getIsHnoDimanche() == 1 && !empty($hnoBdcOperation->getMajoriteHnoDimanche())) {
                    $unitPrice = round(((($hnoBdcOperation->getMajoriteHnoDimanche() * $nouvellePrixUnitMere) / 100) + $nouvellePrixUnitMere), 2);
                    $hnoBdcOperation->setPrixUnit($unitPrice);
                }

                $this->entityManager->persist($hnoBdcOperation);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @param $operationArray
     * @param $em
     * Le param operationArray correspond au tableau d'object contenant les bdcOperations venant du front
     */
    private function setTarifForUpdatedLignFact($createdBdc, $operationArray, $em){
        foreach ($operationArray as $ligneFacturation){
            # Si isTarifEdited existe et egal à 1, alors on fait la modication des tarifs
            foreach ($createdBdc->getBdcOperations() as $lignFact) {
                if ($lignFact->getOperation()->getId() == $ligneFacturation['operation'] && $lignFact->getIsHnoHorsDimanche() == null && $lignFact->getIsHnoDimanche() == null){

                    # Si type de facturation est égal à acte, alors on refait le calcul du prix unit à partir du tarifHoraireCible, tempsProductifs, et dmt
                    if ($lignFact->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_acte')){
                        # Enregistrer de l'ancien prix unitaire dans l'attribut oldPrixUnit
                        $lignFact->setOldPrixUnit($lignFact->getPrixUnit());

                        # Verification si le tarifHoraireCible, tempsProductifs et dmt existe bien
                        if (isset($ligneFacturation['tarifHoraireCible']) && isset($ligneFacturation['tempsProductifs']) && isset($ligneFacturation['dmt'])){
                            # On met à jour les tarifHoraireCible, tempsProductifs, dmt et prix unitaire
                            $lignFact->setTarifHoraireCible($ligneFacturation['tarifHoraireCible'] ?? null);
                            $lignFact->setTempsProductifs($ligneFacturation['tempsProductifs']);
                            $lignFact->setDmt($ligneFacturation['dmt']);
                            $lignFact->setPrixUnit($ligneFacturation['prixUnit']);
                        }
                    } elseif ($lignFact->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_mixte')) {
                        $lignFact->setTarifHoraireCible($ligneFacturation['tarifHoraireCible'] ?? null);
                        $lignFact->setProductiviteActe($ligneFacturation['productiviteActe'] ?? null);

                        # Enregistrer de l'ancien prix unitaire dans l'attribut oldPrixUnit
                        if ($lignFact->getPrixUnitaireActe() != $ligneFacturation['prixUnitaireActe']){
                            $lignFact->setOldPrixUnitActe($lignFact->getPrixUnitaireActe());
                            $lignFact->setPrixUnitaireActe($ligneFacturation['prixUnitaireActe'] ?? null);
                        }

                        if ($lignFact->getPrixUnitaireHeure() != $ligneFacturation['prixUnitaireHeure']){
                            $lignFact->setOldPrixUnitHeure($lignFact->getPrixUnitaireHeure());
                            $lignFact->setPrixUnitaireHeure($ligneFacturation['prixUnitaireHeure'] ?? null);
                        }

                        # Calcul quantite acte et heure
                        if (!empty($ligneFacturation['nbEtp']) && !empty($ligneFacturation['nbHeureMensuel']) && !empty($ligneFacturation['productiviteActe'])) {
                            /**
                             * quantiteActe = (nbEtp * nbHeureMensuel) * productiviteActe
                             */
                            $quantiteActe = ($ligneFacturation['nbEtp'] * $ligneFacturation['nbHeureMensuel']) * $ligneFacturation['productiviteActe'];
                            $lignFact->setQuantiteActe($quantiteActe);
                        }

                        if (!empty($ligneFacturation['nbEtp']) && !empty($ligneFacturation['nbHeureMensuel'])) {
                            /**
                             * quantiteHeure = nbEtp * nbHeureMensuel
                             */
                            $quantiteHeure = $ligneFacturation['nbEtp'] * $ligneFacturation['nbHeureMensuel'];
                            $lignFact->setQuantiteHeure($quantiteHeure);
                        }
                    } else {
                        # Enregistrer de l'ancien prix unitaire dans l'attribut oldPrixUnit
                        $lignFact->setOldPrixUnit($lignFact->getPrixUnit());

                        # Si le type de facturation n'est pas acte, alors on met à jour directement son prix unitaire via le prix unitaire venant du front.
                        if (isset($ligneFacturation['prixUnit'])) {
                            $lignFact->setPrixUnit($ligneFacturation['prixUnit'] ?? null);
                        }
                    }

                    # Mis à jour prix unitaire des lignes facturations HNO si la ligne de facturation est HNO
                    if ($lignFact->getValueHno() == "Oui") {
                        # Prend les lignes de facturation HNO
                        $hnoBdcOperations = $this->getDoctrine()->getRepository(BdcOperation::class)->findBdcOperationChild($lignFact->getId(), $lignFact->getOperation()->getId(), $createdBdc->getId());

                        # MAJ prix unitaire des lignes facturations HNO
                        if (!empty($hnoBdcOperations)) {
                            foreach ($hnoBdcOperations as $hnoBdcOperation) {
                                $nouvellePrixUnitMere = null;

                                # Modifier la valeur du PU mere en fonction du type de facturation du mere et des HNO
                                if ($lignFact->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_mixte')) {
                                    if ($hnoBdcOperation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_acte')){
                                        $nouvellePrixUnitMere = $ligneFacturation['prixUnitaireActe'];
                                    }
                                    if ($hnoBdcOperation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_heure')){
                                        $nouvellePrixUnitMere = $ligneFacturation['prixUnitaireHeure'];
                                    }
                                } else {
                                    $nouvellePrixUnitMere = $ligneFacturation['prixUnit'];
                                }

                                # Mis à jour PU des lignes de facturations HNO.
                                if ($hnoBdcOperation->getIsHnoHorsDimanche() == 1 && !empty($hnoBdcOperation->getMajoriteHnoHorsDimanche())) {
                                    $unitPrice = round((($hnoBdcOperation->getMajoriteHnoHorsDimanche() * $nouvellePrixUnitMere) / 100 + $nouvellePrixUnitMere), 2);
                                    $hnoBdcOperation->setPrixUnit($unitPrice);
                                    $em->persist($hnoBdcOperation);
                                } elseif ($hnoBdcOperation->getIsHnoDimanche() == 1 && !empty($hnoBdcOperation->getMajoriteHnoDimanche())) {
                                    $unitPrice = round((($hnoBdcOperation->getMajoriteHnoDimanche() * $nouvellePrixUnitMere) / 100 + $nouvellePrixUnitMere), 2);
                                    $hnoBdcOperation->setPrixUnit($unitPrice);
                                    $em->persist($hnoBdcOperation);
                                }
                            }
                        }
                    }

                    $lignFact->setApplicatifDate($ligneFacturation['applicatifDate'] ? new \DateTime($ligneFacturation["applicatifDate"]) : null);

                    $em->persist($lignFact);
                    $em->flush();
                }
            }
        }
    }

    /**
     * @param $oneOperationArray
     * @param $dataUpdate
     * @return Bdc
     */
    private function NewBonCommandeForNewLignFact($dataUpdate, $oneOperationArray): Bdc
    {
        $paysProdBdc = $oneOperationArray[0]['paysProduction'];
        $paysFactBdc = $oneOperationArray[0]['paysFacturation'];

        $bdc = new Bdc();
        $bdc->setDateDebut($dataUpdate->getDateDebut() ?? null);
        $bdc->setAdresseFacturation($dataUpdate->getAdresseFacturation() ?? null);
        $bdc->setCdc($dataUpdate->getCdc() ?? null);
        $bdc->setCgv($dataUpdate->getCgv() ?? null);
        $bdc->setDateCreate(new \DateTime());
        $bdc->setDateModification(new \DateTime());
        $bdc->setDateFin($dataUpdate->getDateFin() ?? null);
        $bdc->setDiffusions($dataUpdate->getDiffusions() ?? null);
        $bdc->setPaysProduction($this->getDoctrine()->getRepository(PaysProduction::class)->find($paysProdBdc ?? $dataUpdate->getPaysProduction()));
        $bdc->setTitre($dataUpdate->getTitre() ?? null);
        $bdc->setDescriptionGlobale($dataUpdate->getDescriptionGlobale() ?? null);
        $bdc->setResumePrestation($dataUpdate->getResumePrestation() ?? null);
        $bdc->setResumeLead($this->getDoctrine()->getRepository(ResumeLead::class)->find($dataUpdate->getResumeLead()));
        $bdc->setPaysFacturation($this->getDoctrine()->getRepository(PaysFacturation::class)->find($paysFactBdc ?? $dataUpdate->getPaysFacturation()));
        $bdc->setSocieteFacturation($this->getDoctrine()->getRepository(SocieteFacturation::class)->find($dataUpdate->getSocieteFacturation()));
        $bdc->setUniqId(uniqid());

        $this->entityManager->persist($bdc);
        $this->entityManager->flush();

        return $bdc;
    }

    /**
     * @param Bdc $actualBdc
     * @param $lignFactToCreateBdc
     * @return array
     * Crée les nouvelle bon de commande à partir des nouvelles lignes de facturation ajoutés
     */
    private function createNewBdcOfAvenant(Bdc $actualBdc, $lignFactToCreateBdc): array
    {
        $idDevisAddedViaNewLignFact = [];

        if (!empty($lignFactToCreateBdc)){
            $filterLF = new FilterLigneFacturation();

            # regroupe tout les nouveaux lignes de facturations par pays de production
            list($keyOfPaysProd, $result) = $filterLF->group_by("paysProduction", $lignFactToCreateBdc);

            if (!empty($keyOfPaysProd)) {
                foreach ($keyOfPaysProd as $num){
                    # Ensemble des lignes de facturation de meme pays de production
                    $bdcOperationArray = $result[$num];

                    # Creation du nouvel bon de commande
                    $newBdc = $this->NewBonCommandeForNewLignFact($actualBdc, $bdcOperationArray);

                    # Creation des nouveaux lignes de facturations manuelles
                    $this->createNewLignFactAndLeadDetailOperation($newBdc, $bdcOperationArray);

                    # Ajout nouvelle ligne fact hno dimanche et hors dimanche
                    $this->saveNewLigneFacturationHnoDimancheAndHorDimanche($newBdc->getId());

                    # Ajout opération automatique
                    $this->ajoutOperationAutomatique($newBdc->getId(), $bdcOperationArray);

                    $this->entityManager->persist($newBdc);
                    $this->entityManager->flush();

                    $idDevisAddedViaNewLignFact[] = $newBdc->getId();
                }
            }
        }

        return $idDevisAddedViaNewLignFact;
    }

    /**
     * @param $idBdc
     * Ajout nouvelle ligne de facturation HNO (dimance et hors dimanche)
     */
    private function saveNewLigneFacturationHnoDimancheAndHorDimanche($idBdc) {
        # On a besoin l'id du bon de commande en question
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
                if ($value->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_mixte')) {
                    # Ajout des lignes des facturations HNO pour typeFact mixte (nb = 4)
                    for ($j = 0; $j < 4; $j++) {
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
            $this->entityManager->persist($bonDeCommande);
            $this->entityManager->flush();
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
     * @param $idBdc
     * @param $lignFact
     */
    private function ajoutOperationAutomatique($idBdc, $lignFact) {
        $tabIdOperationFormationAndPanneTech = $this->getParameter('param_id_operation_formation_panne_technique');

        # On a besoin d'ID du bon de commande à créer
        $dataBdc = $this->getDoctrine()->getRepository(Bdc::class)->find($idBdc);

        # Ajout operation formation et panne technique uniquement pour langue de traiment ajouté
        if (!empty($dataBdc)) {
            foreach ($tabIdOperationFormationAndPanneTech as $formationAndPanneTech) {
                $ligneFacturation = new BdcOperation();
                $ligneFacturation->setTypeFacturation($this->getDoctrine()->getRepository(TypeFacturation::class)->find(3));
                $ligneFacturation->setOperation($this->getDoctrine()->getRepository(Operation::class)->find($formationAndPanneTech));
                $ligneFacturation->setBu($this->getDoctrine()->getRepository(Bu::class)->find($lignFact[0]["bu"]));
                $ligneFacturation->setLangueTrt($this->getDoctrine()->getRepository(LangueTrt::class)->find($lignFact[0]["langueTrt"]));
                $ligneFacturation->setIsParamPerformed(0);
                $dataBdc->addBdcOperation($ligneFacturation);
            }
            $this->entityManager->persist($dataBdc);
            $this->entityManager->flush();
        }

        # Logique ajout des operations automatique
        $this->extractedOpAuto($this->getParameter('param_id_operation_automatique'), $dataBdc);
    }

    /**
     * @param $tabIdOperation
     * @param $dataBdc
     */
    private function extractedOpAuto($tabIdOperation, $dataBdc): void
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
            }
        }

        $this->entityManager->persist($dataBdc);
        $this->entityManager->flush();
    }

    private function createNewLignFactAndLeadDetailOperation(Bdc $currentBdc, $operationArray){
        # Logique nouvelle ligne de facturation ajouté
        $newLigneFact = [];
        foreach ($operationArray as $value) {
            if (isset($value['newOperation']) == "ok") {
                $newLigneFact[] = $value;
            }
        }

        # Ajout nouvelle ligne de facturation
        if (!empty($newLigneFact)) {
            $this->addOperationBdc($newLigneFact, $currentBdc, null);
        }

        if (!in_array($currentBdc->getStatutLead(), $this->getParameter('statut_lead_avenant_rejeter'))) {
            # On va faire une nouvelle ajout aussi dans leadDetailOperation FQ
            $this->newLedDetailOperation($currentBdc->getId(), $operationArray);
        }
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
     * @param Bdc $bdcToValidate
     * @return string
     * return l'objet de notification pour les validateurs
     * que se soit dirprod, daf, dg
     */
    private function getEmailObjectOfValidator(Bdc $bdcToValidate): string
    {
        $numBdc = $bdcToValidate->getNumBdc();
        $client = $bdcToValidate->getResumeLead()->getCustomer()->getRaisonSocial();

        return "Demande validation du devis numéro $numBdc pour la société $client";
    }

    /**
     * @param BdcOperation $bdcOperation
     * @param $jsonResponse
     * @return array
     */
    private function getQuantityOfLignFact(BdcOperation $bdcOperation, $jsonResponse = null): array
    {
        $quantite = null;
        $quantiteActe = null;
        $quantiteHeure = null;

        $operationId = $bdcOperation->getOperation()->getId();

        if ($jsonResponse){
            $duree = $jsonResponse['Duree'] ?? null;
            $ressourceFormer = $jsonResponse['ressourceFormer'] ?? null;
            $nbHeureMensuel = $jsonResponse['nbHeureMensuel'] ?? null;
            $nbEtp = $jsonResponse['nbEtp'] ?? null;
            $volumeMensuel = $jsonResponse['volumeATraite'] ?? null;
            $productiviteActe = $jsonResponse['productiviteActe'] ?? null;
        } else {
            $duree = $bdcOperation->getDuree() ?? null;
            $ressourceFormer = $bdcOperation->getRessourceFormer() ?? null;
            $nbHeureMensuel = $bdcOperation->getNbHeureMensuel() ?? null;
            $nbEtp = $bdcOperation->getNbEtp() ?? null;
            $volumeMensuel = $bdcOperation->getVolumeATraite() ?? null;
            $productiviteActe = $bdcOperation->getProductiviteActe() ?? null;
        }

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

    /**
     * @param BdcOperation $bdcOperationMere
     * @param BdcOperation $actualBdcOperation
     * @return int
     * Retourne le prix unitaire du ligne de facturation mère en fonction de son type de facturation
     */
    private function getPrixUnitMere(BdcOperation $bdcOperationMere, BdcOperation $actualBdcOperation): int
    {
        $prixUnitMere = null;

        /**
         * Verification du type de facturation mere
         * s'il est mixte,
         * on prend prix unitaire acte pour le bdcOperation (Courant) de type acte
         * on prend prix unitaire heure pour le bdcOperation (Courant) de type heure
         * s'il n'est pas mixte, alors on prend juste la valeur du champs prix unitaire classique
         */
        if ($bdcOperationMere->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_mixte')){
            if ($actualBdcOperation->getTypeFacturation()->getId() === $this->getParameter('param_id_type_fact_acte')){
                $prixUnitMere = $bdcOperationMere->getPrixUnitaireActe();
            }

            if ($actualBdcOperation->getTypeFacturation()->getId() === $this->getParameter('param_id_type_fact_heure')){
                $prixUnitMere = $bdcOperationMere->getPrixUnitaireHeure();
            }
        } else{
            $prixUnitMere = $bdcOperationMere->getPrixUnit();
        }

        return $prixUnitMere;
    }

    /**
     * @Route("/get/histov3/via/idM/{idM}", name="getHistoV3" ,methods={"GET"})
     */
    public function getHistoV3(int $idM,BdcRepository $bdcRepository,BdcOperationRepository $bdcOperationRepository):Response
    {
        try {
            //get IdM->Maka Zanany
            $result = $bdcRepository->findBy([
                "idMere" => $idM
            ]);

            $solution = $bdcRepository->getIdByIdM($idM);
            $operation=array();
            foreach ($solution as $s){
                $vers=$s->getNumVersion();
                $version=explode("_",$vers);
                $i=0;

                foreach ($version as $v){
                    if($i ==1 )
                        $reponse =  substr($v,1);
                    $i++;
                }
                $operation[$reponse]=$s->getBdcOperations();
            }
            $valiny=array();
            $tableaustring=array();
            $xtab=array();
            $test=0;
            foreach ($operation as $i=>$op){
                //Ajout
                $test++;
                //dd($op);
                /*if($test>1){
                    if($op->getFamilleOperation()){
                        $libel=$op->getFamilleOperation()->getLibelle();
                        $tableaustring["$test Ajout"]="Le daty, Ajout d'un Operation $libel";
                    }
                }*/
                $xtab[]=count($op);
            }
            return $this->json($tableaustring, 200, [], ['groups' => ['get-by-bdc']]);
        }
        catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }

    }
}
