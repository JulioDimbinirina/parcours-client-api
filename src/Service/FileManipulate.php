<?php
namespace App\Service;

use App\Repository\BdcOperationRepository;
use App\Repository\BdcRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;

class FileManipulate {
    /**
     * @param $path
     * @param $request
     * @param int|null $isViaBase64
     * @return string
     */
    public function uploadFile($path, $request, int $isViaBase64 = null): string
    {
        if ($isViaBase64){
            $fileData = json_decode($request->getContent(), 'true');

            $base64service = new CurrentBase64Service();

            $fileName = $base64service->convertToFile($fileData['file'], $path, 'XLS_');
        } else {
            # Use for insomnia post
            $file = $request->files->get('file'); // get the file from the sent request

            # apply md5 function to generate an unique identifier for the file and concat it with the file extension
            $fileName = md5(uniqid()) . $file->getClientOriginalName();

            try {
                $file->move($path, $fileName);
            } catch (FileException $e) {
                dd($e);
            }
        }

        return $path.$fileName;
    }

    public function setDataOfMergeCell($spreadsheet)
    {
        # Prend tout les cellules fusionnés
        $sheetDataMerge = $spreadsheet->getSheet(0)->getMergeCells();

        foreach ($sheetDataMerge as $val) {
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
                $res = $spreadsheet->getSheet(0)->getCell($columnName.$j)->getValue();

                if ($res != null) {
                    $mainvalue = $res;
                }
            }

            # Copie le valeur du cellule fusionné dans chaque cellule qui a pour valeur égal null
            for ($k = $startIndex; $k<= $endIndex; $k++) {
                # $res = $spreadsheet->getActiveSheet()->getCell($columnName.$k)->getValue();
                $res = $spreadsheet->getSheet(0)->getCell($columnName.$k)->getValue();
                if ($res == null) {
                    # $spreadsheet->getActiveSheet()->setCellValue($columnName.$k, $mainvalue);
                    $spreadsheet->getSheet(0)->setCellValue($columnName.$k, $mainvalue);
                }
            }
        }

        # Supprime le premier ligne
        $spreadsheet->getSheet(0)->removeRow(1);

        # Supprime les valeurs null dans le tableau
        $sheetData = $spreadsheet->getSheet(0)->toArray(null, true, true, true);

        return array_values(array_map('array_filter', $sheetData));
    }

    /**
     * @param $file
     * @return bool
     */
    public function deleteFile($file): bool
    {
        if (file_exists($file)) {
            unlink($file);
            return true;
        }
        return false;
    }
}