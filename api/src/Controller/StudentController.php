<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class StudentController extends AbstractController
{
    /**
     * @Route("/student/grades", name="student-grades-list", methods={"GET"})
     * IsGranted("ROLE_STUDENT")
     */
    public function index()
    {
        $user = $this->getUser();
    
        $grades = [];

        foreach($user->getGrades() as $grade) {
            $grades[] = $grade->repr();
        }

        return $this->json([
            'grades' => $grades
        ]);
    }
}
