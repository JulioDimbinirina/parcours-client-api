<?php
namespace App\Service;

use App\Repository\BdcOperationRepository;
use App\Repository\BdcRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;

class CaMensuel {
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function getCaMensuel($bdcOperations){
        $caMensuel = 0;

        foreach ($bdcOperations as $operation){
            /**
             * Si ligne fact est mixte,
             * $totalHT2 = (prixActe * qteActe) + (prixHeure * qteHeure)
             * sinon, $totalHT2 = prixUnitaire * quantite
             */
            if ($operation->getTypeFacturation()->getId() == $this->parameterBag->get('param_id_type_fact_mixte')){
                $caMensuel += ($operation->getPrixUnitaireActe() * $operation->getQuantiteActe()) + ($operation->getPrixUnitaireHeure() * $operation->getQuantiteHeure());
            } else {
                $caMensuel += $operation->getPrixUnit() * $operation->getQuantite();
            }
        }

        return $caMensuel;
    }
}