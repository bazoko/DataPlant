<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'measurement_variable_id',
    'user_id',
    'measured_at',
    'measured_date',
    'shift',
    'value',
    'observation',
    'status',
])]
class Measurement extends Model
{
    protected function casts(): array
    {
        return [
            'measured_at' => 'datetime',
            'measured_date' => 'date',
            'value' => 'decimal:4',
        ];
    }

    public function variable(): BelongsTo
    {
        return $this->belongsTo(MeasurementVariable::class, 'measurement_variable_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
