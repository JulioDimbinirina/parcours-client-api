<?php
namespace App\Service;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttplugClient;

class ParametrageSuiviRenta{
    private $httpClient;
    private $httpcl;

    public function __construct(HttpClientInterface $httpClient){
        $this->httpClient = $httpClient;
    }

    public function getDataOnCurrentSuiviRenta($url, string $token = null){
        return $this->httpClient->request('GET', $url, [
            'auth_bearer' => $token
        ]);
    }

    public function getDataOnCurrentSuiviRentaPost($url,$data = [], string $method = "POST", string $token = null){
        return $this->httpClient->request($method, $url, [
            'auth_bearer' => $token,
            'json' => $data
        ]
    );

    }

    public function deleteData($url){
        return $this->httpClient->request("DELETE", $url);
    }

    public function getAsyncSuivi($url){
        $request = $this->httpcl->createRequest('GET', "http://localhost:12992/api/saisie/manager/facturation2/CDISCOUNT/12/2021");
        return $this->httpcl->sendAsyncRequest($request);
    }
}