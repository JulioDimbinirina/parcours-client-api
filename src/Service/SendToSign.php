<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Response;

class SendToSign {

    public function sendToSign($files, $signataire){

        $documentOptions = [
            'doc1' => [
                'label' => 'Document 1',
                'docType' => 'PDF',
                'widgets' => [
                    [
                        "pageNumber" => 1,
                        "top" => 521.90625,
                        "left" => 351.99609375,
                        "right" => 560.23828125,
                        "bottom" => 460.16015625,
                        "tabIndex" => 0
                    ]
                ]
            ],
            'doc2' => [
                'label' => 'Document 2',
                'docType' => 'MS_WORD',
                'widgets' => [[
                    "pageNumber" => 3,
                    "top" => 113.484375,
                    "left" => 330.24609375,
                    "right" => 516.73828125,
                    "bottom" => 59.98828125,
                    "tabIndex" => 0
                ]]
            ],
            'doc3' => [
                'label' => 'Document3',
                'docType' => 'PDF',
                'widgets' => []
            ]
        ];

        $encodedFiles = [];
        foreach ($files as $k => $file) {
            $options = $documentOptions[$file['type']];
            if ($options['widgets']) {
                $encodedFiles[] =
                    [
                        "content" => base64_encode(file_get_contents($this->getParameter('bdc_dir') . $file['fileName'])),
                        "signatureFields" => [
                            [
                                "id" => "signature-" . $k,
                                "signerId" => "signer-1",
                                "signingModeOptions" => ["C2S"],
                                "required" => true,
                                "readOnly" => false,
                                "widgets" => $options['widgets']
                            ]
                        ],
                        "id" => "document-" . $k,
                        "fileName" => $file['fileName'],
                        "format" => $options['docType'],
                        "name" => $options['label']
                    ];
            } else {
                $encodedFiles[] =
                    [
                        "content" => base64_encode(file_get_contents($this->getParameter('bdc_dir') . $file['fileName'])),
                        "id" => "document-" . $k,
                        "fileName" => $file['fileName'],
                        "format" => $options['docType'],
                        "name" => $options['label']
                    ];
            }
        }

        $postParams = [
            "name" => "[BON DE COMMANDE A SIGNE]",
            "description" => "[BON DE COMMANDE A SIGNE]",
            "expirationDate" =>  date('Y-m-d', strtotime('+' . $this->getParameter('package_expiration') . ' month')) . 'T00:00:00Z',
            "documents" => $encodedFiles,
            "signingModeOptions" => ["C2S"],
            "type" => "PACKAGE",
            "state" => "DRAFT",
            "processingType" => "PAR",
            "mailSubject" => 'Bon De Commande à signer',
            "mailMessage" => "Un Bon De Commande vous a été créé.<br/> Merci de cliquer sur le bouton ci-dessous pour passer à la signaturer eléctroniaque SVP.<br/>Cordialement, <br/> Groupe Outsourcia",
            "inPersonEnabled" => true,
            "signers" => [
                [
                    "id" => "signer-1",
                    "role" => "SIGNER",
                    "name" => $signataire['name'],
                    "email" => $signataire['email'],
                ]
            ]
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt_array($curl, [
            CURLOPT_PORT => $this->getParameter('signdoc_port'),
            CURLOPT_URL => $this->getParameter('signdoc_url') . "cirrus/rest/v7/package",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($postParams),
            CURLOPT_HTTPHEADER => [
                "API-key: " . $this->getParameter('signdoc_api_key'),
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            var_dump("cURL Error #:" . $err);
            return false;
        } else {
            echo "reponse 1 : <br/>";
            var_dump($response);

            $response = json_decode($response);
            if (isset($response->id)) {
                $curl2 = curl_init();
                curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt_array($curl2, [
                    CURLOPT_PORT => $this->getParameter('signdoc_port'),
                    CURLOPT_URL => $this->getParameter('signdoc_url') . "cirrus/rest/v7/packages/" . $response->id . "/scheduler",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_HTTPHEADER => [
                        "API-key: " . $this->getParameter('signdoc_api_key'),
                        "Content-Type: application/json"
                    ],
                ]);

                $rep = curl_exec($curl2);
                $err = curl_error($curl2);

                curl_close($curl2);

                echo "reponse 2 : <br/>";
                var_dump($rep);

                if ($err) {
                    var_dump("cURL Error #:" . $err);
                    return false;
                }
            } else {
                var_dump($response);
                return false;
            }
        }

        return $response->id;
    }
}