<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class BnCalendarHolidaySource extends Model
{
    protected $table = 'bn_calendar_holiday_sources';

    protected $fillable = [
        'name',
        'type',
        'country',
        'region',
        'url',
        'is_active',
        'sync_interval_hours',
        'last_synced_at',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_synced_at' => 'datetime',
            'settings' => 'array',
        ];
    }

    public function holidays(): HasMany
    {
        return $this->hasMany(BnCalendarHoliday::class, 'source_id');
    }
}
