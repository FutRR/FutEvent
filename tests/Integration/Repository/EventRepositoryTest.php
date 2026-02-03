<?php

namespace App\Tests\Integration\Repository;

use App\Entity\Event;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EventRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $this->em = static::getContainer()
            ->get('doctrine')
            ->getManager();

        $this->em->beginTransaction();
    }

    public function testSearchByKeywordMatchesTitle(): void
    {
        $repo = $this->em->getRepository(Event::class);

        $category = new Category();
        $category->setName('Music');

        $user = new User();
        $user->setUsername('test_user_' . uniqid());
        $user->setEmail($user->getUsername() . '@test.com');

        $event = new Event();
        $event->setTitle('Live Music Night');
        $event->setDatetimeStart(new \DateTime('+1 day'));
        $event->setDatetimeEnd(new \DateTime('+2 days'));
        $event->setCategory($category);
        $event->setCreator($user);

        $this->em->persist($category);
        $this->em->persist($user);
        $this->em->persist($event);
        $this->em->flush();

        $results = $repo->searchByKeyword('Music');

        $this->assertCount(1, $results);
        $this->assertSame($event, $results[0]);
    }

    protected function tearDown(): void
    {
        if ($this->em->getConnection()->isTransactionActive()) {
            $this->em->rollback();
        }

        parent::tearDown();
    }
}
