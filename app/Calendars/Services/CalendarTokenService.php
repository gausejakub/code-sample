<?php

declare(strict_types = 1);

namespace App\Calendars\Services;

use App\Calendars\Calendar;
use App\Calendars\CalendarToken;

class CalendarTokenService
{

    /**
     * @param array<string> $hostnames
     */
    public function createToken(Calendar $calendar, array $hostnames = []): CalendarToken
    {
        return CalendarToken::create([
            'calendar_id' => $calendar->id,
            'hostnames' => $hostnames,
        ]);
    }

}
