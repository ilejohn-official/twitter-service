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

        $result = $this->channelProvider->subscribeToList($channelId,  $request->user());

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

        $result = $this->channelProvider->subscribeToList($request->channelId,  $request->user());

        return response()->json($result, $result['success'] == true ? 200 : 400);
    }


    /**
     * 
     * @OA\Get(
     *     path="/api/v1/botUser",
     *     tags={"Index"},
     *     summary="Get the account details associated with the app",
     *     @OA\Response(
     *         response="200",
     *         description="Get the account details associated with the app",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 description="Indicates the operation was successful",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="result",
     *                 type="object",
     *                 description="The return object from the channel",
     *                 example= {
     *                  "id": "13696209441431539484",
     *                  "name": "Opeyemi Ilesanmi",
     *                  "username": "ilejohn"
     *                 }
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="A message indicating success",
     *                 example="User retrieved"
     *             ),
     *          ),
     *        )
     *     )
     * )
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBotUser()
    {
        $result = $this->channelProvider->botUser();

        return response()->json($result, $result['success'] == true ? 200 : 400);
    }

    /**
     * 
     * @OA\Get(
     *     path="/api/v1/me",
     *     tags={"Index"},
     *     summary="Get the user details of the auth user",
     *     @OA\Response(
     *         response="200",
     *         description="Get the user details of the auth user",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 description="Indicates the operation was successful",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="result",
     *                 type="object",
     *                 description="The return object from the channel",
     *                 example= {
     *                  "id": "13696209441431539484",
     *                  "name": "Opeyemi Ilesanmi",
     *                  "twitter_screen_name": "ilejohn"
     *                 }
     *             ),
     *          ),
     *        )
     *     )
     * )
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(Request $request)
    {
        return response()->json(['success' => true, 'result' => $request->user()], 200);
    }

    /**
     *    @OA\Post(
     *     path="/api/v1/tweet",
     *     summary="Post a tweet from the account associated with the app",
     *     description="Post a tweet from the account associated with the app using the specified parameters.",
     *     tags={"Index"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="text",
     *                     type="string",
     *                     description="The text to be tweeted",
     *                     example="A random tweet from a laravel app"
     *                 )
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 description="Indicates whether the action was successful",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="result",
     *                 type="object",
     *                 description="The return object",
     *                 example={
     *                  "edit_history_tweet_ids": "1727801020732801098",
     *                      "id": "1727801020732801098",
     *                      "text": "A random tweet from a laravel app"
     *                  }
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="A message indicating success",
     *                 example="Tweet successful"
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 description="An error message why the action was not successful",
     *                 example="Error message"
     *             ),
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 description="Indicates the operation was unsuccessful",
     *                 example=false
     *             ),
     *          ),
     *        )
     *    )
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function tweet(Request $request)
    {
        $this->validate($request, [
            'text' => 'required'
        ]);

        $result = $this->channelProvider->tweet($request->user(), $request->text);

        return response()->json($result, $result['success'] == true ? 200 : 400);
    }
}
