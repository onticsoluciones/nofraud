#!/bin/bash

#Magento Data dump example
git clone https://github.com/onticsoluciones/nofraud-sample-data.git

#Deploy 3 nodes (mysql, magento, nofraud)
docker-compose up --build -d


# Import SQL dump configured
sleep 5
docker exec -it magentonofraud sh /tmp/import.sh
