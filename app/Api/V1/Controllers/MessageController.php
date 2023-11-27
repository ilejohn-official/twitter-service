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
     * @OA\Post(
     *     path="/api/v1/messages/send",
     *     summary="Send message to subscribers",
     *     description="Send a message to a channel using the specified parameters.",
     *     operationId="sendMessage",
     *     tags={"Communication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     description="The message to be sent.",
     *                     example="Hello, World!"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="user-id",
     *         in="header",
     *         required=true,
     *         description="The user ID.",
     *         example="12345",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 description="Indicates whether the message was sent successfully.",
     *                 example=true
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Invalid User Id.",
     *                 example="The User id must be one of the four specified in the document"
     *             ),
     *         ),
     *     )
     * )
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function send(Request $request)
    {
        $channelId = config('twitter.bot_id');

        if(empty($channelId)) {
            return response()->json(['success' => false, 'error' => 'Set bot id in env : TWITTER_BOT_ID']);
        }

        $this->validate($request, [
            'message' => 'required|string'
        ]);

        $result = $this->channelProvider->sendMessage($request->message, $channelId);

        return response()->json($result, $result['success'] == true ? 200 : 400);
    }
}
