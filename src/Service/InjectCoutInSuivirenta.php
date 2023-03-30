<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class InjectCoutInSuivirenta
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    public function __construct(HttpClientInterface $client){
        $this->client = $client;
    }

    public function injectOrUpdateCoutToSuivirenta($bdc, $method, $url)
    {
        foreach ($bdc->getBdcOperations() as $ligneFacturation) {
            $coutHoraire = $ligneFacturation->getCoutHoraire();

            if (!empty($coutHoraire)){
                $customer = $bdc->getResumeLead()->getCustomer();
                $operation = $ligneFacturation->getOperation();

                $response = $this->client->request($method, $url, [
                    'body' => [
                        'pays' => $coutHoraire->getPays(),
                        'bu' => $coutHoraire->getBu(),
                        'client' => $customer->getRaisonSocial(),
                        'operation' => $operation->getLibelle(),
                        'date_debut' => $coutHoraire->getDateDebut()->format('Y-m-d H:i:s'),
                        'date_fin' => $coutHoraire->getDateFin()->format('Y-m-d H:i:s'),
                        'coutactivite' => $coutHoraire->getCoutHoraire(),
                        'coutformation' => $coutHoraire->getCoutFormation(),
                    ]
                ]);
            }
        }
        return $response;
    }
}