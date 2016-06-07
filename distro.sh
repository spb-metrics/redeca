#!/bin/bash
REDECA_DIR=redeca
mv .htaccess .htaccess2
echo 'RewriteEngine on
RewriteRule .* install.php

php_flag magic_quotes_gpc off
php_flag register_globals off
' > .htaccess

cd ..
tar --exclude=CVS -czf redeca-`cat $REDECA_DIR/VERSION.txt`.tar.gz $REDECA_DIR
zip -9 -r redeca-`cat $REDECA_DIR/VERSION.txt`.zip $REDECA_DIR

cd $REDECA_DIR
mv .htaccess2 .htaccess
