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
     *    @OA\Post(
     *      path="/api/v1/chatbot/subscribe",
     *      summary="Subscribe users to a chat bot",
     *      description="Subscribe users to a chat bot using the specified parameters.",
     *      tags={"Subscriptions"},
     *      @OA\Parameter(
     *          name="user-id",
     *          in="header",
     *          required=true,
     *          description="The user ID.",
     *          example="12345",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  description="Indicates whether the user was successfully subscribed to the chatbot",
     *                  example=true
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  description="Invalid User Id.",
     *                  example="The User id must be one of the four specified in the document"
     *              ),
     *          ),
     *       )
     *    ) 
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function subscribeToChatBot(Request $request)
    {
        $channelId = config('twitter.bot_id');

        if(empty($channelId)) {
            return response()->json(['success' => false, 'error' => 'Set bot id in env : TWITTER_BOT_ID']);
        }

        $result = $this->channelProvider->subscribeToList($channelId,  $request->header('user-id'));

        return response()->json($result, $result['success'] == true ? 200 : 400);
    }

    /**
     *    @OA\Post(
     *     path="/api/v1/chat/subscribe",
     *     summary="Subscribe users to a chat or a channel",
     *     description="Subscribe users to a chat or a channel using the specified parameters.",
     *     tags={"Subscriptions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="channelId",
     *                     type="string",
     *                     description="The ID of the channel to subscribe the user to.",
     *                     example="channel123"
     *                 )
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
     *                 description="Indicates whether the user was successfully subscribed to the chat or channel",
     *                 example=true
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
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
     *    )
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function subscribeToChat(Request $request)
    {
        $this->validate($request, [
            'channelId' => 'required'
        ]);

        $result = $this->channelProvider->subscribeToList($request->channelId,  $request->header('user-id'));

        return response()->json($result, $result['success'] == true ? 200 : 400);
    }
}
