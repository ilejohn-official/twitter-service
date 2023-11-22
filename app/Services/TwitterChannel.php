<?php

namespace App\Services;

use Atymic\Twitter\Facade\Twitter;
use Illuminate\Support\Facades\Log;
use Exception;

class TwitterChannel
{
    public function sendMessage($message, $channelId)
    {
        if (empty($channelId)) {
            throw new \BadMethodCallException('channelId required');
        }

        try {
            $subscribers = Twitter::getListsSubscribers(['list_id' => $channelId]);

            foreach ($subscribers as $subscriber) {
                Twitter::postDm([
                    'event' => [
                        'type' => 'message_create',
                        'message_create' => [
                            'target' => [
                                'recipient_id' => $subscriber
                            ],
                            'message_data' => $message
                            
                        ]
                    ]
                ]);
            }

            return [
                'message' => 'Message sent',
                'result' => true
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function subscribeToList($listId, $ownerId)
    {
        try {
            Twitter::postListSubscriber(['list_id' => $listId, 'owner_id' => $ownerId]);

            return true;
        } catch (Exception $e) {
            Log::error('Error subscribing user ' . $ownerId . ' to chat ' . $listId . ': ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }
}
