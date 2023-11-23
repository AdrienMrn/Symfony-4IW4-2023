<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        $pwd = 'test';

        $users =  [
            [
                'email' => 'user@user.fr',
                'role' => ['ROLE_USER']
            ],
            [
                'email' => 'admin@user.fr',
                'role' => ['ROLE_ADMIN']
            ]
        ];

        foreach ($users as $user) {
            $user = (new User())
                ->setEmail($user['email'])
                ->setRoles($user['role'])
            ;
            $user->setPassword($this->passwordHasher->hashPassword($user, $pwd));
            $manager->persist($user);
        }

        for ($i = 0; $i < 10; $i++) {
            $user = (new User())
                ->setEmail($faker->email)
                ->setRoles([])
            ;
            $user->setPassword($this->passwordHasher->hashPassword($user, $pwd));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
