<?php

namespace App\Services;

use App\Models\User;
use Atymic\Twitter\Facade\Twitter;
use Atymic\Twitter\Contract\Twitter as TwitterContract;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class TwitterChannel
{
    public function login()
    {
        try {
            $token = Twitter::getRequestToken(route('login.callback'));

            if (isset($token['oauth_token_secret'])) {
                $url = Twitter::getAuthenticateUrl($token['oauth_token']);
    
                // Store the token and token secret in the cache
                Cache::put('oauth_request_token', $token['oauth_token'], now()->addMinutes(30));
                Cache::put('oauth_request_token_secret', $token['oauth_token_secret'], now()->addMinutes(30));
    
                return [
                    'success' => true,
                    'result' => $url,
                    'message' => 'Login initiated successfully'
                ];
            }
    
            return ['success' => false, 'error' => 'Login initiation failed. token:'.$token];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Login initiation failed. error:'.$e->getMessage()];
        }
    }

    public function callback($oauthVerifier)
    {
        try {
            $oauthToken = Cache::get('oauth_request_token');
            $oauthTokenSecret = Cache::get('oauth_request_token_secret');

            if ($oauthToken) {
                $twitter = Twitter::usingCredentials($oauthToken, $oauthTokenSecret);
                $token = $twitter->getAccessToken($oauthVerifier);

                if (!isset($token['oauth_token_secret'])) {
                    return ['success' => false, 'error' => 'Failed to login via twitter. token:'.$token];
                }

                // use new tokens
                $twitter = Twitter::usingCredentials($token['oauth_token'], $token['oauth_token_secret']);
                $credentials = $twitter->getCredentials();

                if (is_object($credentials) && !isset($credentials->error)) {

                     // Check if user with this Twitter ID exists in the database or create
                    $user = User::firstOrCreate(['twitter_id' => $credentials->id_str], [
                        'name' => $credentials->name,
                        'twitter_screen_name' => $credentials->screen_name,
                        'twitter_oauth_token' => $token['oauth_token'],
                        'twitter_oauth_token_secret' => $token['oauth_token_secret']
                    ]);

                     // Create a token for the user
                    $accessToken = $user->createToken('twitter-token')->plainTextToken;

                    return ['success' => true, 'message' => 'Login successfully', 'token' => $accessToken, 'user' => $user];
                }
            }

            return ['success' => false, 'error' => 'Failed to login via twitter. token: '.$token];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to login via twitter. error: '.$e->getMessage()];
        }
       
    }

    public function sendMessage($message, $channelId)
    {
        if (empty($channelId)) {
            return ['success' => false, 'error' => 'channelId required'];
        }

        try {
            $result = Twitter::getListsSubscribers(['list_id' => $channelId]);

            $subscribers = $result->data->users ?? $result->users;

            foreach ($subscribers as $subscriber) {
                Twitter::postDm([
                    'event' => [
                        'type' => 'message_create',
                        'message_create' => [
                            'target' => [
                                'recipient_id' => $subscriber->id
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

    public function subscribeToList($listId, $user)
    {
        if (empty($listId) || empty($user) ) {
            return ['success' => false, 'error' => 'user and listId required'];
        }

        try {
            $twitter = Twitter::usingCredentials($user->twitter_oauth_token, $user->twitter_oauth_token_secret);
            $result = $twitter->postListSubscriber(['list_id' => $listId, 'owner_id' => $user->twitter_id]);

            return [
                'success' => true,
                'result' => $result->data,
                'message' => 'User subscribed'
            ];
        } catch (Exception $e) {
            Log::error('Error subscribing user ' . $user->twitter_id . ' to chat ' . $listId . ': ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function crcHash($crcToken)
    {
        return Twitter::crcHash($crcToken);
    }

    public function tweet($user, $text)
    {
        try {
            
            $result = Twitter::usingCredentials($user->twitter_oauth_token, $user->twitter_oauth_token_secret)->forApiV2()->getQuerier()->withOAuth1Client()->post('tweets', [
                'text' => $text,
                TwitterContract::KEY_REQUEST_FORMAT => TwitterContract::REQUEST_FORMAT_JSON
            ]);

            return [
                'success' => true,
                'result' => $result->data,
                'message' => 'Tweet successful'
            ];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function botUser()
    {
        try {
            $result = Twitter::forApiV2()->getQuerier()->withOAuth1Client()->get('users/me');

            return [
                'success' => true,
                'result' => $result->data,
                'message' => 'User retrieved'
            ];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
        
    }
}
