## Clean Installation Guide.

### Prerequisites.
Before you start, make sure you have the following:
- PHP 8.1 or higher.
- MySQL 5.7 or higher.
- Composer 2.3.10 or higher.
- Node.js 16 or higher.
- Yarn 1.22.17 or higher.

If you don't have any of these, you should install them first.

### Installation.
1. Clone the repository.
2. Run `composer install` to install PHP dependencies.
3. Run `yarn install` to install Node.js dependencies.
4. Open PhpMyAdmin or any other MySQL client and create a database for the application.
5. Copy `.env.example` to `.env` with following command `cp .env.example .env` and fill in the following fields:
    - `APP_URL` is the URL of your application.
    - Update all `DB_*` fields with your database's credentials.
        - This application uses two databases, one for the main application and the other for the Alumni data that will be imported from the CSV file.
    - `MAIL_*` fields are used for sending emails. You can use [Mailtrap](https://mailtrap.io/) for testing.
6. Open the terminal and run `php artisan migrate` to create the database tables.
7. Run `php artisan passport:install` to create the OAuth keys.
8. Run `php artisan key:generate` to generate the application key.
9. Run `php artisan db:seed` to seed the database with the default data.

#### Conguratulations! You have successfully installed the application.

### How to run.
1. Open the terminal Run `php artisan serve` to start the application backend.
2. Open another terminal and run `yarn run serve` to start the frontend.
3. Open your browser and go to `http://localhost:8080/spa` to view the application.

### Credentials
- Email: `mumerfarooqlablnet01@gmail.com`
- Password: `password`

- *Note*: Make sure you have configured your .env file with the correct settings and follow above steps in order."

## Webhooks.
- Add below webhooks in your coinbase account.
- yoursite.com/webhook/charge-completed
- yoursite.com/webhook/charge-failed
- yoursite.com/webhook/charge-delayed

