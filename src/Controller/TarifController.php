<?php

namespace App\Controller;

use App\Repository\BdcRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TarifController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route ("/list/tarif/{rowperpage}/{page}", name="list_tarif", methods={"POST"})
     * @return Response
     */
    public function getListTarif(int $rowperpage, int $page, Request $request): Response {
        try {
            $dataFront = json_decode($request->getContent(), true);

            $url = $this->getParameter('param_list_tarif_in_suivirenta_url'). $dataFront["filter"] . "/" . $dataFront["keyword"] . "/" . $rowperpage . "/" . $page;

            $response = $this->client->request('GET', $url, [
                'auth_bearer' => $dataFront["token"]
            ]);

            if (!empty($response->toArray())) {
                $res = [$response->toArray()["totalCount"], $response->toArray()["datas"]];
                return $this->json($res, 200, [], []);
            }

            return $this->json('The response is empty', 404, [], []);

        } catch (\Exception $exception) {
            return $this->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @Route ("/edit/tarif", name="edit_tarif_sirenta", methods={"GET"})
     * @param BdcRepository $bdcRepository
     * @return Response
     */
    public function getTarifBdcProdAndEditDatabaseSiRenta(BdcRepository $bdcRepository): Response {
        try {
            // On va recuperer d'abord les infos tarifs du bdc  en production
            $bdcProd = $bdcRepository->findAllBdcEnProduction();

            if (!empty($bdcProd)) {
               foreach ($bdcProd->getBdcOperations() as $ligneFacturation) {

                   // Logique tarif formation
                   $tarifFormation = null;
                   if ($ligneFacturation->getOperation()->getId() == $this->getParameter('param_id_operation_formation_continue')) {
                       $tarifFormation = floatval($ligneFacturation->getPrixUnit());
                   }

                   // Logique tarif heure dimanche
                   $tarifHeureDimanche = null;
                   if
                   (
                       $ligneFacturation->getIsHnoDimanche() == $this->getParameter('param_is_hno') &&
                       $ligneFacturation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_heure')
                   ) {
                       $tarifHeureDimanche = floatval($ligneFacturation->getPrixUnit());
                   }

                   // Logique tarif acte dimanche
                   $tarifActeDimanche = null;
                   if
                   (
                       $ligneFacturation->getIsHnoDimanche() == $this->getParameter('param_is_hno') &&
                       $ligneFacturation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_acte')
                   )
                   {
                       $tarifActeDimanche = floatval($ligneFacturation->getPrixUnit());
                   }

                   // Logique tarif heure hors dimanche
                   $tarifHeureHorsDimanche = null;
                   if
                   (
                       $ligneFacturation->getIsHnoHorsDimanche() == $this->getParameter('param_is_hno') &&
                       $ligneFacturation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_heure')
                   ) {
                       $tarifHeureHorsDimanche = floatval($ligneFacturation->getPrixUnit());
                   }

                   // Logique tarif acte hors dimanche
                   $tarifActeHorsDimanche = null;
                   if
                   (
                       $ligneFacturation->getIsHnoHorsDimanche() == $this->getParameter('param_is_hno') &&
                       $ligneFacturation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_acte')
                   ) {
                       $tarifActeHorsDimanche = floatval($ligneFacturation->getPrixUnit());
                   }

                   $this->client->request('PUT', $this->getParameter('param_update_tarif_in_suivirenta_url'), [
                       'body' => [
                           'pays' => $bdcProd->getPaysProduction()->getLibelle(),
                           'bu' => $ligneFacturation->getBu()->getLibelle(),
                           'client' => $bdcProd->getResumeLead()->getCustomer()->getRaisonSocial(),
                           'operation' => $ligneFacturation->getOperation()->getLibelle(),
                           'tarifheure' => ($ligneFacturation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_heure') ? floatval($ligneFacturation->getPrixUnit()) : null),
                           'tarifacte' => ($ligneFacturation->getTypeFacturation()->getId() == $this->getParameter('param_id_type_fact_acte') ? floatval($ligneFacturation->getPrixUnit()) : null),
                           'tarifformation' => $tarifFormation,
                           'tarifheuredimanche' => $tarifHeureDimanche,
                           'tarifactedimanche' => $tarifActeDimanche,
                           'tarifformationdimanche' => null,
                           'tarifheurehorsdimanche' => $tarifHeureHorsDimanche,
                           'tarifactehorsdimanche' => $tarifActeHorsDimanche
                       ]
                   ]);

                   return $this->json('Update data successfully', 200, [], []);
               }
            }

            return $this->json('Update data not successfully', 500, [], []);

        } catch (\Exception $exception) {
            return $this->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
