#!/bin/bash

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[0;93m'
NC='\033[0m'

source ".docker/.env"

APP_NAME=$(echo "$APP_NAME")

docker exec -it ${APP_NAME}-node yarn run prod

