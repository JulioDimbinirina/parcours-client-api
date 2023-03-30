<?php

namespace App\Controller;

use App\Entity\Bdc;
use App\Repository\BdcRepository;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\SendMailTo;
use Symfony\Component\Security\Core\User\UserInterface;

class SignatureController extends AbstractController
{
    /**
     * @Route("/send/to/sign/{id}", name="send_to_sign", methods={"GET"})
     */
    public function sendingTosign(int $id, BdcRepository $bdcRepository, EntityManagerInterface $em, ContactRepository $contactRepository): Response
    {
		$bdc = $bdcRepository->find($id);

		# Recuperation email de contact du client
		$customer = $bdc->getResumeLead()->getCustomer();
		$contacts = $customer->getContacts();

        # Recuperation destinataire du BDC
        $destinataires = $bdc->getDestinataireSignataire();

        if(empty($destinataires))
        {
            $destinataires = [];

            # Recuperation d'un contact destinataire. Temp
            foreach($contacts As $contact)
            {
                $destinataires[] = $contact->getId();
            }
        }

        # Prendre un contact destinataire
        $signataire = array();
        foreach($destinataires As $contactId)
        {
            $contact = $contactRepository->find($contactId);

            $signataire["name"] = ($contact->getPrenom() . " " . $contact->getNom()) ?? "";
            $signataire["email"] = $contact->getEmail();

            break;
        }

		$files = [
			['type' => 'doc1', 'fileName' => 'bdc_' . $bdc->getIdMere() . '.pdf']
		];

		$page = $this->nbr_pages($this->getParameter('bdc_dir') . 'bdc_' . $bdc->getIdMere() . '.pdf');

        $this->sendToSign($files, $signataire, $bdc, $em, $page, 1);

        return $this->json("Bon de commande envoyé avec succès !", 200, [], ['groups' => ['sendtosign']]);
    }

	/**
     * @Route("/send/to/sign/com/{id}", name="send_to_sign_com", methods={"GET"})
     */
    public function sendingTosignCom(int $id, BdcRepository $bdcRepository, EntityManagerInterface $em, SendMailTo $sendMailTo): Response
    {
		$bdc = $bdcRepository->find($id);

		//Recuperation email de contact du commercial
		$dest = $bdc->getResumeLead()->getCustomer()->getUser();

		$signataire["name"] = $dest->getUsername();
        $signataire["email"] = $dest->getEmail();

		$files = [
			['type' => 'doc1', 'fileName' => 'bdc_com_' . $id . '.pdf']
		];

		$page = $this->nbr_pages($this->getParameter('bdc_dir') . 'bdc_com_' . $id . '.pdf');

        $this->sendToSign($files, $signataire, $bdc, $em, $page);

        return $this->json("Bon de commande envoyé au commercial avec succès !", 200, [], ['groups' => ['sendtosign']]);
    }

    private function sendToSign($files, $signataire, $bdc, $em, $page, int $isSendToCustomer = null){
        /* if($isSendToCustomer)
        {
            switch($page)
            {
                case 6:
                    $top = 61.90625;
                    $left = 30.99609375;
                    $right = 480.23828125;
                    $bottom = 10.16015625;
                    $pageToSign = 2;
                    $tabIndex = 0;
                    break;
                case 7:
                    $top = 81.90625;
                    $left = 30.99609375;
                    $right = 480.23828125;
                    $bottom = 20.16015625;
                    $pageToSign = 3;
                    $tabIndex = 0;
                    break;
                case 8:
                default:
                    $top = 421.90625;
                    $left = 30.99609375;
                    $right = 220.23828125;
                    $bottom = 360.16015625;
                    $tabIndex = 0;
                    break;
            }
        }
        else
        {
            switch($page)
            {
                case 1:
                    $top = 61.90625;
                    $left = 30.99609375;
                    $right = 160.23828125;
                    $bottom = 10.16015625;
                    $tabIndex = 0;
                    break;
                case 2:
                    $top = 81.90625;
                    $left = 30.99609375;
                    $right = 160.23828125;
                    $bottom = 20.16015625;
                    $tabIndex = 0;
                    break;
                case 3:
                default:
                    $top = 461.90625;
                    $left = 30.99609375;
                    $right = 160.23828125;
                    $bottom = 400.16015625;
                    $tabIndex = 0;
                    break;
            }
        } */

        $top = 485.90625;
        $left = 30.99609375;
        $right = 217.23828125;
        $bottom = 424.16015625;

        $tabIndex = 0;

        switch($page)
        {
            case 4:
                $pageToSign = 2;
                break;
            case 5:
                $pageToSign = 3;
                break;
            case 6:
                $pageToSign = 4;
                break;
            case 7:
                $pageToSign = 5;
                break;
            default:
                $pageToSign = 6;
                break;
        }

        $documentOptions = [
            'doc1' => [
                'label' => 'Document 1',
                'docType' => 'PDF',
                'widgets' => [
                    [
                        "pageNumber" => $pageToSign,
                        "top" => $top,
                        "left" => $left,
                        "right" => $right,
                        "bottom" => $bottom,
                        "tabIndex" => $tabIndex
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

        if (in_array($bdc->getStatutLead(), $this->getParameter('statut_lead_validate_by_dir_fin'))){
            $oblNotif = "Bon De Commande numero ". $bdc->getNumBdc() . " pour la societe " . $bdc->getResumeLead()->getCustomer()->getRaisonSocial() ." à signer";
        } else {
            $oblNotif = "Bon De Commande à signer de la pars de Outsourcia";
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
            "mailSubject" => $oblNotif,
            "mailMessage" => "Merci de cliquer sur le bouton ci-dessous pour passer à la signature électronique du Bon De Commande SVP.<br/>Cordialement, ",
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

                # Stocké dans la BDD l'id du package
                if ($isSendToCustomer == 1) {
                    $bdc->setSignaturePackageId($response->id);
                } else {
                    $bdc->setSignaturePackageComId($response->id);
                }

                $em->persist($bdc);
                $em->flush();

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

	private function nbr_pages($pdf){
	   if (false !== ($fichier = file_get_contents($pdf))){
		  $pages = preg_match_all("/\/Page\W/", $fichier, $matches);
		  return $pages;
	   }
	}

}
