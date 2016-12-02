# NoFraud

Electronic Transactions Validator. 

# Description

NoFraud is a federated multi-node electronic transactions validator. Uses Machine Learning to check transactions and give back if transaction is fraudulent or legitim. End users can configurate to which nodes can connect and params to check depending to your needs.


# Technology used

- [x] Machine Learning
- [x] Python
- [x] PHP (Core)
- [x] Docker - Kubernetes
- [x] Polymer
- [x] Tensor Flow
- [x] API-Rest

# Features

- [x] Magento Module
- [x] Configurable Plugin via Admin Panel
- [x] Multinode Connections

# Installation instructions

Required dependencies

- [x] php5 interpreter or above
- [x] curl
- [x] php extension for curl

Clone the repository

```bash
git clone https://github.com/onticsoluciones/nofraud.git
```

After cloning the repository, download composer from the official website

```bash
curl -sS https://getcomposer.org/installer | php
```

And install the project dependencies

```bash
cd nofraud
php composer.phar install
```

At that point you can use the built-in PHP webserver to run the software

```bash
php -S localhost:9000 -t .
```

The exposed resources should be accessible at:

http://localhost:9000/capabilities [GET]

http://localhost:9000/assesment [POST]

# Todo

# Credits


