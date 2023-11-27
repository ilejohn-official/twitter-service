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
     * Login
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
