<?php

namespace App\Services;

use Atymic\Twitter\Facade\Twitter;
use Atymic\Twitter\Contract\Twitter as TwitterContract;
use Illuminate\Support\Facades\Log;
use Exception;

class TwitterChannel
{
    public function sendMessage($message, $channelId)
    {
        if (empty($channelId)) {
            return ['success' => false, 'error' => 'channelId required'];
        }

        try {
            $result = Twitter::getListsSubscribers(['list_id' => $channelId]);

            $subscribers = $result['data']['users'] ?? $result['users'];

            foreach ($subscribers as $subscriber) {
                Twitter::postDm([
                    'event' => [
                        'type' => 'message_create',
                        'message_create' => [
                            'target' => [
                                'recipient_id' => $subscriber['id']
                            ],
                            'message_data' => $message
                            
                        ]
                    ]
                ]);
            }

            return [
                'success' => true,
                'message' => 'Message sent'
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function subscribeToList($listId, $ownerId)
    {
        if (empty($listId) || empty($ownerId) ) {
            return ['success' => false, 'error' => 'ownerId and listId required'];
        }

        try {
            $result = Twitter::postListSubscriber(['list_id' => $listId, 'owner_id' => $ownerId]);

            return [
                'success' => true,
                'result' => $result['data'] ?? $result,
                'message' => 'User subscribed'
            ];
        } catch (Exception $e) {
            Log::error('Error subscribing user ' . $ownerId . ' to chat ' . $listId . ': ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function crcHash($crcToken)
    {
        return Twitter::crcHash($crcToken);
    }

    public function tweet($text)
    {
        $result = Twitter::forApiV2()->getQuerier()->withOAuth1Client()->post('tweets', [
            'text' => $text,
            TwitterContract::KEY_REQUEST_FORMAT => TwitterContract::REQUEST_FORMAT_JSON
        ]);

        return $result['data'];
    }
}
