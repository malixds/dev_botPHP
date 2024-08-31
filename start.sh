#!/bin/bash

set -e

docker-compose up -d --build

echo "Waiting for PostgreSQL"
sleep 10

echo "Creating users table"

docker exec -i postgres psql -U test -d test <<EOF
CREATE TABLE IF NOT EXISTS users (
    id BIGINT,
    balance BIGINT
);
EOF
