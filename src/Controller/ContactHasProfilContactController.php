<?php

namespace App\Controller;

use App\Entity\ContactHasProfilContact;
use App\Entity\Contact;
use App\Entity\ProfilContact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ContactHasProfilContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @Route("/api")
 */
class ContactHasProfilContactController extends AbstractController
{
    /**
     * @Route("/contact/has/profil/contact", name="contact_has_profil_contact", methods={"GET"})
     */
    public function findAll(ContactHasProfilContactRepository $repo): Response
    {
        try {
            return $this->json($repo->findAll(), 200, [], ['groups' => ['has']]);
        } catch (\Exception $e) {
            return $this->json([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/save-contact-has", name="has_profil_contact", methods={"POST"})
     */
    public function save(Request $request, EntityManagerInterface $em, 
    SerializerInterface $serializer): Response 
    {
        try {
            $jsonRecu = $request->getContent();
            $hasProfil = $serializer->deserialize($jsonRecu, ContactHasProfilContact::class, 'json');

            $dataDecode = json_decode($request->getContent(), true);
            $cont = $this->getDoctrine()->getRepository(Contact::class)->findOneById($dataDecode['contact']);
            $profilContact = $this->getDoctrine()->getRepository(ProfilContact::class)->findOneById($dataDecode['profilContact']);

            $hasProfil->setContact($cont);
            $hasProfil->setProfilContact($profilContact);
            $em->persist($hasProfil);
            $em->flush();

            return $this->json($hasProfil, 201, [], ['groups' => ['has']]);

        } catch (\Exception $e) {
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }
}
