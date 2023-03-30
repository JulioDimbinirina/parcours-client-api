<?php

namespace App\Controller;

use App\Entity\CoutHoraire;
use App\Repository\BdcRepository;
use App\Repository\CoutHoraireRepository;
use App\Service\CurrentBase64Service;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class CoutHoraireController extends AbstractController
{
    /**
     * @Route("/edit/cout/horaire/{id}", name="edit_cout_horaire", methods={"PUT"})
     * @param int $id
     * @param Request $request
     * @param CoutHoraireRepository $coutHoraireRepository
     * @param EntityManagerInterface $manager
     * @return Response
     * Mise à jour cout horaire
     */
    public function updateCoutHoraire(int $id, Request $request,
                                      CoutHoraireRepository $coutHoraireRepository,
                                      EntityManagerInterface $manager): Response
    {
        try {
            // On va decodé l'objet JSON envoyé via front
            $jsonRecu = json_decode($request->getContent(), true);

            // On va faire une requete repository pour avoir le données correspond à cell de l'id envoyé par front
            $dataEdit = $coutHoraireRepository->find($id);

            // On va tester puis executer la mise à jour
            if (isset($jsonRecu, $dataEdit)) {

                $dataEdit->setCoutHoraire(!empty($jsonRecu['coutHoraire']) ? floatval($jsonRecu['coutHoraire']) : $dataEdit->getCoutHoraire());
                $dataEdit->setCoutFormation(!empty($jsonRecu['coutFormation']) ? floatval($jsonRecu['coutFormation']) : $dataEdit->getCoutFormation());

                $manager->persist($dataEdit);
                $manager->flush();
            }

            return $this->json('Data updated', 200, [], []);

        } catch (\Exception $exception) {
            return $this->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @Route ("/import/cout/annuel", name="import_cout_agent", methods={"POST"})
     * @param Request $request
     * @param CoutHoraireRepository $coutHoraireRepository
     * @param EntityManagerInterface $manager
     * @return Response
     * Fonction pour importer les couts annuels
     */
    public function importCoutAgent(Request $request,
                                    CoutHoraireRepository $coutHoraireRepository,
                                    EntityManagerInterface $manager,
                                    BdcRepository $bdcRepo,
                                    BonDeCommandeController $bdcControl
                                    ): Response
    {
        try {
            $fichierDecode = json_decode($request->getContent(), true);

            // Upload file
            $base64service = new CurrentBase64Service();
            $file = $base64service->convertToFile($fichierDecode['name'], $this->getParameter('cout_horaire_file_dir'), 'XLS_');

            $fileFolder = $this->getParameter('cout_horaire_file_dir');

            $spreadsheet = IOFactory::load($fileFolder . $file);

            $sheetDataOne = $spreadsheet->getActiveSheet()->toArray(null, true, true,true);

            // Decouper tous les chaines avant de comparer
            /*$langueSpecialite = str_replace(array('/', ' ', 'é'), '', $sheetDataOne[1]["D"]);
            $coutHoraire = str_replace(array('û', ' ', 'é'), '', $sheetDataOne[1]["E"]);
            $coutFormation = str_replace(array('û', ' '), '', $sheetDataOne[1]["F"]);*/

            // Condtion pour le fichier importer soit conforme au model
            if (
                strtolower($sheetDataOne[1]["A"]) == "pays" &&
                strtolower($sheetDataOne[1]["B"]) == "bu" &&
                strtolower($sheetDataOne[1]["C"]) == "niveaux"
            ) {
                // Supprime l'entete de notre fichier
                $spreadsheet->getActiveSheet()->removeRow(1);

                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true,true);

                // Logique date (du premier janvier au 31 decembre de l'année en cours)
                $dateNow = new \DateTime();
                $year = !empty($fichierDecode['annee']) ? $fichierDecode['annee'] : $dateNow->format("Y");
                $january = "01";
                $december = "12";
                $dayJanuary = "01";
                $dayDecember = "31";
                $dateDebut = date("m/d/Y", mktime(0,0,0, $january, $dayJanuary, $year));
                $dateFin = date("m/d/Y", mktime(0,0,0, $december, $dayDecember, $year));
                
                $coutArrayHashage = [];
                if (!empty($sheetData)) {

                    foreach ($sheetData as $row) {
                        /*
                         * On va faire une verification des données entre interval des dates,
                         * pays, bu et langue de spécialité
                         */
                        $date1 = date('Y-m-d', strtotime($dateDebut));
                        $date2 = date('Y-m-d', strtotime($dateFin));
                        $isExist = $coutHoraireRepository->verifDataExisteEntity($date1, $date2, $row["A"], $row["B"], $row["D"]);
                        if (!empty($isExist)) {
                            if (!empty($row["E"]) && !empty($row["F"])) {
                                $coutHoraireRepository->updateData($row["E"], $row["F"], $date1, $date2, $row["A"], $row["B"], $row["D"]);
                                $coutArrayHashage[$isExist[0]->getPays()][$isExist[0]->getBu()][$isExist[0]->getLangueSpecialite()]=$isExist[0];

                            }
                        } else {
                            if (!empty($row["E"]) && !empty($row["F"])) {

                                // On va créer notre objet CoutHoraire (Instance d'objet)
                                $coutHoraire = new CoutHoraire();

                                $coutHoraire->setDateDebut(\DateTime::createFromFormat('m/d/Y', $dateDebut));
                                $coutHoraire->setDateFin(\DateTime::createFromFormat('m/d/Y', $dateFin));
                                $coutHoraire->setPays($row["A"] ?? null);
                                $coutHoraire->setBu($row["B"] ?? null);
                                $coutHoraire->setNiveau($row["C"] ?? null);
                                $coutHoraire->setLangueSpecialite($row["D"] ?? null);
                                $coutHoraire->setCoutHoraire($row["E"]);
                                $coutHoraire->setCoutFormation($row["F"]);
                                // On va balancer(executer) notre requete à l'aide du MANAGER
                                $manager->persist($coutHoraire);
                                $manager->flush();
                                $isExist = $coutHoraireRepository->verifDataExisteEntity($date1, $date2, $row["A"], $row["B"], $row["D"]);
                                $coutArrayHashage[$coutHoraire->getPays()][$coutHoraire->getBu()][$coutHoraire->getLangueSpecialite()]=$isExist[0];
                            }
                        }
                    }
                    
                
                }
                $allBdc=$bdcRepo->getAllLastBdcByIdMere();
                foreach($allBdc as $bdc){
                    if(in_array($bdc->getStatutLead(), [11, 20])){
                        # En Production Alors Il y a Duplication

                        $bdcNewVersion = $bdcControl->saveBdcDupsAndNewVersion($bdc,$manager,null,null);
                        $allBdcOperationDuBdc = $bdcNewVersion->getBdcOperations();
                        foreach($allBdcOperationDuBdc as $LigneFacturation){
                            if($LigneFacturation->getCoutHoraire())
                                if(isset($coutArrayHashage[$LigneFacturation->getCoutHoraire()->getPays()][$LigneFacturation->getCoutHoraire()->getBu()][$LigneFacturation->getCoutHoraire()->getLangueSpecialite()])){
                                    $LigneFacturation->setCoutHoraire($coutArrayHashage[$LigneFacturation->getCoutHoraire()->getPays()][$LigneFacturation->getCoutHoraire()->getBu()][$LigneFacturation->getCoutHoraire()->getLangueSpecialite()]);
                                    $manager->persist($LigneFacturation);
                                    $manager->flush();
                                }
                        }
                        

                    }
                    else{
                        # Pas En Production

                        #BdcOperation Du Chaque BDC
                        $allBdcOperationDuBdc = $bdc->getBdcOperations();
                        foreach($allBdcOperationDuBdc as $LigneFacturation){
                            if($LigneFacturation->getCoutHoraire())
                                if(isset($coutArrayHashage[$LigneFacturation->getCoutHoraire()->getPays()][$LigneFacturation->getCoutHoraire()->getBu()][$LigneFacturation->getCoutHoraire()->getLangueSpecialite()])){
                                    $LigneFacturation->setCoutHoraire($coutArrayHashage[$LigneFacturation->getCoutHoraire()->getPays()][$LigneFacturation->getCoutHoraire()->getBu()][$LigneFacturation->getCoutHoraire()->getLangueSpecialite()]);
                                    $manager->persist($LigneFacturation);
                                    $manager->flush();
                                }
                        }
                    }
                }

                return $this->json("data imported", 200, [], []);

            } else {
                return $this->json(["messageAlert" => "Model excel non conforme"], 200, [], []);
            }

        } catch (\Exception $exception) {
            return $this->json([
                'status' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
