<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Repository\UserRepository;
use App\Repository\CourseRepository;
use App\Entity\Grade;

class GradeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(UserRepository $userRepository, CourseRepository $courseRepository)
    {
        $this->userRepository = $userRepository;
        $this->courseRepository = $courseRepository;
    }

    public function load(ObjectManager $manager)
    {
        $course = $this->courseRepository->findOneBy(['name' => 'AngularJS']);
        $students = $this->userRepository->findBy([
            'email' => [
                'neil.richter@supinternet.fr',
                'benjamin.courtine@supinternet.fr'
            ],
        ]);

        $grade = new Grade;
        $grade
            ->setCourse($course)
            ->setStudent($students[0])
            ->setValue(21.5)
            ->setComment('Nice ! Could do better.');

        $manager->persist($grade);

        $grade = new Grade;
        $grade
            ->setCourse($course)
            ->setStudent($students[1])
            ->setValue(3)
            ->setComment('Nul Germain, nul !');

        $manager->persist($grade);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CourseFixtures::class,
        ];
    }
}
