# Currency Exchange Rate

## Local Development

- 下載專案

    `git clone git@github.com:burgess1109/laravel-sample.git`

- cp .env.example .env

    可調整 `DB_DATABASE` 、 `DB_USERNAME` 、 `DB_PASSWORD`

- 安裝 images & run containers

    `docker-composer up -d`

- 安裝 php 套件

    `docker-compose exec php-fpm composer install`

- Generate APP_KEY

    `docker-compose exec php-fpm php artisan key:generate`

- DB initialization

    `docker-compose exec php-fpm php artisan migrate:fresh --seed`

- 設定完成至 http://localhost:8080 可看到 Laravel 頁面

## 其他指令

- PSR-12 lint

    `docker-compose exec php-fpm ./vendor/bin/phpcs ./`

- run test

    `docker-compose exec php-fpm php artisan test`

## API 規格

![Demo](./demo.png)

詳細規格請參考 [openapi.yml](./openapi.yml)
 
