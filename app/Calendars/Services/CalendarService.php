<?php

declare(strict_types = 1);

namespace App\Calendars\Services;

use App\Calendars\Calendar;
use App\Calendars\Events\CalendarCreated;
use App\Calendars\Exceptions\CalendarCreationFailed;
use App\Events\Services\EventService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class CalendarService
{

    public function __construct(private CalendarTokenService $calendarTokenService) {}

    /**
     * @param array<string|bool|int> $integrationCalendar
     * @throws \App\Calendars\Exceptions\CalendarCreationFailed
     */
    public function create(int $userId, string $name, ?int $integrationId, ?array $integrationCalendar, ?Carbon $enableAt, ?string $description): Calendar
    {
        try {
            DB::beginTransaction();
            /** @var \App\ZapTime\Calendars\Calendar $calendar */
            $calendar = Calendar::create([
                'uuid' => Str::uuid()->toString(),
                'user_id' => $userId,
                'integration_id' => $integrationId,
                'name' => $name,
                'description' => $description,
                'enabled_at' => $enableAt ?? Carbon::now(),
                'settings' => $integrationCalendar ? json_encode($integrationCalendar) : json_encode([]),
            ]);

            $this->calendarTokenService->createToken($calendar, []);
            event(new CalendarCreated($calendar));

            DB::commit();

            return $calendar;
        } catch (Throwable $throwable) {
            DB::rollBack();

            throw new CalendarCreationFailed($throwable->getMessage());
        }
    }

}
