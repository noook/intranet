<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\Entity\Course;

use App\Repository\CourseRepository;
use Doctrine\Common\Persistence\ObjectManager;

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

    /**
     * @Route("/students/courses", name="student-courses", methods={"GET"})
     * @IsGranted("ROLE_STUDENT")
     */
    public function studentsCourses(CourseRepository $courseRepository)
    {
        $user = $this->getUser();
        $courses = [];

        foreach ($courseRepository->findBy([], ['name' => 'ASC']) as $course) {
            $courses[] = array_merge(
                $course->repr(),
                ['assigned' => $course->getParticipants()->contains($user)]
            );
        }

        return $this->json([
            'courses' => $courses,
        ]);
    }

    /**
     * @Route("/courses/{id}", name="course-detail", methods={"GET"})
     * @ParamConverter("course", class="App\Entity\Course")
     * @IsGranted("ROLE_STUDENT")
     */
    public function courseDetail(Course $course)
    {
        $participating = [];
        $grades = [];

        foreach ($course->getParticipants() as $participant) {
            $participating[] = $participant->repr();
        }

        foreach ($course->getGrades() as $grade) {
            $grades[] = $grade->repr();
        }

        return $this->json([
            'course' => $course->repr(),
            'participating' => $participating,
            'grades' => $grades,
        ]);

    }
    
    /**
     * @Route("/courses/new", name="new-course", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function newCourse(Request $request, ObjectManager $em)
    {
        $name = json_decode($request->getContent(), true)['name'];
        $course = new Course;
        $course
            ->setName($name);
        
        $em->persist($course);
        $em->flush();

        return $this->json([
            'course' => [
                'id' => $course->getId(),
                'name' => $course->getName(),
            ],
        ], 201);
    }
}
