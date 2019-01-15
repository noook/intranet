<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\Entity\User;

class TeacherController extends AbstractController
{
    /**
     * @Route("/teachers/{id}", name="teacher-detail", methods={"GET"})
     * @ParamConverter("teacher", class="App\Entity\User")
     * @isGranted("ROLE_ADMIN")
     */
    public function teacherDetail(User $teacher)
    {
        $classes = [];

        foreach ($teacher->getClasses() as $class) {
            $classes[] = $class->repr();
        }

        return $this->json([
            'teacher' => $teacher->repr(),
            'classes' => $classes,
        ]);
    }
}
