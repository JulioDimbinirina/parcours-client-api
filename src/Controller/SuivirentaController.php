<?php
namespace App\Controller;

use App\Entity\Customer;
use App\Repository\BdcOperationRepository;
use App\Repository\BdcRepository;
use App\Repository\CustomerRepository;
use App\Service\CheckSimilatiryText;
use App\Service\FileManipulate;
use App\Service\InjectCoutInSuivirenta;
use App\Service\ParametrageSuiviRenta;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;

class SuivirentaController extends AbstractController
{
    /**
     * @var BdcRepository
     */
    private $bdcRepository;

    /**
     * @var InjectCoutInSuivirenta
     */
    private $injectCoutInSuivirenta;

    /**
     * @var BdcOperationRepository
     */
    private $bdcOperationRepository;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var ParametrageSuiviRenta
     */
    private $parametrageSuiviRenta;

    /**
     * @var FileManipulate
     */
    private $fileManipulate;

    public function __construct(BdcRepository $bdcRepository,
                                InjectCoutInSuivirenta $injectCoutInSuivirenta,
                                ParametrageSuiviRenta $parametrageSuiviRenta,
                                BdcOperationRepository $bdcOperationRepository,
                                HttpClientInterface $httpClient){

        $this->bdcRepository = $bdcRepository;
        $this->bdcOperationRepository = $bdcOperationRepository;
        $this->httpClient = $httpClient;
        $this->injectCoutInSuivirenta = $injectCoutInSuivirenta;
        $this->parametrageSuiviRenta = $parametrageSuiviRenta;

        # Service pour manipulation fichier
        $this->fileManipulate = new FileManipulate();
    }

    /**
     * @Route("/parametrage/saisie/manager/list", name="param_saisie_manager_list", methods={"POST"})
     */
    public function SaisieManagerList(Request $request, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $url = $this->getParameter('param_saisie_manager_list_url');
            $results = $parametrageSuiviRenta->getDataOnCurrentSuiviRentaPost($url, $dataFront, "POST", $dataFront["token"]);

            if ($results->getStatusCode() == 200){
                $response = !empty($results->toArray()) ? $this->json($results->toArray(), 200, [], []) : $this->json("No data found", 200, [], []);
            } else {
                $response =  $this->json($results->getContent(), $results->getStatusCode(), [], []);
            }

            return $response;
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/production/heure/agent/list", name="suivirenta_heure_agent_list", methods={"GET"})
     * @return Response
     * Get all saisie manager in suivirenta
     */
    public function getAllSaisieManager(): Response
    {
        return $this->json($this->parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('param_heure_agent_url')), 200, [], []);
    }

    /**
     * @Route("/suivirenta/budget/all", name="suivirenta_GetBudget", methods={"GET"})
     * @return Response
     */
    public function getAllBudgetTest(): Response{
        $reponse=$this->json($this->parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('suivirentaUrl')."api/budget/list"));
        // dd($reponse);
        $res=json_decode($reponse->getContent(), true);
        $valiny=json_decode($res["content"], true);
        return $this->json($valiny, 200, [], []);
    }

    /**
     * @Route("/suivirenta/budget/{filtre}/{search}/{rowsPerPage}/{page}", name="suivirenta_SearchBudget", methods={"GET"})
     * @return Response
     */
    public function suivirenta_SearchBudget($filtre,$search,$rowsPerPage,$page): Response{
        $reponse=$this->json($this->parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('suivirentaUrl')."api/budget/search/".$filtre."/".$search."/".$rowsPerPage."/".$page));
        // dd($reponse);
        $res=json_decode($reponse->getContent(), true);
        $valiny=json_decode($res["content"], true);
        return $this->json($valiny, 200, [], []);
    }

     /**
     * @Route("/suivirenta/budget/count/{filtre}/{search}", name="suivirenta_CountBudget", methods={"GET"})F
     * @return Response
     */
    public function suivirenta_CountBudget($filtre,$search): Response{
        $reponse=$this->json($this->parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('suivirentaUrl')."api/budget/count/".$filtre."/".$search));
        // dd($reponse);
        $res=json_decode($reponse->getContent(), true);
        $valiny=json_decode($res["content"], true);
        return $this->json($valiny, 200, [], []);
    }

     /**
     * @Route("/suivirenta/budget/get/id/{id}", name="suivirenta_GetBudget_ById", methods={"GET"})
     * @return Response
     */

     public function suivirenta_GetBudget_ById($id): Response{
        $reponse=$this->json($this->parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('suivirentaUrl')."api/budget/get/ById/".$id));
        $res=json_decode($reponse->getContent(), true);
        $valiny=json_decode($res["content"], true);
        return $this->json($valiny, 200, [], []);
     }

      /**
     * @Route("/suivirenta/budget/update/{id}", name="suivirenta_Update_ById", methods={"POST"})
     * @return Response
     */

    public function suivirenta_UpdateBudget_ById($id,Request $request): Response{
        $object=json_decode($request->getContent(), 'true');
        $object["id"]=$id;
        // dd($object);
        $reponse=$this->json($this->parametrageSuiviRenta->getDataOnCurrentSuiviRentaPost($this->getParameter('suivirentaUrl')."api/budget/update/",$object));
        $res=json_decode($reponse->getContent(), true);
        $valiny=json_decode($res["content"], true);
        return $this->json($valiny, 200, [], []);
     }

    /**
     * @Route("/suivirenta/update/cout", name="suivirenta_update_cout", methods={"GET"})
     * @return Response
     */
    public function updateCoutInSuivirenta(): Response
    {
        # Récuperation des tout les bon de commande en production
        $bdcs = $this->bdcRepository->findAllBdcEnProduction();

        if (!empty($bdcs)){
            foreach ($bdcs as $bdc){
                # Appel l'api suivirenta pour la mis à jour des couts
                $this->injectCoutInSuivirenta->injectOrUpdateCoutToSuivirenta($bdc, "PUT", $this->getParameter('param_update_cout_in_suivirenta_url'));
            }

            return $this->json("Updated data !", 200, [], ['groups' => ['inject:cout']]);
        } else {
            return $this->json("No result", 200, [], ['groups' => ['inject:cout']]);
        }
    }

    /**
     * @Route("/suivirenta/inject_or_update/saise_manager/{firstOfThisMonth}/{currentDate}", name="suivirenta_inject_or_update_saisie_manager", methods={"GET"})
     * @return Response
     */
    public function updateSaisieAgentInSuivirenta(string $firstOfThisMonth, string $currentDate): Response
    {
        try{
            set_time_limit(-1);

            # Prendre le premier du mois en cours
            // $firstOfThisMonth = date('Y-m-d', mktime(0, 0, 0, date("m"), 1, date("Y")));

            # Prendre la date courant
            // $currentDate = date("Y-m-d");

            # url à utiliser pour obtenir les heures agents dans IRM
            $url = $this->getParameter('irm_get_heures_agent') . $firstOfThisMonth . '/' . $currentDate;

            # Recupération des heures agents validées dans IRM
           $dataHeureAgent = $this->httpClient->request('GET', $url);

            # Enregistrement des heures agents validées dans suivirenta
            if ($dataHeureAgent->getStatusCode() == 200){
                foreach ($dataHeureAgent->toArray() as $heureAgent){
                    # Url d'enregistrement des heures agents dans suivirenta
                    $url1 = $this->getParameter('param_inject_or_update_heure_agent_in_suivirenta_url');

                    $currDate = str_replace("/", "-", $heureAgent["jour"]);

                    $dateSaisie = date("Y-m-d", strtotime($currDate));

                    # Enregistrement des données vers suivirenta.
                    $this->httpClient->request('POST', $url1, [
                        'body' => [
                            'date_saisie' => $dateSaisie,
                            'actes' => null,
                            'Jour' => $this->ObtenirLeJourdeLaSemaine($heureAgent['jour']),
                            'heures_valides' => str_replace(',', '.', $heureAgent['heures_valides']),
                            'tarifprod' => null,
                            'tarifformation' => null,
                            'tarifactes' => null,
                            'coutactivite' => null,
                            'coutformation' => null,
                            'login' => null,
                            'date_insert' => $currentDate,
                            'heuresfomationfacturees' => 0,
                            'dmt' => null,
                            'id_operation' => $heureAgent['id_operation']
                        ]
                    ]);
                }

                return $this->json("Operation done !", 200, [], ['groups' => ['saisie:manager']]);
            }

            return $this->json("Error found", 200, [], []);
        } catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        } catch (TransportExceptionInterface $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/export/canvas/budget", name="canvas_budget", methods={"POST"})
     */
    public function exportCanvasBudget(Request $request, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $operations = $parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('param_operation_url'), $dataFront["token"]);

            if ($operations->getStatusCode() == 200){
                if (!empty($operations->toArray())) {
                    # On recupère d'abord les données.
                    $datas = $this->reorganiseData($operations->toArray());

                    $spreadsheet = new Spreadsheet();

                    $this->setEachSheet($spreadsheet, $datas);

                    for ($i = 1; $i < 3; $i++){
                        $this->setEachSheet($spreadsheet, $datas, $i);
                    }

                    $writer = new Xlsx($spreadsheet);

                    # Le nom du fichier à exporter
                    $filename = 'canvas_budget_par_client.xlsx';

                    $writer->save($filename);

                    return $this->json($filename, 200, [], ['groups' => ['bdcs']]);
                } else {
                    return $this->json("Auccun donnée ont été trouvé", 200, [], []);
                }
            } else {
                return $this->json($operations->getContent(), $operations->getStatusCode(), [], []);
            }
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/import/customer/budget", name="import_customer_budget", methods={"POST"})
     */
    public function importBudgetByCustomer(Request $request): Response
    {
        try {
            # Import le fichier
            $file = $this->fileManipulate->uploadFile($this->getParameter('bdc_dir'), $request, 1);

            if (file_exists($file)) {
                $spreadsheet = IOFactory::load($file);

                # Recupère les données dans l'onglet bdc et operation
                $nbClasseur = $spreadsheet->getSheetCount();

                for ($i = 0; $i < $nbClasseur; $i++) {
                    $spreadsheet->getSheet($i)->removeRow(1);
                    $existCustomerBudget = $spreadsheet->getSheet($i)->toArray(null, true, true, true);

                    # Injection cout dans suivirenta
                    foreach ($existCustomerBudget as $dataClasseur){
                        if ($dataClasseur["A"] && $dataClasseur["B"] && $dataClasseur["C"] &&
                            $dataClasseur["D"] && $dataClasseur["E"] && $dataClasseur["F"] &&
                            $dataClasseur["G"] && $dataClasseur["H"]){
                            $this->httpClient->request('POST', $this->getParameter('param_import_data_file_budget_url'), [
                                'json' => [
                                    "pays" => $dataClasseur["A"],
                                    "bu" => $dataClasseur["B"],
                                    "client" => $dataClasseur["C"],
                                    "date_debut" => date_format((new \DateTime($dataClasseur["D"])), "Y-m-d H:i:s"),
                                    "date_fin" => date_format((new \DateTime($dataClasseur["E"])), "Y-m-d H:i:s"),
                                    "ca_budget" => $dataClasseur["F"],
                                    "tx_marge_budget" => $dataClasseur["G"],
                                    "marge_valeur_budget" => $dataClasseur["H"]
                                ]
                            ]);
                        }
                    }
                }

                # On supprime le fichier lorsque l'import est terminé
                $this->fileManipulate->deleteFile($file);

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
     * @Route("/export/excel/for/suivirenta/alignement/{percent}", name="excel_for_suivirenta_alignement", methods={"GET"})
     */
    public function exportExcelForSuivirentaAlignement(int $percent, CustomerRepository $customerRepository, CheckSimilatiryText $checkSimilatiryText): Response
    {
        try {

            # Recuperer tout les clients qui existe dans la base de donnée parcours client
            $allCustomers = $customerRepository->findAll();

            $clientsOnSuivirenta = $this->parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('param_all_client_url') );

            if ($allCustomers && $clientsOnSuivirenta->toArray()){
                # Recupère les données qui ont des correspondances
                list($datas, $noCorrespondanceRasisonSocials) = $checkSimilatiryText->getSimilarData($clientsOnSuivirenta->toArray(), $allCustomers, $percent);

                $sheetToCreate = new Spreadsheet();

                $sheet = $sheetToCreate->getActiveSheet();

                $sheet->setTitle('Suivirenta alignement');

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
                $sheet->getCell('A1')->setValue("ID CLIENT SUIVIRENTA");
                $sheet->getCell('B1')->setValue("RAISON SOCIALE CLIENT DANS SUIVIRENTA");
                $sheet->getCell('C1')->setValue("RAISON SOCIALE QUI PEUVENT CORRESPONDRE");
                $sheet->getCell('D1')->setValue("VALIDATION (Mettre un croix SVP)");
                $sheet->getCell('E1')->setValue("LISTE DES RAISONS SOCIALES DANS CRM QUI N'ONT PAS DE CORRESPONDANCE DANS SUIVIRENTA");

                # Insertion des données dans le fichier
                $sheet->fromArray($datas, null, 'A2', true);

                # Fusionne les cellules qui doit être fusionnée
                $checkSimilatiryText->mergeCells($datas, $sheet);

                $columnAContent = "A2:A". (count($datas) + 1);

                $sheet->getStyle($columnAContent)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                if ($noCorrespondanceRasisonSocials){
                    /**
                     * Ajouter tout les raisons sociales qui n'ont pas
                     * de correspondance dans la colonne E de l'excel
                     */
                    $checkSimilatiryText->setValueOfColumnE($sheet, $noCorrespondanceRasisonSocials);
                }

                $writer = new Xlsx($sheetToCreate);

                # Le nom du fichier à exporter
                $newFile = $this->getParameter('bdc_dir').'raison_social_to_align_for_suivirenta.xlsx';

                $writer->save($newFile);

                return $this->json("Ok", 200, [], ['groups' => ['status:lead']]);
            }

            return $this->json("No customer found", 200, [], []);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/execute/suivirenta/alignement", name="execute_suivirenta_alignement", methods={"POST"})
     */
    public function importValidateFileToSuivirentaAlignement(Request $request): Response
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
                    $this->updateClientRaisonSocialeOnSuivirenta($sheetData);

                    # Supprime le fichier importé au paravant
                    $fileManipulate->deleteFile($file);
                }

                return $this->json("Operation done!", 200, [], ['groups' => ['status:lead']]);
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
    private function updateClientRaisonSocialeOnSuivirenta($sheetData): void
    {
        foreach ($sheetData as $row){
            if (!empty($row["D"])){
                if (!empty($row["A"]) && !empty($row["C"])){
                    $raisonSocial = $row["C"];

                    /**
                     * Verifie s'il y a une apostrophe dans la raison social
                     * si c'est le cas, alors on le remplace par une double apostrophe
                     */
                    strpos($raisonSocial, "'") && $raisonSocial = str_replace("'", "''", $raisonSocial);

                    $this->httpClient->request('PUT', $this->getParameter('param_rs_align_for_client_url'), [
                        'json' => [
                            "id" => $row["A"],
                            "raisonSocial" => $raisonSocial,
                        ]
                    ]);
                }
            }
        }
    }

    /**
     * @param $operations
     * @return array
     * fonction qui retourne les données nécéssaire au fichier à exporter
     */
    private function reorganiseData($operations): array
    {
        $data = [];
        $tmpOperations = [];

        # On extraire les données qui doit être present dans le fichier
        if(count($operations) > 0) {
            foreach ($operations as $operation) {
                if (!isset($tmpOperations[$operation["pays"]][$operation["client"]])){
                    for ($i = 01; $i < 13; $i++){
                        $dateDebut = "01-" . $i . "-" . date("Y");

                        $dateFin = $this->ObtenirLeDernierJourDuMois($i) . "-" . $i . "-" . date("Y");

                        $data[] = [
                            $operation["pays"],
                            $operation["bu"],
                            $operation["client"],
                            $dateDebut,
                            $dateFin,
                            "",
                            "",
                            "",
                        ];
                    }
                    $tmpOperations[$operation["pays"]][$operation["client"]][$operation["bu"]] = 1;
                }
            }
        }

        return $data;
    }

    private function setEachSheet($spreadsheet, $datas, int $i = null)
    {
        if ($i){
            $sheet = $spreadsheet->createSheet();
        } else{
            $sheet = $spreadsheet->getActiveSheet();
        }

        switch ($i){
            case 1:
                $title = 'New business 1';
                break;
            case 2:
                $title = 'New business 2';
                break;
            default:
                $title = 'Client existant';
        }

        $sheet->setTitle($title);

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
        $sheet->getStyle('A1:H1')->applyFromArray($styleArray);

        # Donner la largeur automatique pour chaque colonne
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);

        # Tout les colonnés existant
        $sheet->getCell('A1')->setValue('PAYS');
        $sheet->getCell('B1')->setValue('BU');
        $sheet->getCell('C1')->setValue('CLIENT');
        $sheet->getCell('D1')->setValue('DATE DEBUT');
        $sheet->getCell('E1')->setValue('DATE FIN');
        $sheet->getCell('F1')->setValue('CA BUDGET');
        $sheet->getCell('G1')->setValue('TAUX MARGE BUDGET');
        $sheet->getCell('H1')->setValue('MARGE VALEUR BUDGET');

        # Contient le nombre de ligne à donner une formule pour la colonne MARGE VALEUR BUDGET
        $totalRow = 1501;

        # Rempli le premier classeur
        if (empty($i)){
            # Insertion des données dans le fichier
            $sheet->fromArray($datas, null, 'A2', false);

            $totalRow = count($datas) + 2;
        }

        # Attribuer une formule de calcule au colonne MARGE VALEUR BUDGET
        for ($i = 2; $i < $totalRow; $i++){
            $sheet->setCellValue("H$i", "=F$i * G$i");
        }
    }

    /**
     * @param string|null $date
     * @return string
     * Retourne le jour de la semaine à partir d'une date
     */
    private function ObtenirLeJourdeLaSemaine(string $date = null): string
    {
        $jours = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];

        list($jour, $mois, $annee) = explode('/', $date);

        return $jours[intval(date("w", mktime(0, 0, 0, $mois, $jour, $annee)))];
    }

    /**
     * @param int
     * @return int
     * Retourne le dernier jour du mois
     */
    private function ObtenirLeDernierJourDuMois(int $mois): int
    {
        $finJourFevrier = 28;

        $nextYear = date("Y") + 1;

        # Verification des année bissextille
        if(((($nextYear % 4) == 0) && (($nextYear % 100) != 0)) || (($nextYear % 400) == 0) ){
            $finJourFevrier = 29;
        }

        $dernierjoursDuMois = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

        return $dernierjoursDuMois[$mois - 1];
    }
}
