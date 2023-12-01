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
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/api/v1",
     *     description="Api welcome entry route",
     *     summary="Api welcome entry route",
     *     tags={"Index"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="version",
     *                 type="number",
     *                 description="Indicates the laravel version of the app",
     *                 example="8.83.27"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Welcome message",
     *                 example="Welcome to Twitter comunication service Api"
     *             ),
     *         ),
     *     ),
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
