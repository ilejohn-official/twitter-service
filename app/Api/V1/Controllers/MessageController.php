<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{

    protected $channelProvider;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->channelProvider = app('communication');
    }

    /**
     * @param Request $request
     * 
     * Send messages to subcribers
     */
    public function send(Request $request)
    {
        $this->validate($request, [
            'channelId' => 'required',
            'message' => 'required|string'
        ]);

        $result = $this->channelProvider->sendMessage($request->message, $request->channelId);

        return response()->json($result);
    }
}
