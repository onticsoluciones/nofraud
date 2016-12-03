#!/bin/bash

#Deploy 3 nodes (mysql, magento, nofraud)
docker-compose up --build -d

# Import SQL dump configured
docker exec -it magentonofraud sh /tmp/import.sh
