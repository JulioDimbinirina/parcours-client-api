<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/user", name="user", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */

    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepository): Response
    {
        $user = new User();
        $data = json_decode($request->getContent(), true);

        $id = 2;

        $us = $userRepository->find($id);

        if (!empty($data)) {
            $user->setUsername($data['username']);
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $data['password']
            ));
            if(!empty($data['parent']))
            {
                // $user->setParent($data['parent']);

                $user->setParent($us);
            }

            $em->persist($user);
            $em->flush();
        }
        return $this->json($user, 201, []);
    }

    /**
     * @Route("/api/get/info/user", name="get_info_user", methods={"GET"})
     * @return Response
     * Get information of current user
     */
    public function getCurrentUser(UserInterface $user): Response {
        try {
            return $this->json($user->getRoles(), 200, [], ['groups' => ['current-user']]);
        } catch (\Exception $exception) {
            return $this->json(["status" => 500, "message" => $exception->getMessage()], 500);
        }
    }

    /**
     * @Route("/api/get/username", name="get_username", methods={"GET"})
     * @param UserInterface $user
     * @return Response
     * Get nom de l'user connecté....................
     */
    public function getCurrentUsername(UserInterface $user): Response {
        try {
            return $this->json(["userConnecte" => $user->getCurrentUsername()], 200, [], ['groups' => ['current-user']]);
        } catch (\Exception $exception) {
            return $this->json([
                "status" => 500,
                "message" => $exception->getMessage()
            ], 500);
        }
    }
}
