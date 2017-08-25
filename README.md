# uri.gbv.de/terminology

## Installation

Das Repository kann direkt von GitHub geklont und aktualisiert werden:

    $ git clone https://github.com/gbv/uri-gbv-terminology.git
    $ cd uri-gbv-terminology

Benötigt wird PHP 7 mit den Erweiterungen mbstring und curl:

    $ sudo apt-get install php-curl php-mbstring

Zusätzliche PHP-Bibliotheken sind in `composer.json` aufgeführt und werden
folgendermaßen installiert:

    $ composer install --no-dev

Prinzipiell können verschiedene Webserver verwendet werden. Hier die
Installation unter Apache2 unter Ubuntu (>= 16.04):

    $ sudo apt-get install libapache2-mod-php
    $ sudo a2enmod rewrite php7.0
    $ sudo service apache2 restart

Für nginx funktioniert u.A. folgende Konfiguration:

    location /terminology {
        rewrite ^(/terminology)$ $1/ permanent;
        if (-f $request_filename) {
            break;
        }
        rewrite ^/terminology/(.+)$ /terminology/index.php/$1;
    }
    location ~ \.php(/.*)?$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php7.0-fpm.sock;
    }

