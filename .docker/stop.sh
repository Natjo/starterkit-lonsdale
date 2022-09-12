#!/bin/bash

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[0;93m'
NC='\033[0m'

cd "$(dirname "$0")"

echo -e ${RED}"Stop all docker containers${NC}"
docker container stop $(docker container ls -aq)

echo -e ${RED}"Delete all docker containers${NC}"
docker container rm $(docker container ls -aq)

# docker rm -v $(docker ps -a -q)
# docker rmi -f $(docker images -q)