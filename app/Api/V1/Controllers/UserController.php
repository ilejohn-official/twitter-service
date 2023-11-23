<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
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
     * Subscribe users to a chat bot
     */
    public function subscribeToChatBot(Request $request)
    {
        $channelId = config('twitter.bot_id');

        if(empty($channelId)) {
            return response()->json(['success' => false, 'error' => 'Set bot id in env : TWITTER_BOT_ID']);
        }

        $result = $this->channelProvider->subscribeToList($channelId,  $request->header('user-id'));

        return response()->json($result);
    }

    /**
     * @param Request $request
     * 
     * Subscribe users to a chat or a channel
     */
    public function subscribeToChat(Request $request)
    {
        $this->validate($request, [
            'channelId' => 'required'
        ]);

        $result = $this->channelProvider->subscribeToList($request->channelId,  $request->header('user-id'));

        return response()->json($result);
    }
}
