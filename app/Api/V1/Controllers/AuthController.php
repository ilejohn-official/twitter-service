<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
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
     * 
     *    @OA\Post(
     *      path="/api/v1/login",
     *      summary="Initiate login process",
     *      description="Initiate login process",
     *      tags={"Authentication"},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  description="Indicates whether login was successfully initiated",
     *                  example=true
     *              ),
     *              @OA\Property(
     *                  property="result",
     *                  type="string",
     *                  description="The redirect url",
     *                  example="https://api.twitter.com/oauth/authenticate?oauth_token=xxxxxxxxxxxxxxxxx"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  description="Message on the success",
     *                  example="Login initiated successfully"
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Error",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  description="Indicates there's an error initiating the login",
     *                  example=false
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  description="The resulting error",
     *                  example="Login initiation failed. error:..."
     *              ),
     *          ),
     *       )
     *    ) 
     * 
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function login()
    {
        $result = $this->channelProvider->login();

        return response()->json($result, $result['success'] == true ? 200 : 400);
    }

    /**
     *
     * Handle login callback
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function callback(Request $request)
    {
        $result = $this->channelProvider->callback($request->query('oauth_verifier'));

        return response()->json($result, $result['success'] == true ? 200 : 400);
    }
}
