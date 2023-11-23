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

- Generate Application Key

  ```
  php artisan key:generate
  ```

## Usage

- To run local server

  ```
  php artisan serve
  ```

  Visit http://localhost:8000/api/v1 and you should see a welcome response

