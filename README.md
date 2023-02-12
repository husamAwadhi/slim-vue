# slim-vue
Application Skeleton for a backend running Slim PHP and frontend running Vue with out of the box JWT authentication

## Tech
backend running on [Slim Framework 4](api/README.md) and [Vue 3 in Vite](frontend/README.md) using [PostgreQL 14](https://www.postgresql.org/about/news/postgresql-14-released-2318/) Database and [Nginx](https://docs.nginx.com/) Web server. 

## prerequisites
- Docker

## setup
in project root directory,
```sh
docker-compose -f docker/docker-compose.dev.yaml --env-file docker/.env build
```
```sh
docker-compose -f docker/docker-compose.dev.yaml --env-file docker/.env up
```
```sh
docker exec -it slim-php bash -c \
    "cd /app && composer install && php bin/doctrine orm:schema-tool:create && php bin/fixtures"
```

## access
* backend: localhost:80
* frontend: localhost:8000

## Important Files
* login info: [User Fixture](api/src/Fixtures/UserDataLoader.php)
* backend 
  * routes: [routes](api/app/routes.php) 
  * settings: [settings](api/app/settings.php)
* frontend 
  * routes: [routes](frontend\src\router\index.js) 
  * settings: [config](frontend/vite.config.js)
