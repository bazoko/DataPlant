<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'code', 'description', 'requires_shift', 'expected_per_day'])]
class MeasurementFrequency extends Model
{
    protected function casts(): array
    {
        return [
            'requires_shift' => 'boolean',
            'expected_per_day' => 'integer',
        ];
    }

    public function variables(): HasMany
    {
        return $this->hasMany(MeasurementVariable::class);
    }
}
