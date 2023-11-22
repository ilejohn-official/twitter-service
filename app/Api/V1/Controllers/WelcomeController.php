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

    public function index()
    {
        return response()->json([
            'version' => app()->version(), 
            'message' => 'Welcome to Twitter comunication service Api'
        ]);
    }
}
