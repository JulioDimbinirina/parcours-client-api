<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/api")
 */
class RaisonSocialEditController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/edit/raison/social", name="raisonEdit" ,methods={"POST"})
     */
    public function raisonEdit(CustomerRepository $cusRep){
        try{
            $result=$cusRep->findAll();
            $exel=array();
            $exel = ["Code Clients", "CADJ","CADMS","CADVANCE","CADVANCEBUR","CAGEFOS","CASOL","CAUDIKA","CAUTOJOU","CAXA","CCAPC","CCAPCONTACTSERVIC","CCHASSE","CCHASSEU"];
            $resultat = [];
            foreach ($result as $customer){
                $rescust = [];
                foreach ($exel as $ex){
                    similar_text($customer->getRaisonSocial(),$ex,$pourcent);
                    if($pourcent>60){
                        $rescust[]=$customer->getRaisonSocial();
                    }
                }
                $resultat[$customer->getRaisonSocial()]=$rescust;

            }
            return $this->json($resultat, 200, [], ['groups' => ['get-by-bdc']]);
        }
        catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }
        /**
         * @Route("/edit/raison/social", name="raisonEdit2" ,methods={"GET"})
         */
        public function raisonEdit2(CustomerRepository $cusRep){
            try{
                $result=$cusRep->findAll();
                $exel=array();
                $exel = ["AMI DES JARDINS", "ADMS","ADVANCE BUREAUTIQUE","ADVANCE BUREAUTIQUE","AGEFOS PME IDF","AS-COM SOLIDAIRE","AUDIKA GROUPE","AUTO JOURNAL","AUTOPLUS","AXA ASSURCREDIT","AS-COM CENTRE","CAP CONTACT SERVICES","REVUE NATIONALE DE LA CHASSE","CHASSEUR FRANCAIS"
                ,"COFACE","COFACE BELGIQUE","COFACE SERVICES","COYOTE SYSTEMS BENELUX","COYOTE SYSTEM","EDIFA","EGN TFN","ESA","OPCO ATLAS"
                ,"LIVELLE","AA DIGITAL Ltd","ADL PARTNER","AFDAS","Atlas for Men","APRIL CONTACT","Aquarelle.com","AROBIZ","AUSHOPPING","AVOTECH","BeBetter&Co","CAMPINGS.COM","CARFUEL","COVERLYS","CRITIZR","ECOTRI","ENACO","FIDDLER","FREE","GOCATER","GRDFMED","GrDF","GRDF OUEST","HAPPN","THOM","Infopresse","JURATOYS"
                ,"BIS RENOVATION"
                ];
                $texte="ADL PARTNER
ADLP ASSURANCES
ADLP TELESURVEILLANCE
ADVANCE BUREAUTIQUE
AFDAS
Atlas  Menqstr
APRIL CONTACT
Aquarelle.com
AQUARELLE COFFRET
AROBIZ
AS COM
AS COM CENTRE
XXXXXXX
ASSISTANCE COMMUNICATION
AS Partners SA
Editions Atlas
AUSHOPPING
AUTO ECOLE.NET
AVOTECH
PREVAAL CONSEIL
BAOBAZ SAS
B ASSUR
BAUER MEDIA FRANCE
BeBetter&Co
BRANDALLEY SA
BUFFALO GRILL
CAMPINGS.COM
CAMPINGS.COM UNITED LTD 
CareerBuilder France 
CARFUEL
ONLINE CARREFOUR
CASAL SPORT
TOUCHVIBES / CCM Performance
CHD EXPERT GROUP
LE CNAM - AGENCE COMPTABLE
COURTEPAILLE
COVERASSUR
COVERLYS
COYOTE SYSTEM
COYOTE SYSTEMS BENELUX
COYOTE SYSTEM
COYOTE SYSTEM
CRITIZR
DATAWORDS-DATASIA
DEBONIX FRANCE
DES BRAS EN PLUS
SARL DIGITZ
EAU DE PARIS
Prunelle Marketing
ECOTRI
Editions Atlas
Editions Atlas SA
INFORMACION NUMEROS DE TELEFONO SL
EDITIONS MONDADORI AXEL SPRINGING
ENACO
E.Q.C.M
F2C SOLUTION
Fédération Française pour le Don de Sang Bénévole
FIDDLER
Le Fongecif Bretagne
FREE
Gaz réseau Distribution France
GDF SUEZ
GECODIS SA
GEMSTAR BRANDS
GFK Retail & Technology
GfK Consumer Choices France
GOCATER
GRDFMED
GrDF
GrDF Direction Développement
GRDF EST Pôle Marketing
GRDF EST ENTREPRISE
GRDF IDF Pôle Marketing
Direction Clients Territoires IDF
GRDF IDF ACOF Sce GAZ
GRDF Méditerranée Pôle Marketing 
55211 GrDF
GRDF Nord Ouest 
GRDF OUEST
GRDF SUD OUEST 
GREENWEEZ
Greenweez Belgium
Groupe BMS
HAPPN
HARMONIC FRANCE SAS
THOM
Home Shopping Service
Home Shopping Service Belgique
Infopresse
GROUPE MONITEUR
INTERDIT AU PUBLIC
INTERFORCE MARKETING INC
JENNYFER
JURATOYS
JURISYSTEM
KARD
KASE WORLD WIDE
KUEHNE & NAGEL SAS
KUEHNE & NAGEL ROAD
LABEL HABITATION
LA REDOUTE
Yolaw SAS
Lightspeed Netherlands B.V.
Lightspeed Netherlands B.V.
LOUNNA SAS
LUNCHR SAS
LYOVEL NORD PICARDIE NORMANDIE 
MANUTAN
Manutan N.V./S.A
MANUTAN COLLECTIVITE
Manutan
MAPPY - ILM
MARIONNAUD Lafayette
MARIONNAUD ESPACE
MASALLEDEBAIN.COM
MATERA
MAISON DE L EMPLOI 
MESOIGNER
Mister Auto
MONDADORI FRANCE
CAFAN
MyPix.com
MY SAFE MAP
NATIXIS
NATIXISLEASE
NUMBER118 / ND3
NETLINECC
NEWPIX & CO
NIKE EUROPEAN OPERATIONS NETHERLANDS (NEON)
FONDATION OCH
OPCO ATLAS
OUTSOURCIA NIGER
OXEDIS
DIGITAL ENTREPRENEUR SERVICES LTD
PARAGON TRANSACTION
Automobiles Peugeot
PHARMACIE ANGLAISE 
Pixmania
PIXALIONE
PLAYGROUND MEDIA
Points de Vente SAS
POUEY INTERNATIONAL S.A.
PressImmo Online
PRIVALIA VENTA DIRECTA, S.A.
DIGITAL MOONWALK LTD
PROPRIOO
PROXIMUM
QAMA Quincaillerie
R WEB
SANTAREL LTD
SAVELYS
SCEMI
PRESSIMMO ONLINE
ROADGET BUSINESS PTE. LTD.
SHINNING
Sciencéthic
SIMPLIFY
SOCULTUR
SOLUANCE
SOLUCIA 
SOMATEX
SPB
SPEAK 33
SHOWROOMPRIVE
STAROFSERVICE SAS
STEFANINI SAS
Stefanini Poland
STEFI INFORMATIQUE
SUNWEB VACANCES
KR STORE
The continuity company
TEEZILY
THE KOOPLES
Thésée SAS
Grass Valley France SA
Thomson Video Networks France SAS
TIENDEO WEB MARKETING S.L.
TOKTOKTOK
Place du Marché
24PrimeMarkets Ltd
TRAQUEUR
TRAVEL HORIZON
TWENGA
TYM CAPITAL
CS Support Network Ltd 
TIMEONE - PUBLISHING
VDD SAS
VEMARIN VESTIS
Vente-privée.com
VENTE PRIVEE DIGITALSERVICESIBERICA
VirtualExpo
VISAUDIO SAS
VISIATIV SOLUTIONS ENTREPRISE 
V.Optimum
VOYAGES LOISIRS
VPC
W3 Ltd, Company
WAZARI S.A.S
WEYOU GROUP
WILSON  Leasing
WONDERBOX BELGIQUE SA 
WONDERBOX
WONDERBOX ESPANA SL
WONDERBOX ITALIA SRL 
WORLDONE RESEARCH
SPLASH
Netdistrict
ZEE MEDIA Agency
Natixis Lease
PERSOLASER
FREE MOBILE
Le Comptoir National de lOR
GRDF Méditerannée
GREENWEEZ
NEWPIX & CO
";
                $tabout=explode("\n",$texte);
                $resultat = [];
                $isamety=0;
                $isataf=0;
                foreach ($result as $customer){
                    $rescust = [];
                    foreach ($tabout as $ex){
                        $pourcent=0;
                        $var1 = strtolower($customer->getRaisonSocial());
                        $var1a=str_replace(' ','',$var1);
                        $var2 =strtolower($ex);
                        $var2a=str_replace(' ','',$var2);
                        similar_text($var1a,$var2a,$pourcent);
                        $mety="";
                        if($pourcent>=90){
                            $mety="tena mety";
                            $isamety++;
                        }
                        if($pourcent>=80){
                            $tmp=$customer->getRaisonSocial();
                            $rescust[]="Bdd=$tmp  et Exel=$ex $mety";
                            $isataf++;
                        }
                    }
                    $resultat[$customer->getRaisonSocial()]=$rescust;

                }
                $resultat["mety"]="tafiditra=$isataf  et mety=$isamety";
                return $this->json($resultat, 200, [], ['groups' => ['get-by-bdc']]);
            }
            catch (\Exception $e) {
                return $this->json([
                    "status" => 500,
                    "message" => $e->getMessage()
                ], 500);
            }
    }

}
