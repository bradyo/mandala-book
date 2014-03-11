#!/bin/bash

# install dependencies
apt-get update
echo mysql-server mysql-server/root_password password vagrant | debconf-set-selections
echo mysql-server mysql-server/root_password_again password vagrant | debconf-set-selections
apt-get install --yes apache2 php5 php5-cli libapache2-mod-php5 php-apc \
	php5-mysql php5-curl php5-intl mysql-server
apt-get install --yes imagemagick php5-imagick
apt-get install --yes inkscape

# install phantomjs
cp /vagrant/environment/files/phantomjs-1.9.2-linux-x86_64.tar.bz2 ~/
tar jxf ~/phantomjs-1.9.2-linux-x86_64.tar.bz2 -C ~/
cp ~/phantomjs-1.9.2-linux-x86_64/bin/phantomjs /usr/bin/

# set up hosts
cp /vagrant/environment/hosts /etc/hosts

# initialize apache configuration
a2enmod rewrite
a2enmod ssl
a2enmod headers
rm /etc/apache2/sites-enabled/000-default 
rm /var/www/index.html
cp /vagrant/environment/apache2/httpd.conf /etc/apache2/httpd.conf
cp /vagrant/environment/apache2/ports.conf /etc/apache2/ports.conf
cp /vagrant/environment/apache2/sites-enabled/mandala.local /etc/apache2/sites-enabled/mandala.local

# initialize mysql
cp /vagrant/environment/mysql/my.cnf /etc/mysql/my.cnf

echo 'CREATE DATABASE mandala' | mysql -u root -pvagrant 

