<?php

namespace App\Http\Controllers\Api; // pastikan ini sesuai folder: app/Http/Controllers/Api

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FlipController extends Controller
{
    public function payoutCallback(Request $request)
    {
        Log::info('FlipController::payoutCallback called', $request->all());
        return response()->json(['status' => 'ok']);
    }
}
