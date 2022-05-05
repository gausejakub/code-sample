<?php

declare(strict_types = 1);

namespace App\Calendars;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CalendarToken extends Model
{

    protected string $table = 'calendar_tokens';

    /**
     * @var array<string>
     */
    protected array $fillable = [
        'token',
        'user_id',
        'calendar_id',
        'hostnames',
    ];

    /**
     * @param array<mixed> $attributes
     */
    public static function create(array $attributes = []): self
    {
        $token = Str::random(32);
        $attributes = array_merge($attributes, ['token' => $token]);

        /** @var self $calendarToken */
        $calendarToken = self::query()->create($attributes);

        return $calendarToken;
    }

    public function calendar(): BelongsTo
    {
        return $this->belongsTo(Calendar::class);
    }

    /**
     * @return array<string>
     */
    public function getHostnamesAttribute(string $value): array
    {
        return explode(',', $value);
    }

    /**
     * @param array<string> $value
     */
    public function setHostnamesAttribute(array $value): void
    {
        $this->attributes['hostnames'] = implode(',', $value);
    }

}
