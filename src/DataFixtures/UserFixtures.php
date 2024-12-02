<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Admin User
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setFirstName('Admin');
        $admin->setLastName('User');
        $admin->setPosition('Administrator');
        $admin->setBirthDate(new \DateTime('1990-01-01'));
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setCreatedAt(new \DateTime());
        $manager->persist($admin);

        // Applicant User
        $applicant = new User();
        $applicant->setEmail('applicant@example.com');
        $applicant->setFirstName('Applicant');
        $applicant->setLastName('User');
        $applicant->setPosition('Developer');
        $applicant->setBirthDate(new \DateTime('1995-01-01'));
        $applicant->setRoles(['ROLE_APPLICANT']);
        $applicant->setPassword($this->passwordHasher->hashPassword($applicant, 'applicant123'));
        $applicant->setCreatedAt(new \DateTime());
        $manager->persist($applicant);

        $manager->flush();
    }
}
