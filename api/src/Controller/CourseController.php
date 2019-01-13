<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Entity\Course;

use App\Repository\CourseRepository;

class CourseController extends AbstractController
{
    /**
     * @Route("/courses", name="courses", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(CourseRepository $courseRepository)
    {
        $courses = [];

        foreach($courseRepository->findBy([], ['name' => 'ASC']) as $course) {
            $courses[] = $course->repr();
        }

        return $this->json([
            'courses' => $courses,
        ]);

    }
}
