# Installation instructions

Required dependencies

- Magento 1.9.x

Copy files on Magento directory

Clear cache, run compilation (if active) and logout from backend (if logged)

# Extension configuration

On Admin -> System -> Configuration -> Ontic -> Parameters

- Host: IP or domain name for NoFraud service

- Username: Username for NoFraud service

- Password: Password for NoFraud service

- Min: Down this, transactions are marked as fraudulent

- Max: Up this, transactions are considered fine

# Feed learning

- Orders marked as Canceled are informed as bad for learning puporse

- Orders marked as Completed (Shipped and Invoiced) are informed as good
