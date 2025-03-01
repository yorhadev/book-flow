# Laravel 11 Library Management API

This repository contains a Library Management API built with Laravel 11. Follow the steps below to set up and run the project locally using Laravel Sail.

## Installation

### Prerequisites
Ensure you have the following installed on your system:
- Docker
- Laravel Sail (included in dependencies)

### Setup
1. **Clone the repository:**
   ```sh
   git clone git@github.com:yorhadev/book-flow.git
   ```

2. **Navigate into the project directory:**
   ```sh
   cd book-flow
   ```

3. **Install dependencies using Docker:**
   ```sh
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v "$(pwd):/var/www/html" \
       -w /var/www/html \
       laravelsail/php84-composer:latest \
       composer install --ignore-platform-reqs
   ```

4. **Copy and configure the environment file:**
   ```sh
   cp .env.example .env
   ```

5. **Start the application with Sail:**
   ```sh
   ./vendor/bin/sail up
   ```

6. **Generate the application key:**
   ```sh
   ./vendor/bin/sail artisan key:generate
   ```

7. **Run database migrations:**
   ```sh
   ./vendor/bin/sail artisan migrate
   ```

8. **(Optional) Seed the database with genres:**
   ```sh
   ./vendor/bin/sail artisan db:seed --class=GenreSeeder
   ```

Your Laravel API should now be running!

## API Documentation
A Postman collection is available to test the API endpoints. You can import the collection into Postman using the following file:

- [Postman Collection](./postman/@yorhadev-book-flow.postman_collection.json)

To import the collection:
1. Open Postman.
2. Click **Import**.
3. Select the file `@yorhadev-book-flow.postman_collection.json`.
4. Start testing the API endpoints!

All requests are configured to run on `http://localhost`, ensuring easy setup for local development.
