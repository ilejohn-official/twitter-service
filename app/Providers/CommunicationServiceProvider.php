<?php

namespace App\Providers;

use App\Services\TwitterChannel;
use Illuminate\Support\ServiceProvider;

class CommunicationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('communication', function ($app) {
            $channel = $this->determineChannel();

            switch ($channel) {
                case 'twitter':
                    return $app->make(TwitterChannel::class);

                default:
                    throw new \Exception("Unsupported channel: $channel");
            }
        });
    }

    protected function determineChannel()
    {
        // Update logic to use other channels in future
        return 'twitter';
    }
}
