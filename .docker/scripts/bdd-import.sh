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

echo -e ${BLUE}"Import mysql ${DB_NAME} database${NC}"

# Asking for .sql file
echo -e ${GREEN}"Please enter .sql file path${NC}"
read sqlPath

# Importing .sql file
echo -e ${GREEN}"Importing .sql file : $sqlPath${NC}"
cat $sqlPath | docker exec -i ${APP_NAME}-db /usr/bin/mysql -u ${DB_USER} --password=${DB_PASSWORD} ${DB_NAME}
