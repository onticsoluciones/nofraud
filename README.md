# NoFraud

# Installation instructions

Required dependencies

php5 interpreter, curl, php5 extension for curl

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
