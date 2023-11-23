<?php

namespace App\Api\V1\Controllers;

class WelcomeController extends Controller
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
     * Api welcome entry route
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/api/v1",
     *     tags={"Index"},
     *     @OA\Response(
     *         response="200",
     *         description="Welcome route"
     *     )
     * )
     */
    public function index()
    {
        return response()->json([
            'version' => app()->version(), 
            'message' => 'Welcome to Twitter comunication service Api'
        ]);
    }
}
