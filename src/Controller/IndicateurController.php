<?php

namespace App\Controller;

use App\Entity\BdcOperation;
use App\Entity\IndicatorQualitatif;
use App\Entity\IndicatorQuantitatif;
use App\Entity\ObjectifQualitatif;
use App\Entity\ObjectifQuantitatif;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class IndicateurController extends AbstractController
{

    /**
     * @Route ("/save/indicateur/qt", name="indicateur_qt", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * Enregistrement indicateur objectif quantitatif dans la base
     */
    public function saveIndicateurQuantitatif(Request $request, EntityManagerInterface $manager): Response {
        try {
            $jsonRecu = json_decode($request->getContent(), true);
            $data = new IndicatorQuantitatif();
            if (isset($jsonRecu)) {
                $data->setIndicator($jsonRecu['indicator'] ?? null);
                $data->setBdcOperation($this->getDoctrine()->getRepository(BdcOperation::class)->find($jsonRecu['bdcOperation']));
                $data->setObjectifQuantitatif($this->getDoctrine()->getRepository(ObjectifQuantitatif::class)->find($jsonRecu['objectifQuantitatif']));
                $manager->persist($data);
            }
            $manager->flush();
            return $this->json($data, 200, [], ['groups' => ['save-qt']]);
        } catch (\Exception $exception) {
            return  $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @Route ("/save/indicateur/qual", name="indicateur_qual", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * Enregistrement indicateur objectif qualitatif........
     */
    public function saveIndicatorQualitatif(Request $request, EntityManagerInterface $manager): Response {
        try {
            $jsonRecu = json_decode($request->getContent(), true);
            $donnee = new IndicatorQualitatif();
            if (isset($jsonRecu)) {
                $donnee->setIndicator($jsonRecu['indicator'] ?? null);
                $donnee->setBdcOperation($this->getDoctrine()->getRepository(BdcOperation::class)->find($jsonRecu['bdcOperation']));
                $donnee->setObjectifQualitatif($this->getDoctrine()->getRepository(ObjectifQualitatif::class)->find($jsonRecu['objectifQualitatif']));
                $manager->persist($donnee);
            }
            $manager->flush();
            return $this->json($donnee, 200, [], ['groups' => ['save-qual']]);
        } catch (\Exception $exception) {
            return  $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }
}
