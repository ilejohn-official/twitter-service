<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param Request $request
     * 
     * Receive responses from messenger API
     */
    public function handleResponse(Request $request)
    {
        Log::info($request->getContent());

        return response()->json([
            'message' => 'webhook handled'
        ]);
    }
}
