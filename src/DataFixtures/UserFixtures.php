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
                'role' => ['ROLE_USER'],
                'reference' => 'user'
            ],
            [
                'email' => 'admin@user.fr',
                'role' => ['ROLE_ADMIN'],
                'reference' => 'admin'
            ],
            [
                'email' => 'coordinator@user.fr',
                'role' => ['ROLE_COORDINATOR'],
                'reference' => 'coordinator'
            ]
        ];

        foreach ($users as $user) {
            $object = (new User())
                ->setEmail($user['email'])
                ->setRoles($user['role'])
            ;
            $object->setPassword($this->passwordHasher->hashPassword($object, $pwd));
            $manager->persist($object);
            $this->addReference($user['reference'], $object);
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
