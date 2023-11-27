# Twitter Communication Service

## Table of contents

- [General Info](#general-info)
- [External Dependencies](#external-dependencies)
- [Requirements](#requirements)
- [Setup](#setup)
- [Usage](#usage)

## General Info

This is a service for communicating with users using the Twitter channel.

- To subscribe users to a chat bot.
- To subscribe users to channel or chat.
- To send messages to subscribers.
- Webhooks to receive responses from messenger API.
- Fetch user details of associated twitter account
- Post a tweet from associated twitter account

## External Dependencies

- Atymic Twitter (https://github.com/atymic/twitter)
- L5-Swagger (https://github.com/DarkaOnLine/L5-Swagger)

## Requirements

- [php ^7.3|^8.0](https://www.php.net/ "PHP")
- [atymic/twitter ^3.1.11](https://github.com/atymic/twitter "Twitter SDK")
- [darkaonline/l5-swagger ^8.5](https://github.com/DarkaOnLine/L5-Swagger "L5-Swagger")

## Setup

- Clone the project and navigate to it's root path and install the required dependency packages using either of the below commands on the terminal/command line interface

  ```bash
  git clone https://github.com/ilejohn-official/twitter-service
  cd twitter-service
  ```

  ```
  composer install
  ```

- Copy and paste the content of the .env.example file into a new file named .env in the same directory as the former and set it's  
  values based on your environment's configuration.

- Create an app on [Twitter Application Management](https://developer.twitter.com/apps) and set the following


  ```
    TWITTER_CONSUMER_KEY=
    TWITTER_CONSUMER_SECRET=
    TWITTER_ACCESS_TOKEN=
    TWITTER_ACCESS_TOKEN_SECRET=
    TWITTER_API_VERSION=
    TWITTER_BOT_ID=
  ```

  Also set the Callback URI / Redirect URL as http://your-domain-name/api/v1/login-callback in your twitter app dashboard

- Generate Application Key

  ```
  php artisan key:generate
  ```

## Usage

- To run local server

  ```
  php artisan serve
  ```

  - Ensure `Content-Type` is set to `application/json` in all requests header.
  - Visit /api/v1 and you should see a welcome response
  - Send a GET request to /api/v1/bot-user to fetch the app(bot) user.
  - The /login route returns a redirect url that can be visited in the browser which then returns the token
  to be used in auth requests

