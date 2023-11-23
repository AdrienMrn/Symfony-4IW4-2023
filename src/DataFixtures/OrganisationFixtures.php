<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Organisation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrganisationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $categories = $manager->getRepository(Category::class)->findAll();

        for ($i = 0; $i < 10; $i++) {
            $organisation = (new Organisation())
                ->setName($faker->company)
                ->setDescription($faker->paragraph(5))
                ->setCreatedAt(new \DateTimeImmutable())
                ->setCategory($categories[array_rand($categories)])
            ;
            $manager->persist($organisation);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
