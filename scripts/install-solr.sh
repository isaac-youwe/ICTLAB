#!/usr/bin/env bash

# Install Solr
apt-get -y install openjdk-7-jdk
mkdir -p /usr/java
ln -s /usr/lib/jvm/java-7-openjdk-amd64 /usr/java/default
apt-get -y install solr-tomcat

sudo apt-get install libcurl4-gnutls-dev libxml2 libxml2-dev
sudo apt-get install libpcre3-dev
sudo pecl install -n solr
sudo echo "extension=solr.so" >> /etc/php5/apache2/php.ini
sudo echo "extension=solr.so" > /etc/php5/apache2/conf.d/solr.ini
sudo service apache2 restart