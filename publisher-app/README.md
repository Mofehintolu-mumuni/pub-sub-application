# Publisher-app
This application shows how a two applications can be use RabbitMq to communicate. This kind of communication is used in microservices architecture and prevents too many api calls to services thereby reducing latency .The publisher app and sunscriber app communicate using jobs and queues that are managed by a rabbitmq docker container attached in this app folder. All applications were developed using laravel.

There is an in-app sql lite database setup for testing.

# Application setup

This application is running on laravel version ^8.54, and PHP ^8.0. Kindly ensure that your system meets this specification.

Ensure that your .env file is an exact replica of the .env.example file.

The main business logic of this application consists of a publisher app that allows a user to create a topic, subscribe apps to a topic, list topics created and publish messages to topics created. These actions all have endpoints which have documentation that can be accessed using api/documentation from the publisher app. The subsciber application connects to queues managed by rabbitmq, listens for new jobs published to queues and prints the messages sent is it subscribed to the topic.

The subscriber app would run on localhost:9000 this url is needed when sending requests to api that subscribe to a topic i.e api/subscribe/{topicId}

Error handling was implemented in the application with errors logged via the Illuminate\Support\Facades\Log  Facade.


 # RabbitMq server Setup
  To run the rabbitmq server cd into the directory named rabbit-mq and run docker compose up
  

  # Publisher App Setup
  This application has the following server app setup
  
  Ensure that xampp server is used.

  On the command terminal Run composer install

  To run migration files run command: php artisan migrate

  To start the server run php artisan serve

  To run unit tests use the following command: php artisan test


   # Subscriber App Setup
  This application has the following server app setup
  
  Ensure that xampp server is used.

  On the command terminal Run composer install

  To run migration files run command: php artisan migrate

  To start the server run the custom command I created that uses custom port in the env and perfroms an Artisan::call() to start the server. Run php artisan start:app

  To run the queue workers run php artisan queue:work