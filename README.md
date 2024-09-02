# uri.gbv.de/terminology

***THIS APPLICATION HAS BEEN REPLACED BY <https://github.com/gbv/jskos-proxy>***

## Installation

Create a user `uri-terminology` and check out the repository

    $ sudo adduser uri-terminology --disabled-password --home /srv/uri-terminology
    $ sudo -iu uri-terminology
    $ git clone --bare https://github.com/gbv/uri-gbv-terminology .git
    $ git init; git checkout
    $ composer install --no-dev

Die Anwendung läuft mittels nginx und PHP-FPM.

    $ sudo apt-get install nginx php-fpm
    $ sudo cp /srv/uri-terminology/uri-terminology /etc/nginx/sites-enabled/uri-terminology # ggf. anpassen
    $ sudo service nginx restart

## Development

    $ composer install

Prinzipiell können verschiedene Webserver verwendet werden. Zum kurzen Testen
eignet sich beispielsweise der PHP-eigene Webserver:

    $ php -S localhost:8090 -t public/
