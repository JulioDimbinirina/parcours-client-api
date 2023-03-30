<?php
namespace App\Service;

use App\Repository\BdcOperationRepository;
use App\Repository\BdcRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;

class CheckSimilatiryText {
    /**
     * @param $allCustomers
     * @param $crmRaisonSocialArray
     * @return array
     * Recupère les données qui ont des correspondance
     */
    public function getSimilarData($raisonSocialArray, $allCustomers, int $percent = 60): array
    {
        $datas = [];
        $noCorrespondanceRasisonSocials = [];

        $tmpSageRs = [];

        foreach ($raisonSocialArray as $client){
            foreach ($allCustomers as $customer){
                $res = $this->similarityText($client["client1"], $customer->getRaisonSocial());

                if ($res >= $percent){
                    $societeClean = strtolower(str_replace(' ', '', $customer->getRaisonSocial()));
                    if (!in_array($societeClean, $tmpSageRs)){
                        $datas[] = [
                            $client["id"],
                            $client["client1"],
                            $customer->getRaisonSocial(),
                            "",
                            "",
                        ];

                        $tmpSageRs[] = $societeClean;
                    }
                } else {
                    !in_array($client["client1"], $noCorrespondanceRasisonSocials) && $noCorrespondanceRasisonSocials[] = $client["client1"];
                }
            }
        }

        return [$datas, $noCorrespondanceRasisonSocials];
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
     * @param $datas
     * @param $sheet
     * Fusionne les cellules
     */
    public function mergeCells($datas, $sheet): void
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
     * @param $sheet
     * @param $noCorrespondanceRasisonSocials
     * Ajouter tout les raisons sociales qui n'ont pas
     * de correspondance dans la colonne E de l'excel
     */
    public function setValueOfColumnE($sheet, $noCorrespondanceRasisonSocials): void
    {
        $noCorresNb = count($noCorrespondanceRasisonSocials);

        for ($i=0; $i < $noCorresNb; $i++){
            # Cellule E2 à En
            $cell = "E". ($i+2);
            $sheet->setCellValue($cell, $noCorrespondanceRasisonSocials[$i]);
        }
    }
}