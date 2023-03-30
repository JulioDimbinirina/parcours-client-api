<?php
namespace App\Service;

use App\Entity\Bdc;
use App\Entity\RejectBdc;
use App\Repository\BdcRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MixedService extends AbstractController{
    public function __construct(){}

    public function getAllByEntity(string $entityName){
        $data = $this->getDoctrine()->getRepository(sprintf('App\Entity\%s', $entityName))->findAll();
        return $data;
    }
}


