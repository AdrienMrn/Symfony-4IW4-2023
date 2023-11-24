<?php

namespace App\DataFixtures;

use App\Entity\Proof;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProofFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        // user reference's proofs
        for ($i = 0; $i < 5; $i++) {
            $proof = (new Proof())
                ->setName('Preuve open' . $i)
                ->setDescription($faker->paragraph)
                ->setOwner($this->getReference('user'))
            ;
            $manager->persist($proof);
        }

        $users = $manager->getRepository(User::class)->findAll();
        for ($i = 0; $i < 50; $i++) {
            $proof = (new Proof())
                ->setName($faker->colorName)
                ->setDescription($faker->paragraph)
                ->setOwner($users[array_rand($users)])
            ;
            $manager->persist($proof);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
