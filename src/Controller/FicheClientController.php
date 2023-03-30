<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\FicheClient;
use App\Entity\NaturePrestation;
use App\Repository\BudgetAnnuelRepository;
use App\Repository\FicheClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api")
 */
class FicheClientController extends AbstractController
{
    /**
     * @Route("/fiche/client/{id}", name="fiche_client", methods={"GET"})
     * @param int $id
     * @param FicheClientRepository $repository
     * @return Response
     * Obtenir une liste fiche client et customer par rapport à son id........
     */
    public function getFicheClient(int $id, FicheClientRepository $repository): Response
    {
        try {
            $customer = $this->getDoctrine()->getRepository(Customer::class)->find($id);
            $ficheClient = $repository->findByIdCustomer($id);
            return $this->json([$ficheClient, $customer], 200, [], ['groups' => ['fiche-client', 'list-fiche']]);
        } catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @Route ("/budget/annuel", name="fiche_client_annee", methods={"POST"})
     * @param BudgetAnnuelRepository $budgetAnnuelRepository
     * @return Response
     * Calcul du budget annuel par pays de production pendant l'année en cours.....
     */
    public  function getBudgetAnnuelByPaysProductionAndAnneeEnCours(Request $request, BudgetAnnuelRepository $budgetAnnuelRepository) : Response
    {
        try {
            $jsonRecu = json_decode($request->getContent(), true);
            $year = date("Y"); // Obtebir l'année en cours
            $data = array();
            $sommeCaAnnuel = 0;
            if (!empty($jsonRecu)) {
                $data = $budgetAnnuelRepository->findByAnnee($year, $jsonRecu['pays1'], $jsonRecu['pays2'],
                    $jsonRecu['pays3'], $jsonRecu['pays4']);
                foreach ($data as $item) {
                    $sommeCaAnnuel += $item->getCaAnnuelNplusUn();
                }
            }
            return $this->json(["sommeCaAnnuelNplusUn" => $sommeCaAnnuel, "data" => $data], 200, [], []);
        } catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @Route ("/budget/mmoins/un", name="budget_m_moins_un", methods={"POST"})
     * @param Request $request
     * @param BudgetAnnuelRepository $budgetAnnuelRepository
     * @return Response
     *  Si on est en mois de Novembre, on va recuperer le budget Octobre..............
     */
    public function getBudgetMmoinsUn(Request $request,
                                      BudgetAnnuelRepository $budgetAnnuelRepository) : Response
    {
        try {
            $jsonRecu = json_decode($request->getContent(), true);
            $year = date("Y"); // Obtenir l'année en cours
            $month = date("m"); // Obtenir le mois en cours
            $mois = [];
            switch ($month) {
                case 1:
                    // stand by
                    $mois = $budgetAnnuelRepository->findByMonthDecember($year, $jsonRecu['pays1'],
                        $jsonRecu['pays2'], $jsonRecu['pays3'], $jsonRecu['pays4']);
                    break;
                case 2:
                    // On recupere le mois de janvier
                    $mois = $budgetAnnuelRepository->findByMonthJanuary($year, $jsonRecu['pays1'],
                        $jsonRecu['pays2'], $jsonRecu['pays3'], $jsonRecu['pays4']);
                    break;
                case 3:
                    // On recupere le mois de fevrier
                    $mois = $budgetAnnuelRepository->findByMonthFebruary($year, $jsonRecu['pays1'],
                        $jsonRecu['pays2'], $jsonRecu['pays3'], $jsonRecu['pays4']);
                    break;
                case 4:
                    // On recupere le mois de mars
                    $mois = $budgetAnnuelRepository->findByMonthMarch($year, $jsonRecu['pays1'],
                        $jsonRecu['pays2'], $jsonRecu['pays3'], $jsonRecu['pays4']);
                    break;
                case 5:
                    // On recupere le mois avril
                    $mois = $budgetAnnuelRepository->findByMonthApril($year, $jsonRecu['pays1'],
                        $jsonRecu['pays2'], $jsonRecu['pays3'], $jsonRecu['pays4']);
                    break;
                case 6:
                    // On recupere le mois de mai
                    $mois = $budgetAnnuelRepository->findByMonthMay($year, $jsonRecu['pays1'],
                        $jsonRecu['pays2'], $jsonRecu['pays3'], $jsonRecu['pays4']);
                    break;
                case 7:
                    // On recupere le mois de juin
                    $mois = $budgetAnnuelRepository->findByMonthJune($year, $jsonRecu['pays1'],
                        $jsonRecu['pays2'], $jsonRecu['pays3'], $jsonRecu['pays4']);
                    break;
                case 8:
                    // On recupere le mois de juillet
                    $mois = $budgetAnnuelRepository->findByMonthJuly($year, $jsonRecu['pays1'],
                        $jsonRecu['pays2'], $jsonRecu['pays3'], $jsonRecu['pays4']);
                    break;
                case 9:
                    // On recupere le mois de aout
                    $mois = $budgetAnnuelRepository->findByMonthAugust($year, $jsonRecu['pays1'],
                        $jsonRecu['pays2'], $jsonRecu['pays3'], $jsonRecu['pays4']);
                    break;
                case 10:
                    // On recupere le mois de septembre
                    $mois = $budgetAnnuelRepository->findByMonthSeptember($year, $jsonRecu['pays1'],
                        $jsonRecu['pays2'], $jsonRecu['pays3'], $jsonRecu['pays4']);
                    break;
                case 11:
                    // On recupere le mois d'octobre
                    $mois = $budgetAnnuelRepository->findByMonthOctober($year, $jsonRecu['pays1'],
                        $jsonRecu['pays2'], $jsonRecu['pays3'], $jsonRecu['pays4']);
                    break;
                case 12:
                    // On recupere le mois de novembre
                    $mois = $budgetAnnuelRepository->findByMonthNovember($year, $jsonRecu['pays1'],
                        $jsonRecu['pays2'], $jsonRecu['pays3'], $jsonRecu['pays4']);
                    break;
                default:
                    echo "C'est fini !";
            }
            return $this->json($mois, 200, [], []);
        } catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @Route ("/save/fiche/client", name="save_fiche_client", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * Enregistrement fiche client dans la base..................
     */
    public function saveFicheClient(Request $request, EntityManagerInterface $manager): Response {
        try {
            $jsonRecu =json_decode($request->getContent(), true);
            $ficheClient = new FicheClient();
            if (!empty($jsonRecu)) {
                $ficheClient->setActiviteContexte($jsonRecu['activiteContexte'] ?? null);
                $ficheClient->setLangueTrt($jsonRecu['langueTrt'] ?? null);
                $this->extracted($ficheClient, $jsonRecu, $manager);
            }
            $manager->flush();
            return $this->json($ficheClient, 200, [], ['groups' => ['save-fiche']]);
        } catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @Route ("/edit/fiche/client/{id}", name="edit_fiche_client", methods={"PUT"})
     * @param int $id
     * @param Request $request
     * @param FicheClientRepository $repository
     * @param EntityManagerInterface $manager
     * @return Response
     * Mise à jour fiche client.............................................
     */
    public function updateFicheClient(int $id, Request $request, FicheClientRepository $repository,
                                      EntityManagerInterface $manager): Response
    {
        try {
            $dataUpdate = $repository->find($id);
            $jsonRecu = json_decode($request->getContent(), true);
            if (!empty($jsonRecu)) {
                $dataUpdate->setLangueTrt($jsonRecu['langueTrt'] ?? null);
                $dataUpdate->setActiviteContexte($jsonRecu['activiteContexte'] ?? null);
                $this->extracted($dataUpdate, $jsonRecu, $manager);
            }
            $manager->flush();
            return $this->json($dataUpdate, 200, [], ['groups' => ['save-fiche']]);
        } catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @param FicheClient $ficheClient
     * @param $jsonRecu
     * @param EntityManagerInterface $manager
     * Utilisation de la méthode dupliqué........
     */
    public function extracted(FicheClient $ficheClient, $jsonRecu, EntityManagerInterface $manager): void
    {
        $ficheClient->setBudgetAnnuel($jsonRecu['budgetAnnuel'] ?? null);
        $ficheClient->setBudgetDeMiseEnPlace($jsonRecu['budgetDeMiseEnPlace'] ?? null);
        $ficheClient->setBudgetFormation($jsonRecu['budgetFormation'] ?? null);
        $ficheClient->setBudgetM1($jsonRecu['budgetM1'] ?? null);
        $ficheClient->setBudgetMoyenMensuel($jsonRecu['budgetMoyenMensuel'] ?? null);
        $ficheClient->setCaM1($jsonRecu['caM1'] ?? null);
        $ficheClient->setChiffreAffaireRealise($jsonRecu['chiffreAffaireRealise'] ?? null);
        $ficheClient->setConfSpecifique($jsonRecu['confSpecifique'] ?? null);
        $ficheClient->setDateDemarragePartenariat(isset($jsonRecu['dateDemarragePartenariat']) ? \DateTime::createFromFormat('Y-m-d', $jsonRecu['dateDemarragePartenariat']) : null);
        $ficheClient->setDimensionnement($jsonRecu['dimensionnement'] ?? null);
        $ficheClient->setForfaitPilotage($jsonRecu['forfaitPilotage'] ?? null);
        $ficheClient->setFormation($jsonRecu['formation'] ?? null);
        $ficheClient->setGestionnaireDeCompte($jsonRecu['gestionnaireDeCompte'] ?? null);
        $ficheClient->setMoyenneQualite($jsonRecu['moyenneQualite'] ?? null);
        $ficheClient->setMoyenneSatcli($jsonRecu['moyenneSatcli'] ?? null);
        $ficheClient->setNiveau($jsonRecu['niveau'] ?? null);
        $ficheClient->setRapportActivite($jsonRecu['rapportActivite'] ?? null);
        $ficheClient->setRc($jsonRecu['rc'] ?? null);
        $ficheClient->setSpecificiteContractuelles($jsonRecu['specificiteContractuelles'] ?? null);
        $ficheClient->setTaciteReconduction($jsonRecu['taciteReconduction'] ?? null);
        $ficheClient->setStatutContrat($jsonRecu['statutContrat'] ?? null);
        $ficheClient->setTypeProfil($jsonRecu['typeProfil'] ?? null);
        $ficheClient->setVersementAcompte($jsonRecu['versementAcompte'] ?? null);
        $ficheClient->setDureeAnciennete($jsonRecu['dureeAnciennete'] ?? null);
        $ficheClient->setRegistreTraitement($jsonRecu['registreTraitement'] ?? null);
        $ficheClient->setAnnexeContrat($jsonRecu['annexeContrat'] ?? null);
        $ficheClient->setOutils($jsonRecu['outils'] ?? null);
        $ficheClient->setCommercial($jsonRecu['commercial'] ?? null);
        $ficheClient->setCustomer($this->getDoctrine()->getRepository(Customer::class)->find($jsonRecu['customer']));
        // $ficheClient->setNaturePrestation(isset($jsonRecu['naturePrestation']) ? $this->getDoctrine()->getRepository(NaturePrestation::class)->find($jsonRecu['naturePrestation']) : null);
        $manager->persist($ficheClient);
    }
}
