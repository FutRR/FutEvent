<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Event;
use App\Entity\EventRequest;
use App\Entity\User;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class EventTest extends TestCase
{

    public function testHasStartedReturnsFalseForFutureEvent(): void
    {
        $event = new Event();
        $event->setDatetimeStart(new \DateTime('+1 day'));

        $this->assertFalse($event->hasStarted());
    }

    public function testHasStartedReturnsTrueForPastEvent(): void
    {
        $event = new Event();
        $event->setDatetimeStart(new \DateTime('-1 day'));

        $this->assertTrue($event->hasStarted());
    }

    public function testAddUserDoesNotDuplicate(): void
    {
        $event = new Event();
        $user = $this->createMock(User::class);

        $event->addUser($user);
        $event->addUser($user);

        $this->assertCount(1, $event->getUsers());
    }

    public function testAddEventRequestSetsOwningSide(): void
    {
        $event = new Event();
        $eventRequest = new EventRequest();

        $event->addEventRequest($eventRequest);

        $this->assertCount(1, $event->getEventRequests());
        $this->assertSame($event, $eventRequest->getEvent());
    }


}
