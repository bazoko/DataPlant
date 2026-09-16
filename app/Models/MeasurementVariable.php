<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'area_id',
    'measurement_frequency_id',
    'name',
    'slug',
    'unit',
    'description',
    'min_value',
    'max_value',
    'is_active',
])]
class MeasurementVariable extends Model
{
    protected function casts(): array
    {
        return [
            'min_value' => 'decimal:4',
            'max_value' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function frequency(): BelongsTo
    {
        return $this->belongsTo(MeasurementFrequency::class, 'measurement_frequency_id');
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class);
    }
}
