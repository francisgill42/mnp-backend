Installation Guide


1 composer update

2 copy .env.example .env AND env configuration with database

3 php artisan key:generate

4 php artisan migrate

5 php artisan passport:install --force

6 php artisan db:seed

finaly run (php artisan serve)

copy address http://localhost:8000/api/login (only used in postman or api tester)

user name : master@erp.com
password : secret



