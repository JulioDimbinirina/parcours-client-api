<?php

namespace App\Controller;

use App\Service\ParametrageSuiviRenta;
use Hybridauth\HttpClient\HttpClientInterface;
use Knp\Component\Pager\PaginatorInterface;
use PhpParser\Node\Stmt\Return_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\Cloner\Data;
use App\Repository\TvaRepository;
use Doctrine\ORM\EntityManagerInterface;
class ParametrageController extends AbstractController
{
    private $entityManager;

    private $paginator;

    public function __construct(EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        $this->entityManager = $entityManager;

        $this->paginator = $paginator;
    }

    /**
     * @Route("/parametrage/pays/{page}/{pays}", name="parametrage_pays", methods={"POST"})
     */
    public function pays(Request $request, int $page, string $pays, ParametrageSuiviRenta $parametrageSuiviRenta, PaginatorInterface  $paginator): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);
            if ($pays == "empty"){
                $results = $parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('param_pays_url'), $dataFront["token"]);
            } else {
                $results = $parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('param_pays_search_url') . $pays, $dataFront["token"]);
            }

            if ($results->getStatusCode() == 200){
                if (!empty($results->toArray())) {
                    $res = array();
                    foreach ($results->toArray() as $country) {
                        array_push($res, [
                            'id' => $country["id_pays"],
                            'pays' => $country["pays1"],
                        ]);
                    }

                    $paginateData = $paginator->paginate($res, $page, 7);

                    return $this->json([$paginateData, count($res)], 200, [], []);
                } else {
                    return $this->json([], 200, [], []);
                }
            } else {
                return $this->json($results->getContent(), $results->getStatusCode(), [], []);
            }
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/parametrage/bu/{page}/{filter}/{word}", name="parametrage_bu", methods={"POST"})
     */
    public function Bus(Request $request, int $page, ?string $filter, ?string $word, ParametrageSuiviRenta $parametrageSuiviRenta, PaginatorInterface  $paginator): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            if ($word == "empty"){
                $results = $parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('param_bu_url'), $dataFront["token"]);
            } else {
                if($filter == "bu") {
                    $results = $parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('param_bu_search_url').'bu/'.$word, $dataFront["token"]);
                } else {
                    $results = $parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('param_bu_search_url').'pays/'. $word, $dataFront["token"]);
                }
            }

            if ($results->getStatusCode() == 200){
                if (!empty($results->toArray())) {
                    $res = array();
                    foreach ($results->toArray() as $data) {
                        array_push($res, [
                            'id' => $data["id"],
                            'pays' => $data["Pays"],
                            'bu' => $data["bu1"]
                        ]);
                    }

                    $paginateData = $paginator->paginate($res, $page, 7);

                    return $this->json([$paginateData, count($res)], 200, [], []);
                } else {
                    return $this->json([], 200, [], []);
                }
            } else {
                return $this->json($results->getContent(), $results->getStatusCode(), [], []);
            }
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/parametrage/client/list", name="param_client_list", methods={"POST"})
     */
    public function ClientList(Request $request, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $url = $this->getParameter('param_client_url');
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
     * @Route("/parametrage/client/create", name="param_client_create", methods={"POST"})
     */
    public function ClientSave(Request $request, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $url = $this->getParameter('suivi_renta_client_url_post');

            $results = $parametrageSuiviRenta->getDataOnCurrentSuiviRentaPost($url, $dataFront);

            if ($results->getStatusCode() == 200){
                return $this->json("Insertion fait !", $results->getStatusCode(), [], []);
            }

            return $this->json("Une erreur s'est produite !", $results->getStatusCode(), [], []);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/parametrage/client/update", name="param_client_update", methods={"PUT"})
     */
    public function ClientUpdate(Request $request, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $url = $this->getParameter('param_client_update_url');
            $results = $parametrageSuiviRenta->getDataOnCurrentSuiviRentaPost($url, $dataFront, "PUT");

            if ($results->getStatusCode() == 200){
                return $this->json("Operation done !", $results->getStatusCode(), [], []);
            }

            return $this->json("Une erreur s'est produite !", $results->getStatusCode(), [], []);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/getAll/clientbyop", name="GetAllClientsByOP", methods={"POST"})
     */
    public function GetAllClientsByOP(Request $request,ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $res=array();
            
            $results=$parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('getclientbyop'));
            // dd($results->toArray());
            if (!empty($results->toArray())) {
                
                foreach ($results->toArray() as $data) {
                    array_push($res, [
                        'client' => $data
                    ]);
                }
                
            }
            return $this->json($res, $results->getStatusCode(), [], []);
        }
        catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

/**
     * @Route("/getAll/client", name="GetAllClients", methods={"POST"})
     */
    public function GetAllClients(Request $request,ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $res=array();
            $dataFront = json_decode($request->getContent(), true);
            $results=$parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('param_client_url_distinct') );
            //$results = $parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('param_client_url_distinct'),$dataFront["token"] );
            //$results =$parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('param_client_url_distinct_ok') );
            if (!empty($results->toArray())) {
                
                foreach ($results->toArray() as $data) {
                    array_push($res, [
                        'id' => $data["id"],
                        'pays' => $data["pays"],
                        'bu' => $data["bu"],
                        'client' => $data["client1"],
                        //'token' => $dataFront["token"]
                    ]);
                }
            }
            return $this->json($res, $results->getStatusCode(), [], []);
        }
        catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }

    }

    /**
     * @Route("/parametrage/operation/list", name="param_operation_list", methods={"POST"})
     */
    public function OperationList(Request $request, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $url = $this->getParameter('param_operation_url');
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
     * @Route("/parametrage/operation/create", name="param_operation_create", methods={"POST"})
     */
    public function OperationCreate(Request $request, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $url = $this->getParameter('suivi_renta_operation_url_post');
            $results = $parametrageSuiviRenta->getDataOnCurrentSuiviRentaPost($url, $dataFront);

            if (in_array($results->getStatusCode(), [200, 202, 204])){
                return $this->json("Insertion éfféctué !", $results->getStatusCode(), [], []);
            }

            return $this->json("Une erreur s'est produite !", $results->getStatusCode(), [], []);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/parametrage/operation/update", name="param_operation_update", methods={"PUT"})
     */
    public function OperationUpdate(Request $request, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $url = $this->getParameter('param_operation_update_url');
            $results = $parametrageSuiviRenta->getDataOnCurrentSuiviRentaPost($url, $dataFront, "PUT");

            if ($results->getStatusCode() == 200){
                return $this->json("Operation done !", $results->getStatusCode(), [], []);
            }

            return $this->json("Une erreur s'est produite !", $results->getStatusCode(), [], []);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/get/all/pays-facturation", name="get_all_pays_facturation", methods={"GET"})
     */
    public function GetAllPaysFact(ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            return $this->getDataInSIRentaViaPostMethod($parametrageSuiviRenta, $this->getParameter("param_pays_fact_list_all_url"));
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/get/all/famille-operation", name="get_all_pays_facturation", methods={"GET"})
     */
    public function GetAllFamilleOperation(ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            return $this->getDataInSIRentaViaPostMethod($parametrageSuiviRenta, $this->getParameter("param_famille_operation_list_all_url"));
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/get/ref/data/for/operation-add", name="get_ref_data_for_operation_add", methods={"POST"})
     */
    public function GetRefData(Request $request, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $pays = $parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter("param_pays_all_url"), $dataFront["token"]);
            $businessUnits = $parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter("param_bu_all_url"), $dataFront["token"]);
            $clients = $parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter("param_all_client_url"), $dataFront["token"]);
            $familleOperations = $parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter("param_famille_operation_list_all_url"), $dataFront["token"]);

            return $this->json(["pays" => $pays->toArray(), "businessUnits" => $businessUnits->toArray(), "clients" => $clients->toArray(), "familleOperations" => $familleOperations->toArray()], 200, [], []);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/parametrage/paysfacturation/list", name="param_paysfacturation_list", methods={"POST"})
     */
    public function PaysFactList(Request $request, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $url = $this->getParameter('param_pays_fact_list_url');
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
     * @Route("/parametrage/societefacturation/list", name="param_societefacturation_list", methods={"POST"})
     */
    public function SocieteFactList(Request $request, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $url = $this->getParameter('param_societe_fact_list_url');
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
     * @Route("/parametrage/societefacturation/save", name="param_societefacturation_save", methods={"POST"})
     */
    public function SocieteFactSave(Request $request, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $url = $this->getParameter('param_societe_fact_save_url');
            $results = $parametrageSuiviRenta->getDataOnCurrentSuiviRentaPost($url, $dataFront);

            if ($results->getStatusCode() == 200){
                return $this->json("Insertion fait !", $results->getStatusCode(), [], []);;
            }

            return $this->json("Une erreur s'est produite !", $results->getStatusCode(), [], []);;
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/parametrage/paysfacturation/save", name="param_paysfacturation_save", methods={"POST"})
     */
    public function PaysFactSave(Request $request, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $url = $this->getParameter('param_pays_fact_save_url');
            $results = $parametrageSuiviRenta->getDataOnCurrentSuiviRentaPost($url, $dataFront);

            if ($results->getStatusCode() == 200){
                return $this->json("Insertion fait !", $results->getStatusCode(), [], []);;
            }

            return $this->json("Une erreur s'est produite !", $results->getStatusCode(), [], []);;
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/parametrage/societefacturation/update", name="param_societefacturation_update", methods={"PUT"})
     */
    public function SocieteFactUpdate(Request $request, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $url = $this->getParameter('param_societe_fact_update_url');
            $results = $parametrageSuiviRenta->getDataOnCurrentSuiviRentaPost($url, $dataFront, "PUT");

            if ($results->getStatusCode() == 200){
                return $this->json("Operation done !", $results->getStatusCode(), [], []);;
            }

            return $this->json("Une erreur s'est produite !", $results->getStatusCode(), [], []);;
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/parametrage/paysfacturation/update", name="param_paysfacturation_update", methods={"PUT"})
     */
    public function PaysFactUpdate(Request $request, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $url = $this->getParameter('param_pays_fact_update_url');
            $results = $parametrageSuiviRenta->getDataOnCurrentSuiviRentaPost($url, $dataFront, "PUT");

            if ($results->getStatusCode() == 200){
                return $this->json("Operation done !", $results->getStatusCode(), [], []);;
            }

            return $this->json("Une erreur s'est produite !", $results->getStatusCode(), [], []);;
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/parametrage/societefacturation/delete/{id}", name="param_societefacturation_delete", methods={"DELETE"})
     */
    public function SocieteFactDelete(int $id, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $url = $this->getParameter('param_societe_fact_delete_url') . $id;

            $results = $parametrageSuiviRenta->deleteData($url);

            if ($results->getStatusCode() == 200){
                return $this->json("Operation done !", $results->getStatusCode(), [], []);
            }

            return $this->json("Une erreur s'est produite !", $results->getStatusCode(), [], []);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/parametrage/paysfacturation/delete/{id}", name="param_paysfacturation_delete", methods={"DELETE"})
     */
    public function PaysFactDelete(int $id, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $url = $this->getParameter('param_pays_fact_delete_url') . $id;

            $results = $parametrageSuiviRenta->deleteData($url);

            if ($results->getStatusCode() == 200){
                return $this->json("Operation done !", $results->getStatusCode(), [], []);
            }

            return $this->json("Une erreur s'est produite !", $results->getStatusCode(), [], []);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/parametrage/famille/operation/list", name="param_famille_operation_list", methods={"POST"})
     */
    public function FamilleOperationList(Request $request, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $url = $this->getParameter('param_famille_operation_list_url');
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
     * @Route("/parametrage/famille/operation/save", name="param_famille_operation_save", methods={"POST"})
     */
    public function FamilleOperationSave(Request $request, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $url = $this->getParameter('param_famille_operation_save_url');
            $results = $parametrageSuiviRenta->getDataOnCurrentSuiviRentaPost($url, $dataFront);

            if ($results->getStatusCode() == 200){
                return $this->json("Insertion done !", $results->getStatusCode(), [], []);;
            }

            return $this->json("Une erreur s'est produite !", $results->getStatusCode(), [], []);;
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/parametrage/famille/operation/update", name="param_famille_operation_update", methods={"PUT"})
     */
    public function FamilleOPerationUpdate(Request $request, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $url = $this->getParameter('param_famille_operation_update_url');
            $results = $parametrageSuiviRenta->getDataOnCurrentSuiviRentaPost($url, $dataFront, "PUT");

            if ($results->getStatusCode() == 200){
                return $this->json("Operation done !", $results->getStatusCode(), [], []);;
            }

            return $this->json("Une erreur s'est produite !", $results->getStatusCode(), [], []);;
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/parametrage/famille/operation/delete/{id}", name="param_famille_operation_delete", methods={"DELETE"})
     */
    public function FamilleOperationDelete(int $id, ParametrageSuiviRenta $parametrageSuiviRenta): Response
    {
        try {
            $url = $this->getParameter('param_famille_operation_delete_url') . $id;

            $results = $parametrageSuiviRenta->deleteData($url);

            if ($results->getStatusCode() == 200){
                return $this->json("Operation done !", $results->getStatusCode(), [], []);
            }

            return $this->json("Une erreur s'est produite !", $results->getStatusCode(), [], []);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @param $parametrageSuiviRenta
     * @param $token
     * @param $url
     * @return Response
     */
    private function getDataInSIRentaViaPostMethod($parametrageSuiviRenta, $url, $token = null): Response
    {
        $results = $parametrageSuiviRenta->getDataOnCurrentSuiviRenta($url, $token);

        if ($results->getStatusCode() == 200){
            $response = !empty($results->toArray()) ? $this->json($results->toArray(), 200, [], []) : $this->json("No data found", 200, [], []);
        } else {
            $response =  $this->json($results->getContent(), $results->getStatusCode(), [], []);
        }

        return $response;
    }

    /**
     * @Route ("/facture/getall/client/{mois}", name="getAllClientfacture2", methods={"GET"})
     * @return Response
     * Import document bdc.........................
     */
    public function getAllClientfacture2(string $mois,ParametrageSuiviRenta $parametrageSuiviRenta): Response{
        $parametre = new Parametre();
        $tmp=explode(" ",$mois);
        $volana=$parametre->GetMois($tmp[0]);
        $tona=$tmp[1];
        $result=$parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('suivirentaUrl').'api/saisie/manager/get/allclient/'.$volana.'/'.$tona);
        $res = json_decode($result->getContent(), true);
        return $this->json($res);
    }
    /**
     * @Route ("/facture/parametre/{client}/{mois}", name="facturepara", methods={"POST"})
     * @param Request $request
     * @return Response
     * Import document bdc.........................
     */
    public function facturepara(Request $request,string $client,string $mois,ParametrageSuiviRenta $parametrageSuiviRenta): Response {
        try{
            
            $parametre = new Parametre();
            $parametre->client=$client;
            $parametre->mois=$mois;
            $tmp=explode(" ",$mois);
            $volana=$parametre->GetMois($tmp[0]);
            $tona=$tmp[1];
            
            //$result=$parametrageSuiviRenta->getAsyncSuivi($this->getParameter('facture_parametre').$client.'/'.$volana.'/'.$tona);
            $result=$parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('facture_parametre').$client.'/'.$volana.'/'.$tona);
            // dd($this->getParameter('facture_parametre').$client.'/'.$volana.'/'.$tona);
            //$result=$parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('facture_parametre'));
            $res = json_decode($result->getContent(), true);
            return $this->json($res);
        }catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }
    /**
     * @Route ("/set/clientcheckedphp", name="setClientChecked", methods={"POST"})
     * @param Request $request
     * @return Response
     * Import document bdc.........................
     */
    public function setClientChecked(Request $request,ParametrageSuiviRenta $parametrageSuiviRenta): Response{
        try {
            $dataFront = json_decode($request->getContent(), true);
            $valiny=[];
            //$result=$parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('setClientSage'));
            foreach($dataFront as $client){
                $result=$parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('setClientSage').'/'.$client);
                $valiny[]=$result->toArray();
            }
            return $this->json($valiny);
        }
        catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }

    }

    /**
     * @Route ("/facture/getAll/{client}/{mois}", name="factureAll", methods={"POST"})
     * @param Request $request
     * @return Response
     * Import document bdc.........................
     */

    public function factureAll(Request $request,string $client,string $mois,ParametrageSuiviRenta $parametrageSuiviRenta): Response {
        $tmp=explode(" ",$mois);
        $parametre = new Parametre();
        $volana=$parametre->GetMois($tmp[0]);
        $tona=$tmp[1];
        
        try{
            if($client == 'tous'){
                $result=$parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('facture_sansExcel').$volana.'/'.$tona);
                dd($result);
            }
            
            else{
                $result=$parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('facture_sansExcelUno').$client.'/'.$volana.'/'.$tona);
            }
            $res = json_decode($result->getContent(), true);

            return $this->json($res);
        }
        catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }

     /**
     * @Route ("/facture/DateFarany", name="DateFarany", methods={"GET"})
     * @return Response
     * Import document bdc.........................
     */
    public function DateFarany(ParametrageSuiviRenta $parametrageSuiviRenta): Response {
        try{
            $result=$parametrageSuiviRenta->getDataOnCurrentSuiviRenta($this->getParameter('daty_farany'));
            $res = json_decode($result->getContent(), true);

            return $this->json($res);
        }
        catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }

/**
     * @Route ("/testPost", name="testpost", methods={"POST"})
     * @param Request $request
     * @return Response
     * Import document bdc.........................
     */
    /*
    public function TestPost(Request $request,ParametrageSuiviRenta $parametrageSuiviRenta): Response {
        try{
            $dataFront = json_decode($request->getContent(), true);
            $result =$parametrageSuiviRenta->getDataOnCurrentSuiviRentaPost("http://localhost:12992/api/saisie/manager/test/post",$dataFront);
            $res = json_decode($result->getContent(), true);
            return $this->json($res);
        }
        catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }
*/

    /**
     * @Route ("/ApiTVA/{libellePays}", name="ApiTvaByliblle", methods={"GET"})
     * @return Response
     * Import document bdc.........................
     */

    public function ApiTvaByliblle(string $libellePays,TvaRepository $tvarepo): Response {
        try{
            $pays=$libellePays;
            $result=$tvarepo->findAll();
            $idres=0;
            $res="tsisy";
            $tab=[];
            foreach ($result as $r){
                $tab[]=$r->getPaysFacturation()->getLibelle();
                if($r->getPaysFacturation()->getLibelle() === $libellePays){
                    $res=$r->getLibelle();
                }
            }
            return $this->json($res);
        }
        catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }

}
class Parametre{
    public $client;
    public $mois;
    public $anne;

    public function GetMois($mois){
        if($mois==="Janvier"){
            return 1;
        }
        if($mois==="Fevrier"){
            return 2;
        }
        if($mois==="Mars"){
            return 3;
        }
        if($mois==="avril"){
            return 4;
        }
        if($mois==="Mai"){
            return 5;
        }
        if($mois==="Juin"){
            return 6;
        }
        if($mois==="juillet"){
            return 7;
        }
        if($mois==="Aout"){
            return 8;
        }
        if($mois==="septembre"){
            return 9;
        }
        if($mois==="Octobre"){
            return 10;
        }
        if($mois==="November"){
            return 11;
        }
        
        if($mois==="Decembre"){
            return 12;
        }
        return 12;
            
    }
}