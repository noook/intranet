<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\Entity\Course;
use App\Entity\Grade;

use App\Repository\CourseRepository;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
     * @Route("/students/courses/participating/{id}", name="student-course-edit", methods={"PUT"})
     * @ParamConverter("course", class="App\Entity\Course")
     * @IsGranted("ROLE_STUDENT")
     */
    public function updateStudentParticipating(Course $course, ObjectManager $em)
    {
        $user = $this->getUser();

        if ($user->getCourses()->contains($course)) {
            $user->removeCourse($course);
        } else {
            $user->addCourse($course);
        }
        $em->flush();

        return $this->json([
            'enrolled' => $user->getCourses()->contains($course),
            'updated' => $course->repr(),
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

    /**
     * @Route("/courses/{id}", name="course-detail", methods={"GET"})
     * @ParamConverter("course", class="App\Entity\Course")
     * @IsGranted("ROLE_TEACHER")
     */
    public function courseDetail(Course $course)
    {
        $user = $this->getUser();
        if (in_array('ROLE_TEACHER', $user->getRoles())) {
            if (!$user->getClasses()->contains($course)) {
                throw new AccessDeniedHttpException;
            }
        }
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
     * @Route("/courses/{id}/assign", name="course-assign-teacher", methods={"PUT"})
     * @ParamConverter("course", class="App\Entity\Course")
     * @IsGranted("ROLE_ADMIN")
     */
    public function assignTeacher(Course $course, Request $request, UserRepository $userRepository, ObjectManager $em)
    {
        $teacherId = json_decode($request->getContent(), true)['teacher'];
        $teacher = $userRepository->find($teacherId);
    
        $course->setTeacher($teacher);

        $em->flush();
    
        return $this->json([
            'course' => $course->repr(),
        ]);
    }

    /**
     * @Route("/courses/{id}/grade", name="course-new-grade", methods={"POST"})
     * @ParamConverter("course", class="App\Entity\Course")
     * @IsGranted("ROLE_TEACHER")
     */
    public function newGrade(Course $course, Request $request, UserRepository $userRepository, ObjectManager $em)
    {
        $user = $this->getUser();
        if (in_array('ROLE_TEACHER', $user->getRoles())) {
            if (!$user->getClasses()->contains($course)) {
                throw new AccessDeniedHttpException;
            }
        }
        
        $data = json_decode($request->getContent(), true)['grade'];
        $student = $userRepository->find($data['student']);

        $grade = new Grade;
        $grade
            ->setStudent($student)
            ->setCourse($course)
            ->setValue($data['value'])
            ->setComment($data['comment']);
        
        $em->persist($grade);
        $em->flush();
    
        return $this->json([
            'grade' => $grade->repr(),
        ]);
    }
}
