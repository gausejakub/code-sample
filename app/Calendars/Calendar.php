<?php

declare(strict_types = 1);

namespace App\Calendars;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{

    protected string $table = 'calendars';

    /**
     * @var array<string>
     */
    protected array $fillable = [
        'name',
        'description',
        'user_id',
        'integration_id',
        'settings',
        'enabled_at',
    ];

    public function tokens(): HasMany
    {
        return $this->hasMany(CalendarToken::class);
    }

}
