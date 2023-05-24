1. Start the app with

composer install
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
php artisan migrate --env=testing
php artisan test --env=testing

4. documentation
storage/api-docs/api-docs.json

regenarate documentation:
php artisan l5-swagger:generate

5. postman collection
POSTMAN: storage/api-docs/home-buget-app.postman_collection.json

TODOs:
- improve requests validation
- create resource classes
- improve error handling
- more tests
- improve swagger docs