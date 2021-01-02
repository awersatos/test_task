# Тестовое задание "Каталог книг" 

## Установка проекта

### Linux
1. Скопировать файл переменных окружения cp .env.dist .env
2. При необходимости изменить значения переменных
3. Выполнить make build

### Windows 

1. Скопировать файл переменных окружения  .env.dist в .env
2. При необходимости изменить значения переменных, если были изменены параметы подключения к БД, то создать файл shared/.env.local
   и прописать туда DATABASE_URL="postgresql://$DB_USER:$DB_PASS@db:5432/$DB_NAME?serverVersion=13&charset=utf8", где
   $DB_USER, $DB_PASS и $DB_NAME значения соответствующих переменных
3. Выполнить docker-compose up -d --build
4. Зайти в контейнер php выполнив docker-compose exec --user www-data:www-data php bash
5. В контейнере выполнить composer install, затем bin/console doctrine:migrations:migrate

## Примеры методов API
По умолчанию хост 'http://localhost:60092', порт можно изменить перед деплоем, изменив соответствующую переменную в .env
#### Формат ответа
~~~
    "data": null|json-object - объект с данными
    "error": null|string - строка с описанием ошибки
~~~

### Создать автора
~~~
curl --location --request POST 'http://localhost:60092/author/create' \
--header 'Content-Type: application/json' \
--data-raw '{
    "name": "Лев Толстой"
}'
~~~

### Создать книгу
~~~
curl --location --request POST 'http://localhost:60092/book/create' \
--header 'Content-Type: application/json' \
--data-raw '{
    "authorId": 1,
    "ruName": "Анна Каренина",
    "enName": "Anna Karenina"
}'
~~~

### Получить книгу
~~~
curl --location --request GET 'http://localhost:60092/en/book/9530'
~~~

### Поиск книг
~~~
curl --location --request GET 'http://localhost:60092/book/search/Война'
~~~

## Вспомогательные команды (только Linux)

* make build - собрать проект
* make start - запустить контейнеры
* make stop - остановить контейнеры
* make down - удалить контейнеры
* mke ps - посмотреть список контейнеров
* bin/backend - зайти в контейнер php
* bin/composer - выполняет composer в контейнере php, аргументы указываются после команды
* bin/clear_cache - очищает кеш
* bin/migrations_migrate - выполняет миграции
* bin/php_unit - выполняет юнит-тесты

## Переменные окружения

| Название    | Значение по умолчанию   | Описание               |
| -----------:| -----------------------:| ----------------------:|
| UID         | 1000                    | ID пользователя        |
| GUID        | 1000                    | ID группы              |
| ENVIRONMENT | development             | Окружение              |
| TIMEZONE    | Europe/Moscow           | Временная зона         |
| PORT        | 60092                   | Внешний порт API       |
| DB_PORT     | 7155                    | Внешний порт БД        |
| DB_USER     | test-task               | Пользователь БД        |
| DB_PASS     | test-task               | Пароль пользователя БД |
| DB_NAME     | test-task               | Название БД            |