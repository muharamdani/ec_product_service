## Product Service
### Requirements
- PHP >= 8.0
- BCMath PHP Extension
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

### Installation
- run composer install
- copy .env.example to .env
- Config .env file
- run php artisan key:generate
- run php artisan migrate
- run php artisan serve --port=8021

### It is fine to run in another port as long as didn't forget to change each URI SERVICES in .env
