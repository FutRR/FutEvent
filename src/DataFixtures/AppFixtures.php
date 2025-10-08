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

        $imageDir = 'public/img/placeholder/';
        $images = glob($imageDir . '*.webp');

        // Create 5 events with different images
        foreach ($images as $imagePath) {
            $event = new \App\Entity\Event();
            $event->setTitle('Event' . uniqid());
            $event->setDescription("This event is a unique opportunity to discover new musical talents and enjoy a festive atmosphere. Come and experience a variety of performances and meet other music enthusiasts.
            The evening will take place in a friendly setting, with entertainment and surprises throughout the event. Don't forget to invite your friends to share this exceptional moment!");
            $event->setImage('/img/placeholder/' . basename($imagePath)); // URL path
            $event->setDatetimeStart(new \DateTime('2024-07-01 18:00:00'));
            $event->setDatetimeEnd(new \DateTime('2024-07-01 21:00:00'));
            $event->setCategory($category);
            $manager->persist($event);
        }

        // Save all changes to the database
        $manager->flush();
    }
}
