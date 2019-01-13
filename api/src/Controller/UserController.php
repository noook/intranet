<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="users-list", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_TEACHER')")
     */
    public function index(UserRepository $userRepository)
    {
        $users = [];

        foreach($userRepository->findBy([], ['email' => 'ASC']) as $user) {
            $users[] = [
                'id' => $user->getId(),
                'first-name' => $user->getFirstName(),
                'last-name' => $user->getLastName(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ];
        }

        return $this->json([
            'users' => $users,
            'roles' => $this->getParameter('available_roles'),
        ]);
    }

    /**
     * @Route("/users/roles", name="users-role-edit", methods={"PUT"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function editRole(Request $request, UserRepository $userRepository)
    {
        $data = json_decode($request->getContent(), true)['data'];
        $userRepository->updateRoles($data);

        return $this->json([], 204);
    }
}
