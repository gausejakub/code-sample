<?php

declare(strict_types = 1);

namespace Tests\Integration\Calendars\Services;

use App\Calendars\Calendar;
use App\Calendars\Services\CalendarService;
use App\Calendars\Services\CalendarTokenService;
use App\Calendars\Events\CalendarCreated;
use Carbon\Carbon;
use Mockery;
use Tests\Integration\IntegrationTestCase;

/**
 * @see CalendarService
 * @group Integration
 * @group Calendars
 */
class CalendarServiceTest extends IntegrationTestCase
{

    /**
     * @see CalendarService::create()
     * @test
     */
    public function can_create_calendar(): void
    {
        $this->expectsEvents(CalendarCreated::class);
        Carbon::setTestNow();

        $calendarTokenServiceMock = Mockery::mock(CalendarTokenService::class);
        $calendarTokenServiceMock->shouldReceive('createToken')
            ->with(Mockery::on(static function (Calendar $calendar) {
                return $calendar->name === 'Fake Calendar Name';
            }), [])
            ->once();
        $this->instance(CalendarTokenService::class, $calendarTokenServiceMock);

        /** @var \App\ZapTime\Calendars\Services\CalendarService $calendarService */
        $calendarService = app(CalendarService::class);

        $calendar = $calendarService->create(
            8,
            'Fake Calendar Name',
            null,
            null,
            Carbon::now(),
            null,
        );

        $this->assertDatabaseCount('calendars', 1);
        $this->assertDatabaseHas('calendars', [
            'id' => $calendar->id,
            'name' => 'Fake Calendar Name',
            'user_id' => 8,
            'enabled_at' => Carbon::now(),
        ]);
    }

}
