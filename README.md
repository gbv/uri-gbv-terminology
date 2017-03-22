# uri.gbv.de/terminology

## Installation

Das Repository kann direkt von GitHub geklont und aktualisiert werden:

    $ git clone https://github.com/gbv/uri-gbv-terminology.git
    $ cd uri-gbv-terminology

Benötigt wird PHP 7 mit der mbstring extension. Zusätzliche PHP-Bibliotheken
sind in `composer.json` aufgeführt und werden folgendermaßen installiert:

    $ sudo apt-get install composer php-mbstring 
    $ composer install --no-dev

Prinzipiell können verschiedene Webserver verwendet werden. Hier die
Installation unter Apache2 unter Ubuntu (>= 16.04):

    $ sudo apt-get install libapache2-mod-php
    $ sudo a2enmod rewrite php7.0
    $ sudo service apache2 restart

