<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use App\Repository\UserRepository;

use App\DataFixtures\UserFixtures;

use App\Entity\Course;

class CourseFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(UserRepository $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager)
    {
        $teacher = $this->userRepository->findOneBy(['email' => 'cyril.teixeira@supinternet.fr']);
        $students = $this->userRepository->findBy([
            'email' => [
                'neil.richter@supinternet.fr',
                'benjamin.courtine@supinternet.fr'
            ],
        ]);

        $course = new Course;
        $course
            ->setName('AngularJS')
            ->setTeacher($teacher);

        foreach($students as $student) {
            $course->addParticipant($student);
        }

        $manager->persist($course);

        $teacher = $this->userRepository->findOneBy(['email' => 'clement.seiller@supinternet.fr']);
        $course = new Course;
        $course
            ->setName('Intégration')
            ->setTeacher($teacher);

        // foreach($students as $student) {
        //     $course->addParticipant($student);
        // }

        $manager->persist($course);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
