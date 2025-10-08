<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create a new category named 'Music'
        $category = new Category();
        $category->setName('Music');
        $manager->persist($category);

        // Create 10 events and associate them with the category
        for ($i = 1; $i <= 10; $i++) {
            $event = new \App\Entity\Event();
            $event->setTitle('Event ' . $i);
            $event->setDescription('Description for event ' . $i);
            $event->setImage('image' . $i . '.jpg');
            $event->setDatetimeStart(new \DateTime('2024-07-' . str_pad($i, 2, '0', STR_PAD_LEFT) . ' 18:00:00'));
            $event->setDatetimeEnd(new \DateTime('2024-07-' . str_pad($i, 2, '0', STR_PAD_LEFT) . ' 21:00:00'));
            $event->setCategory($category);
            $manager->persist($event);
        }

        // Save all changes to the database
        $manager->flush();
    }
}
