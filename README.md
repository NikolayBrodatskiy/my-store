# Installation
Запуск Docker
```
docker compose up -d
```

Установка бекенда
```
docker compose exec app composer install
```

Установка фронтенда
```
docker compose exec app npm install
```
Запуск dev-окружения

"php artisan serve"
"php artisan queue:listen --tries=1 --timeout=0"
"php artisan pail --timeout=0"
"npm run dev" --names=server,queue,logs,vite --kill-others

http://localhost:8080
```
docker compose exec app composer run dev
```

Установка прав для папок
```
docker compose exec app chmod -R 775 ./storage
docker compose exec app chmod -R 775 ./bootstrap/cache
```
```
docker compose exec app php artisan migrate
docker compose exec app php artisan key:generate
#docker compose exec app php artisan app:install #создать admin-user, storage link
docker compose exec app php artisan db:seed #создать фейковые записи в таблицах
```

# Dev
Миграция бд + заполнение тестовыми данными
```
docker compose exec app php artisan migrate:fresh --seed
```

# Login

email: admin@admin.com

password: password
