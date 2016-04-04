#!/usr/bin/env bash

## Color for bash
#Green='\e[0;32m';
#Red='\e[0;31m';
#Yellow='\e[0;33m';
#White='\e[0;37m';
#
#server_ip_address=`wget http://ipecho.net/plain -O - -q ; echo`
#
#echo -e "${Green}Please insert the amount of new administrators that needs to be created and press [ENTER]\n"
#read amount_of_admins
#
#for i in `seq 1 $amount_of_admins`;
#do
#    echo -e "${Green}Insert administrator username and press [ENTER]\n"
#    read user
#
#    # Create new user
#    echo -e "Creating user $user\n"
#    adduser $user
#
#    echo
#
#    # Add user to sudo group
#    echo -e "Adding $user to sudo group.....\n"
#    gpasswd -a $user sudo
#
#    echo
#
#    # Generate a Key Pair
#    echo -e "${Green}Please run the following commands on your ${White}local ${Green}machine\n"
#    echo -e "local$ ${Yellow}ssh-keygen"
#    echo -e "${Green}Press enter"
#    echo -e "local$ ${Yellow}ssh-copy-id $user@$server_ip_address"
#
#    echo
#
#    echo -e "${Green}Type y to continue\n"
#    read continue
#
#    if [[ $continue =~ ^[Yy]$ ]]; then
#        # Reload SSH
#        service ssh restart
#
#        # Check SSH login for user
#        echo -e "${Green}Run the following commands on your local machine\n"
#        echo -e "local$ ${Yellow}ssh $user@$server_ip_address"
#        echo "If it was possible to login using SSH execute:"
#        echo -e "local$ ${Yellow}exit"
#    fi
#done
#
#echo -e "${Green}"
#
## Configure SSH Daemon
## Disable Root Login
#echo -e "Disabling root user...\n"
#sed 's/PermitRootLogin yes/PermitRootLogin no/' /etc/ssh/sshd_config > temp.txt
#mv -f temp.txt /etc/ssh/sshd_config
#
#echo
#
## https://www.digitalocean.com/community/tutorials/how-to-set-up-a-firewall-with-ufw-on-ubuntu-14-04
#echo "Configuring firewall...."
#sudo ufw allow ssh
#sudo ufw allow 80/tcp
#
## Solr ports
#sudo ufw allow 8983/tcp
#sudo ufw allow 8983/udp
#
#echo
#
## Show exceptions
#sudo ufw show added
#
#echo
#
## Enable firewall
#echo "Enabling firewall...."
#sudo ufw enable -y
#
## Configure Timezone and Network Time Protocol Synchronization
#sudo dpkg-reconfigure tzdata
#
## Configure NTP Synchronization
#sudo apt-get -q -y update
#sudo apt-get -q -y install ntp

# Install Java JDK
sudo apt-get -q -y install openjdk-7-jdk
mkdir -p /usr/java
ln -s /usr/lib/jvm/java-7-openjdk-amd64 /usr/java/default

# Install Solr
# https://www.digitalocean.com/community/tutorials/how-to-install-solr-on-ubuntu-14-04
cd /opt
wget http://archive.apache.org/dist/lucene/solr/5.3.1/solr-5.3.1.tgz
tar -xvf solr-5.3.1.tgz
ln -s solr-5.3.1/example /opt/solr
cd /opt/solr
java -jar start.jar

# Configure environment /etc/default/jetty
echo -e "NO_START=0 # Start on boot
JAVA_OPTIONS=\"-Dsolr.solr.home=/opt/solr/solr $JAVA_OPTIONS\"
JAVA_HOME=/usr/java/default
JETTY_HOME=/opt/solr
JETTY_USER=solr
JETTY_LOGS=/opt/solr/logs" >> /etc/default/jetty

# Configure logging /opt/solr/etc/jetty-logging.xml

# Create Solr user and grand it permissions:
sudo useradd -d /opt/solr -s /sbin/false solr
sudo chown solr:solr -R /opt/solr

# Download start file and set it to automatically start up:
sudo wget -O /etc/init.d/jetty http://dev.eclipse.org/svnroot/rt/org.eclipse.jetty/jetty/trunk/jetty-distribution/src/main/resources/bin/jetty.sh
sudo chmod a+x /etc/init.d/jetty
sudo update-rc.d jetty defaults

# Start Jetty/Solr:
sudo /etc/init.d/jetty start

# Install SolrClient
sudo apt-get -q -y install libcurl4-gnutls-dev libxml2 libxml2-devsudo apt-get install libpcre3-dev
sudo pecl install -n solr
sudo echo "extension=solr.so" >> /etc/php5/apache2/php.ini
sudo echo "extension=solr.so" >> /etc/php5/apache2/conf.d/solr.ini
sudo service apache2 restart

# Install libgeos with php5 bindings
sudo apt-get install -q -y apache2 php5 libapache2-mod-php5 php5-dev phpunit
cd /opt
wget http://download.osgeo.org/geos/geos-3.4.2.tar.bz2
tar -xjvf geos-3.4.2.tar.bz2
ln -s geos-3.4.2 /opt/geos
cd geos
./configure --enable-php
sudo make install
sudo cat > /etc/php5/mods-available/geos.ini << EOF
    ; configuration for php geos module
    ; priority=50
    extension=geos.so
EOF

# Enable PHP module geos
sudo php5enmod geos
sudo service apache2 restart

# Install composer
php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
php -r "if (hash('SHA384', file_get_contents('composer-setup.php')) === '7228c001f88bee97506740ef0888240bd8a760b046ee16db8f4095c0d8d525f2367663f22a46b48d072c816e7fe19959') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer

# Create Snapshot / Backup
#echo "Creating Snapshot...."
#sudo apt-get -q -y intall lvm2
#sudo lvcreate --size 1G -s -n snapshot_lv /dev/vgroup/original_lv