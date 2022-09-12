#!/bin/bash

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[0;93m'
NC='\033[0m'

source ".docker/.env"

APP_NAME=$(echo "$APP_NAME")
DB_NAME=$(echo "$DB_NAME")
DB_USER=$(echo "$DB_USER")
DB_PASSWORD=$(echo "$DB_PASSWORD")

echo -e ${BLUE}"Export mysql ${DB_NAME} database${NC}"

# Asking for folder destination
echo -e ${GREEN}"Please enter folder destination${NC}"
read exportPath

# Exporting .sql file
echo -e ${GREEN}"Dumping mysql ${DB_NAME} database: $exportPath/${APP_NAME}_${DB_NAME}_dump.sql${NC}"
docker exec ${APP_NAME}-db /usr/bin/mysqldump -u ${DB_USER} --password=${DB_PASSWORD} ${DB_NAME} > $exportPath/${APP_NAME}_dump.sql
