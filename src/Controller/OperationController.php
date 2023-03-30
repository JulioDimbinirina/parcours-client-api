<?php

namespace App\Controller;

use App\Entity\FamilleOperation;
use App\Entity\Operation;
use App\Repository\FamilleOperationRepository;
use App\Repository\OperationRepository;
use App\Service\CurrentBase64Service;
use App\Service\FileManipulate;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpParser\Node\Scalar\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Exception\ErrorMappingException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class OperationController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FamilleOperationRepository
     */
    private $familleOperationRepository;

    /**
     * @var OperationRepository
     */
    private $operationRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param FamilleOperationRepository $familleOperationRepository
     * @param OperationRepository $operationRepository
     */
    public function __construct(EntityManagerInterface $entityManager, FamilleOperationRepository $familleOperationRepository, OperationRepository $operationRepository){
        $this->entityManager = $entityManager;
        $this->familleOperationRepository = $familleOperationRepository;
        $this->operationRepository = $operationRepository;
    }
     /**
     * @Route("/operation/getfamilleoPeration", name="get_famille_Operation", methods={"GET"})
     */
    public function getFamilleOperation(FamilleOperationRepository $familleOperationRepository){
        $AllFamilleOp=$familleOperationRepository->findAll();
        return $this->json($AllFamilleOp, 200, [], ['groups' => ['get-by-bdc']]);
    }
    /**
     * @Route("/upload/operation", name="upload_operation", methods={"POST"})
     */
    public function uploadOperationFile(Request $request)
    {
        $istruncated = $this->deleteTableWithTruncate();

        if ($istruncated) {
            # Import le fichier
            $fileManipulate = new FileManipulate();
            $file = $fileManipulate->uploadFile($this->getParameter('bdc_dir'), $request);

            if ($file){
                $spreadsheet = IOFactory::load($file);

                for ($i = 0; $i < $spreadsheet->getSheetCount(); $i++) {
                    # Supprime le premier ligne
                    $spreadsheet->getSheet($i)->removeRow(1);

                    # Prend tout les cellules fusionnés
                    $sheetDataMerge = $spreadsheet->getSheet($i)->getMergeCells();

                    foreach ($sheetDataMerge as $index => $val) {
                        # Decoupe chaque valeur dans le tableau (Ex: "A19:A54" en "A19" et "A54")
                        $cellIndex = explode(":", $val);

                        # Prend la premiere index (Ex: 19 pour "A19:A54")
                        $startIndex = (int) substr($cellIndex[0],1);

                        # Prend la derniere index (Ex: 54 pour "A19:A54")
                        $endIndex = (int) substr($cellIndex[1],1);

                        # Prend la lettre correspondant au colonne (Ex: "A" pour "A19:A54")
                        $columnName = substr($cellIndex[0],0,1);

                        # Contient la valeur du cellule fusionné
                        $mainvalue = "";
                        for ($j = $startIndex; $j<= $endIndex; $j++) {
                            # $res = $spreadsheet->getActiveSheet()->getCell($columnName.$j)->getValue();
                            $res = $spreadsheet->getSheet($i)->getCell($columnName.$j)->getValue();

                            if ($res != null) {
                                $mainvalue = $res;
                            }
                        }

                        # Copie le valeur du cellule fusionné dans chaque cellule qui a pour valeur égal null
                        for ($k = $startIndex; $k<= $endIndex; $k++) {
                            # $res = $spreadsheet->getActiveSheet()->getCell($columnName.$k)->getValue();
                            $res = $spreadsheet->getSheet($i)->getCell($columnName.$k)->getValue();
                            if ($res == null) {
                                # $spreadsheet->getActiveSheet()->setCellValue($columnName.$k, $mainvalue);
                                $spreadsheet->getSheet($i)->setCellValue($columnName.$k, $mainvalue);
                            }
                        }
                    }

                    # Supprime les valeurs null dans le tableau
                    # $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                    $sheetData = $spreadsheet->getSheet($i)->toArray(null, true, true, true);
                    $sheetData = array_values(array_map('array_filter', $sheetData));

                    // dd($sheetData);

                    # Enregistrement des données dans la table famille operation et operation
                    $this->injectDataInDatabase($sheetData);
                }
            }

            return $this->json("Ok", 200, [], ['groups' => ['import-excel']]);
        }
    }

    private function deleteTableWithTruncate() {
        try {
            $this->operationRepository->truncateTableOperation();
            $this->familleOperationRepository->truncateTableFamilleOperation();
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function injectDataInDatabase(array $sheetData): void
    {
        foreach ($sheetData as $row) {
            $operationFamille = null;

            if (!empty($row["B"])) {
                $codeFamille = $row["A"] ?? "";
                $libelleFamille = $row["B"];

                $isFamilleOperationExist = $this->familleOperationRepository->findOneBy([
                    'libelle' => $libelleFamille,
                    'codeFamille' => $codeFamille
                ]);

                # Si la famille opération n'a pas encore existé, alors on l'a crée
                if (!$isFamilleOperationExist){
                    $familleOperation = new FamilleOperation();

                    # Si famille operation égal telecom ou malus, alors ce variable vaux 0, sinon il vaux 1.
                    $notInjectableFamilleOperation = (strtolower($codeFamille) == "tel" || strtolower($codeFamille) == "mal") ? 0 : 1;

                    $familleOperation->setCodeFamille($codeFamille);
                    $familleOperation->setIsIrm($notInjectableFamilleOperation);
                    $familleOperation->setIsSiRenta($notInjectableFamilleOperation);
                    $familleOperation->setIsSage($notInjectableFamilleOperation);
                    $familleOperation->setLibelle($libelleFamille);

                    $this->entityManager->persist($familleOperation);

                    $this->entityManager->flush();

                    $operationFamille = $familleOperation;

                } else {
                    $operationFamille = $isFamilleOperationExist;
                }
            }

            # Enregistrement des operations dans la base de données
            if (!empty($row["B"]) && !empty($row["C"]) && !empty($row["D"])) {
                $referenceArticle = $row["C"];
                $libelleOperation = $row["D"];

                $isOperationExist = $this->operationRepository->findOneBy([
                    'libelle' => $libelleOperation,
                    'referenceArticle' => $referenceArticle,
                    'familleOperation' => $operationFamille
                ]);

                if (!$isOperationExist){
                    $operation = new Operation();

                    $operation->setFamilleOperation($operationFamille);
                    $operation->setLibelle($libelleOperation);
                    $operation->setReferenceArticle($referenceArticle);

                    $this->entityManager->persist($operation);

                    $this->entityManager->flush();
                }
            }
        }
    }
}
