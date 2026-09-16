<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('measurements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('measurement_variable_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('measured_at');
            $table->date('measured_date');
            $table->string('shift', 20)->nullable();
            $table->decimal('value', 12, 4);
            $table->text('observation')->nullable();
            $table->string('status')->default('recorded');
            $table->timestamps();

            $table->index(['measured_date', 'shift']);
            $table->index(['measurement_variable_id', 'measured_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('measurements');
    }
};
