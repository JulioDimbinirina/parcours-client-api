<?php
namespace App\Service;

use App\Entity\Bdc;

class FilterLigneFacturation {
    /**
     * @param Bdc $actualBdc
     * @param $datasFront
     * @return array[]
     * filtre les operations modifiés et les nouveaux operations
     */
    public function filterBdcOperationArray(Bdc $actualBdc, $datasFront): array
    {
        # Contient les bu(s) de l'actuel bdc
        $idBusinessUnitBdc = [];

        # Recuperation de tout les bus appartenant au bdc
        foreach ($actualBdc->getBdcOperations() as $lignFacturation){
            if (!empty($lignFacturation->getBu())){
                $idBusinessUnitBdc[] = $lignFacturation->getBu()->getId();
            }
        }

        $lignFactEditedTarifForActualBdc = [];

        $lignFactToAddOnActualBdc = [];

        $lignFactToCreateBdc = [];

        # Recuperation de tout les bus appartenant au bdc
        foreach ($datasFront as $lignFact){
            if (!empty($lignFact['newOperation']) && $lignFact['newOperation'] == "ok"){
                /**
                 * Si pays de production de l'actuel bdc est different du pays de production du nouveau ligne de facturation,
                 * Ou si le bu du nouveau ligne de facturation n'est pas encore dans les bus du bdc actuel,
                 * alors, on créera une nouvel bon de commande
                 */
                if (($actualBdc->getPaysProduction()->getId() != intval($lignFact['paysProduction'])) ||
                    !in_array(intval($lignFact['bu']), $idBusinessUnitBdc)){
                    $lignFactToCreateBdc[] = $lignFact;
                }

                /**
                 * Si pays de production du nouvelle operation est la même que celle du bdc,
                 * alors on ajoute ce nouvelle operation dans le bdc courant
                 */
                if (($actualBdc->getPaysProduction()->getId() == intval($lignFact['paysProduction'])) &&
                    in_array(intval($lignFact['bu']), $idBusinessUnitBdc)){
                    $lignFactToAddOnActualBdc[] = $lignFact;
                }
            }

            # S'il y a modification tarif dans bdc, alors on le met dans le tableau qui contient les operations Avenant
            if (!empty($lignFact['isTarifEdited'])){
                $lignFactEditedTarifForActualBdc[] = $lignFact;
            }
        }

        return array($lignFactEditedTarifForActualBdc, $lignFactToAddOnActualBdc, $lignFactToCreateBdc);
    }

    /**
     * @param $datasFront
     * @return array[]
     * filtre les operations modifiés et les nouveaux operations
     */
    public function filterBdcOperationArrayForBdcSignedByCom($datasFront): array
    {
        # Contient les operations avec des tarif modifié
        $lignFactEditedTarifForActualBdc = [];

        # Contient les nouveaux operations
        $lignFactToAddOnActualBdc = [];

        foreach ($datasFront as $lignFact){
            # S'il y a modification tarif dans bdc, alors on le met dans le tableau qui contient les operations editées
            if (!empty($lignFact['isTarifEdited'])){
                $lignFactEditedTarifForActualBdc[] = $lignFact;
            }

            # S'il y a nouveaux operations, alors on le met dans le tableau ci-dessus
            if (!empty($lignFact['newOperation'])){
                $lignFactToAddOnActualBdc[] = $lignFact;
            }
        }

        return array($lignFactEditedTarifForActualBdc, $lignFactToAddOnActualBdc);
    }

    /**
     * Function that groups an array of associative arrays by some key.
     *
     * @param {String} $key Property to sort by.
     * @param {Array} $data Array that stores multiple associative arrays.
     */
    public function group_by($key, $data): array
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