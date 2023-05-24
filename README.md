1. Start the app with

php artisan serve


2. Database:

is set to mysql
please update needed info in .env

then run: 

php artisan migrate

answer "yes" on prompt for creating a new database if needed

then run: 

php artisan db:seed

for testing
php artisan db:seed --env=testing
php artisan test --env=testing