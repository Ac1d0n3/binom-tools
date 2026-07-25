<?php

namespace App\Models\BnTools;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class BnCalendarHoliday extends Model
{
    protected $table = 'bn_calendar_holidays';

    protected $fillable = [
        'source_id',
        'name',
        'date',
        'starts_at',
        'ends_at',
        'country',
        'region',
        'type',
        'all_day',
        'imported_uid',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'all_day' => 'boolean',
            'metadata' => 'array',
        ];
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(BnCalendarHolidaySource::class, 'source_id');
    }

    public function scopeInRange(Builder $query, $from, $to): Builder
    {
        return $query->whereBetween('date', [$from, $to]);
    }
}
