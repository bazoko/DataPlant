<?php

use App\Models\Area;
use App\Models\Measurement;
use App\Models\MeasurementVariable;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'areaCount' => Area::count(),
        'variableCount' => MeasurementVariable::count(),
        'measurementCount' => Measurement::count(),
        'latestMeasurements' => Measurement::with(['variable.area', 'user'])
            ->latest('measured_at')
            ->limit(8)
            ->get(),
    ]);
});
