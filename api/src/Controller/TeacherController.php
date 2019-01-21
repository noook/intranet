<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\Entity\User;
use App\Entity\Course;

class TeacherController extends AbstractController
{
    /**
     * @Route("/teacher/courses", name="teacher-courses", methods={"GET"})
     * @isGranted("ROLE_TEACHER")
     */
    public function teacherCourseDetail()
    {
        $teacher = $this->getUser();
        $classes = [];

        foreach ($teacher->getClasses() as $class) {
            $classes[] = $class->repr();
        }

        return $this->json([
            'courses' => $classes,
        ]);
    }

    /**
     * @Route("/teacher/courses/{id}", name="teacher-course-detail", methods={"GET"})
     * @ParamConverter("course", class="App\Entity\Course")
     * @isGranted("ROLE_TEACHER")
     */
    public function teacherCourses(Course $course)
    {
        $students = [];
        $grades = [];

        foreach ($course->getGrades() as $grade) {
            $grades[] = $grade->repr();
        }

        foreach ($course->getParticipants() as $participant) {
            $students[] = $participant->repr();
        }

        return $this->json([
            'students' => $students,
            'grades' => $grades,
            'course' => $course->repr(),
        ]);
    }

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
