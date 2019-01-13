<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Service\TokenHandler;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    private $tokenHandler;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, TokenHandler $tokenHandler)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenHandler = $tokenHandler;
    }

    public function load(ObjectManager $manager)
    {
        $student = new User();
        
        $student
            ->setEmail('benjamin.courtine@supinternet.fr')
            ->setFirstName('Benjamin')
            ->setLastName('Courtine')
            ->setPassword('$argon2i$v=19$m=1024,t=2,p=2$SnNoZ0NVZHhMRjY3Zmx5cg$3vNa7WFPl01SBNZaMS8fTDqRIYeUvppPyptdlZ+ympA')
            ->setRoles(['ROLE_STUDENT']);
        
        $manager->persist($student);

        $student = new User();
        
        $student
            ->setEmail('neil.richter@supinternet.fr')
            ->setFirstName('Neil')
            ->setLastName('Richter')
            ->setPassword('$argon2i$v=19$m=1024,t=2,p=2$RloySEVXZWhVTlNJV3Nsag$aL96OegeUeOuBgRViveacyKWMwT+INafQEwdWTlATsQ')
            ->setRoles(['ROLE_STUDENT']);
        
        $manager->persist($student);

        $admin = new User();

        $admin
            ->setEmail('john.bob@supinternet.fr')
            ->setFirstName('John')
            ->setLastName('Bob')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordEncoder->encodePassword($admin, 'admin'));

        $manager->persist($admin);

        $manager->flush();
    }
}
