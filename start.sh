#!/bin/bash

# Остановить скрипт при любой ошибке
set -e

# Запуск контейнеров в фоновом режиме с пересборкой
docker-compose up -d --build

# Подождите несколько секунд, чтобы PostgreSQL был готов
echo "Waiting for PostgreSQL"
sleep 10

# Создание таблицы в базе данных PostgreSQL
echo "Creating users table"

docker exec -i postgres psql -U test -d test <<EOF
CREATE TABLE IF NOT EXISTS users (
    id BIGINT,
    balance BIGINT
);
EOF
# Вывод списка контейнеров для проверки
docker ps
