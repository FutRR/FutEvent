<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Create a new category named 'Music'
        $category = new Category();
        $category->setName('Music');
        $manager->persist($category);

        // Create admin user
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@example.com');
        $admin->setPassword('$2y$13$BmYmQITacL0NKzFP.HyWy.ioPpjHwepabuFr1a7oY7yLRoEHqQmPW'); // password: admin123
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        // Create 5 events with different images
        for($i = 0; $i < 5; $i++) {
            $event = new \App\Entity\Event();
            $event->setTitle('Event' . uniqid());
            $event->setDescription($faker->paragraphs(2, true));
            $event->setDatetimeStart(new \DateTime('2024-07-01 18:00:00'));
            $event->setDatetimeEnd(new \DateTime('2024-07-01 21:00:00'));
            $event->setCategory($category);
            $event->setCreator($admin);
            $manager->persist($event);
        }



        // Save all changes to the database
        $manager->flush();
    }
}
