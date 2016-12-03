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


