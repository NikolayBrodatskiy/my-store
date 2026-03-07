# Installation
```
docker compose compose up -d
docker compose exec app chmod -R 775 ./storage
docker compose exec app chmod -R 775 ./bootstrap/cache

docker compose exec app composer install
docker compose exec app npm install
docker compose exec app php artisan migrate
docker compose exec app php artisan key:generate
docker compose exec app php artisan app:install #создать admin-user, storage link
docker compose exec app php artisan db:seed #создать фейковые записи в таблицах

```
# Login

email: admin@admin.com

password: password
