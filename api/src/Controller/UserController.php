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
            $users[] = $user->repr();
        }

        return $this->json([
            'users' => $users,
            'roles' => $this->getParameter('available_roles'),
        ]);
    }

    /**
     * @Route("/users/teachers", name="teachers-list", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function getTeachers(UserRepository $userRepository)
    {
        $teachers = [];

        foreach ($userRepository->findTeachers() as $teacher) {
            $teachers[] = array_merge($teacher->repr(), ['classes' => count($teacher->getClasses())]);
        }

        return $this->json([
            'teachers' => $teachers,
        ]);
    }

    /**
     * @Route("/users/students", name="students-list", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function getStudents(UserRepository $userRepository)
    {
        $students = [];

        foreach ($userRepository->findStudents() as $student) {
            $students[] = array_merge($student->repr(), ['courses' => count($student->getCourses())]);
        }

        return $this->json([
            'students' => $students,
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
