<?php

namespace App\Controller;

use App\Entity\Bdc;
use App\Entity\ResumeLead;
use App\Repository\BdcOperationRepository;
use App\Repository\BdcRepository;
use App\Repository\FamilleOperationRepository;
use App\Repository\WorkflowLeadRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class InformationController extends AbstractController
{
    /**
     * @Route("/get_Info/on/injected_bdc", name="info_via_his_irm", methods={"GET"})
     */
    public function getInfoViaIRM(BdcOperationRepository $bdcOperationRepository, BdcRepository $bdcRepository): Response
    {
        try {
            $statutEnProduction = $this->getParameter('statut_lead_bdc_signe_client');
            $dataBdcs = $bdcRepository->findBy([
                'statutLead' => $statutEnProduction
            ]);

            $tabFinal = array();
            foreach ($dataBdcs as $result){
                $irmClient = $result->getResumeLead()->getCustomer()->getIrm();
                $raisonSocial= $result->getResumeLead()->getCustomer()->getRaisonSocial();
                $numBdc = $result->getId();

                $data = array();
                foreach ($result->getBdcOperations() as $bdcOperation) {
                    if ($bdcOperation->getIrmOperation() != null) {
                        $libelle = $bdcOperation->getOperation()->getLibelle();
                        $irm = $bdcOperation->getIrmOperation();

                        $Qt = array();
                        foreach ($bdcOperation->getObjectifQuantitatif() as $objectifQuantitatif) {
                            $tempQt = $objectifQuantitatif->getLibelle();
                            array_push($Qt, $tempQt);
                        }

                        $Ql = array();
                        foreach ($bdcOperation->getObjectifQualitatif() as $objectifQualitatif) {
                            $tempQl = $objectifQualitatif->getLibelle();
                            array_push($Ql, $tempQl);
                        }
                        array_push($data, [
                            'libelle' => $libelle,
                            'idIrmOperation' => $irm,
                            'bjectifQuantitatifs' => $Qt,
                            'ObjectifQualitatifs' => $Ql
                        ]);
                    }
                }

                array_push($tabFinal, [
                    'idIrmClient' => $irmClient,
                    'raisonSocial' => $raisonSocial,
                    'numBdc' => $numBdc,
                    'operation' => $data
                ]);
            }

            return $this->json($tabFinal,200, [], ['groups' => ['via-irm']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/view/pdf/{id}", name="view_pdf", methods={"GET"})
     */
    public function viewTemplaPageForEmail(int $id): Response
    {
        $resumeLead = $this->getDoctrine()->getRepository(ResumeLead::class)->find($id);

        return $this->render('forServiceEtude/forServiceEtude.html.twig', [
            'resumeLead' => $resumeLead
        ]);
    }

    /**
     * @Route("/truncate/table", name="truncate_table", methods={"GET"})
     */
    public function truncateAndDropTable(FamilleOperationRepository $familleOperationRepository): Response
    {
        $res = $familleOperationRepository->truncateTableFamilleOperation();

        return $this->json($res, 200, [], []);
    }

    /**
     * @Route("/test/post/method", name="test_post_method", methods={"POST"})
     * @return Response
     */
    public function testeMethodPost(Request $request): Response
    {
        try {
            $datas = json_decode($request->getContent(), true);

            $result = $this->group_by("paysProduction", $datas);
            //var_dump($datas);

            /* $keys=[];
            foreach($groupedDatas as $key => $groupedData) {
                $keys[] = $key;
            } */

            return $this->json($result[1]["3"], 200, [], []);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Function that groups an array of associative arrays by some key.
     *
     * @param {String} $key Property to sort by.
     * @param {Array} $data Array that stores multiple associative arrays.
     */
    private function group_by($key, $data): array
    {
        $result = [];
        $keyOfPaysProd = [];

        foreach($data as $val) {
            if(array_key_exists($key, $val)){
                $result[$val[$key]][] = $val;
            }
        }

        if (!empty($result)){
            foreach($result as $key => $value) {
                $keyOfPaysProd[] = $key;
            }
        }

        return array($keyOfPaysProd, $result);
    }
}
