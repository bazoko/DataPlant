<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

#[Fillable(['user_id', 'modulo', 'accion', 'descripcion'])]
class Actividad extends Model
{
    protected $table = 'actividades';

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function registrar(string $modulo, string $accion, string $descripcion): void
    {
        self::create([
            'user_id' => Auth::id(),
            'modulo' => $modulo,
            'accion' => $accion,
            'descripcion' => $descripcion,
        ]);
    }
}
