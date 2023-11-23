<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
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
     *     path="/api/v1/webhook/messenger",
     *     description="Handle webhook payload",
     *     summary="Handle webhook from messenger",
     *     tags={"Webhook"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Indicates whether the webhook was successfully handled",
     *                 example="webhook handled"
     *             ),
     *             @OA\Property(
     *                 property="response_token",
     *                 type="string",
     *                 description="CRC hash generated for the provided CRC token.",
     *                 example="hgtuctcdut"
     *             ),
     *         ),
     *     ),
     * )
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function handleResponse(Request $request)
    {
        if ($request->has('crc_token')){
            return response()->json(['response_token' => $this->channelProvider->crcHash($request->crc_token)]);
        }

        //Further Actions can be carried out with the request content from twitter
        Log::info($request->getContent());

        return response()->json([
            'message' => 'webhook handled'
        ]);
    }
}
