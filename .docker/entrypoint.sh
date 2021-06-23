#! /bin/sh

set -ex

sed -i "s|e9e4498f0584b7098692512db0c62b48|${HOST_KEY}|g" /var/www/html/public/config.php              
sed -i "s|ze3kr@example.com|${HOST_MAIL}|g"               /var/www/html/public/config.php          
sed -i "s|// \$page_title = \"TlOxygen\"|\$page_title = \"${TITLE}\"|g" /var/www/html/public/config.php 
sed -i "s|// \$tlo_path = \"/\"|\$tlo_path = \"/\"|g" /var/www/html/public/config.php                

apache2-foreground