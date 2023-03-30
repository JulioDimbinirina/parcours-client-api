<?php

namespace App\Controller;

use App\Entity\ProfilContact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProfilContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @Route("/api")
 */
class ProfilContactController extends AbstractController
{
    /**
     * @Route("/profil/contact", name="profil_contact", methods={"GET"})
     */
    public function findAll(ProfilContactRepository $repo): Response
    {
        try {
            return $this->json($repo->findAll(), 200, [], ['groups' =>['profil-contact']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/save-profil-contact", name="ajout_profil_contact", methods={"POST"})
     */
    public function saveProfilContact(Request $request, EntityManagerInterface $em, 
    SerializerInterface $serializer): Response 
    {
        try {
            $jsonRecu = $request->getContent();
            $dataDecode = $serializer->deserialize($jsonRecu, ProfilContact::class, 'json');
            $em->persist($dataDecode);
            $em->flush();

            return $this->json($dataDecode, 201, [], ['groups' => ['profil-contact']]);

        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }
}
