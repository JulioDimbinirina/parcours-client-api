<?php
namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class GetStatutLeadViaRole {
    public function getStatut($role){
        $statut = null;
        $statut1 = null;

        switch ($role){
            case "ROLE_DIRPROD":
                $statut = 3;
                $statut1 = 12;
                break;
            case "ROLE_FINANCE":
                $statut = 4;
                $statut1 = 13;
                break;
            case "ROLE_DG":
                $statut = 6;
                $statut1 = 15;
                break;
        }

        return [$statut, $statut1];
    }
}