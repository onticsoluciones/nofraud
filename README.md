#NoFraud

Electronic Transactions Validator. 

#Table of Contents

* [Description](https://github.com/onticsoluciones/nofraud#description)
* [Technology](https://github.com/onticsoluciones/nofraud#technology-used)
* [Features](https://github.com/onticsoluciones/nofraud#features)
* [Automatic instalation](https://github.com/onticsoluciones/nofraud/blob/master/doc/docker.md)
* [Manual Installation](https://github.com/onticsoluciones/nofraud/blob/master/doc/installation.md)
* [Dashboard](https://github.com/onticsoluciones/nofraud/blob/master/doc/dashboard.md)
* [Magento extension demo](https://github.com/onticsoluciones/nofraud/blob/master/doc/magento_manual.md)
* [License](https://github.com/onticsoluciones/nofraud/blob/master/LICENSE)
* [Credits](https://github.com/onticsoluciones/nofraud#credits)


#Description

NoFraud is a federated multi-node electronic transactions validator. Uses Machine Learning to check transactions and give back if transaction is fraudulent or legitim. End users can configurate to which nodes can connect and params to check depending to your needs. The network nodes is configured as a close - federated and trusted system that uses SSL, TLS and certificate to exchange data. End users connect to those nodes with SSL and make API-Rest request to check if transtactions are legitim or fraudulents. If transaction is legitim, you can continue the proceess, if is fraudulent, system denied operation. Once the transaction is finished, the information is sent to the rest of the nodes to feed the machine learning and help make the global network more secure.

#Technology used

- [x] Machine Learning
- [x] Python
- [x] PHP (Core)
- [x] Docker
- [x] Tensor Flow
- [x] API-Rest
- [x] Dashing.io

#Features

- [x] Command Line
- [x] Magento Module
- [x] Configurable Plugin via Admin Panel
- [x] Multinode Connections
- [x] Monitoring Dashboard


#Credits

- Magento Community Edition -> https://magento.com/products/community-edition
- Docker -> https://www.docker.com/
- Tensor Flow -> https://www.tensorflow.org/
- Symphony -> http://symfony.es/
- Dashing.io -> https://github.com/Shopify/dashing
- Python -> https://www.python.org/
- PHP -> https://php.net/images/logo.php

#Contributors

* [Alfonso Moratalla](https://github.com/alfonsomoratalla) -> [Twitter](https://twitter.com/alfonso_ng)
* [Alejandro Sánchez](https://github.com/alsanchez) -> [Twitter](https://twitter.com/alsanchez_)
* [Ricardo Monsalve](https://github.com/ricarmon) -> [Twitter](https://twitter.com/ricarmonsalve)
* [Germán Sánchez](https://github.com/yercito) -> [Twitter](https://twitter.com/yeroncio)



