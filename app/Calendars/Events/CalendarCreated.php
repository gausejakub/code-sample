<?php

declare(strict_types = 1);

namespace App\Calendars\Events;

use App\Calendars\Calendar;

class CalendarCreated
{

    public function __construct(private Calendar $calendar) {}

}
