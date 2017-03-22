# uri.gbv.de/terminology

## Installation

Das Repository kann direkt von GitHub geklont und aktualisiert werden:

    $ git clone https://github.com/gbv/uri-gbv-terminology.git
    $ cd uri-gbv-terminology

Benötigt wird PHP 7. Zusätzliche PHP-Bibliotheken sind in `composer.json`
aufgeführt und werden folgendermaßen installiert:

    $ composer install --no-dev

Prinzipiell können verschiedene Webserver verwendet werden. Hier die
Installation unter Apache2 unter Ubuntu:

    $ sudo apt-get install libapache2-mod-php7.0
    $ sudo a2enmod rewrite && sudo apache2 restart

