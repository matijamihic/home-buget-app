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

3. testing
php artisan db:seed --env=testing
php artisan test --env=testing

4. documentation
storage/api-docs/api-docs.json

regenarate documentation:
php artisan l5-swagger:generate

TODOs:
- improve requests validation
- create resource classes
- improve error handling
- more tests