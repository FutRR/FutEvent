<?php

namespace App\Enum;

enum EventRequestStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case DENIED = 'denied';
}
