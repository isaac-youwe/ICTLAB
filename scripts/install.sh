#!/usr/bin/env bash

# Color for bash
Green='\e[0;32m';
Red='\e[0;31m';
Yellow='\e[0;33m';
White='\e[0;37m';

server_ip_address=`wget http://ipecho.net/plain -O - -q ; echo`

echo -e "${Green}Please insert the amount of new administrators that needs to be created and press [ENTER]\n"
# Input Arguments
read amount_of_admins

for i in `seq 1 $amount_of_admins`;
do
    echo -e "${Green}Insert administrator username and press [ENTER]\n"
    read user

    # Create new user
    echo -e "Creating user $user\n"
    adduser $user

    echo

    # Add user to sudo group
    echo -e "Adding $user to sudo group.....\n"
    gpasswd -a $user sudo

    echo

    # Generate a Key Pair
    echo -e "${Green}Please run the following commands on your ${White}local ${Green}machine\n"
    echo -e "local$ ${Yellow}ssh-keygen"
    echo -e "${Green}Press enter"
    echo -e "local$ ${Yellow}ssh-copy-id $user@$server_ip_address"

    echo

    echo -e "${Green}Type y to continue\n"
    read continue

    if [[ $continue =~ ^[Yy]$ ]]; then
        # Reload SSH
        service ssh restart

        # Check SSH login for user
        echo -e "${Green}Run the following commands on your local machine\n"
        echo -e "local$ ${Yellow}ssh $user@$server_ip_address"
        echo "If it was possible to login using SSH execute:"
        echo -e "local$ ${Yellow}exit"
    fi
done

echo -e "${White}"

# Configure SSH Daemon
# Disable Root Login
echo -e "Disabling root user...\n"
sed 's/PermitRootLogin yes/PermitRootLogin no/' /etc/ssh/sshd_config > temp.txt
mv -f temp.txt /etc/ssh/sshd_config

echo

# Configure NTP Synchronization
# apt-get silent
sudo apt-get -q -y update

# Install Java JDK
# Symbolische link voor Java
sudo apt-get -q -y install openjdk-7-jdk

# Create directory if it doesn't exist
mkdir -p /usr/java
ln -s /usr/lib/jvm/java-7-openjdk-amd64 /usr/java/default

# Install Solr
# https://www.digitalocean.com/community/tutorials/how-to-install-solr-on-ubuntu-14-04
cd /opt
wget http://archive.apache.org/dist/lucene/solr/5.3.1/solr-5.3.1.tgz
tar -xvf solr-5.3.1.tgz
ln -s solr-5.3.1/example /opt/solr
rm solr-5.3.1.tgz
cd /opt/solr
java -jar start.jar # Error: Unable to access jarfile start.jar

# Configure environment /etc/default/jetty
echo -e "NO_START=0 # Start on boot
JAVA_OPTIONS=\"-Dsolr.solr.home=/opt/solr/solr $JAVA_OPTIONS\"
JAVA_HOME=/usr/java/default
JETTY_HOME=/opt/solr
JETTY_USER=solr
JETTY_LOGS=/opt/solr/logs" >> /etc/default/jetty

# Configure logging /opt/solr/etc/jetty-logging.xm

# Create Solr user and grand it permissions:
sudo useradd -d /opt/solr -s /sbin/false solr
sudo chown solr:solr -R /opt/solr

# Download start file and set it to automatically start up:
# https://github.com/eclipse/jetty.project/blob/master/jetty-distribution/src/main/resources/bin/jetty.sh
sudo wget -O /etc/init.d/jetty http://dev.eclipse.org/svnroot/rt/org.eclipse.jetty/jetty/trunk/jetty-distribution/src/main/resources/bin/jetty.sh # doesn't exist
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

# Create configuration file and archive it
current_user=`whoami`
mkdir -p /home/$current_user/opdrachta
ls /etc/ > /home/$current_user/opdrachta/configurations.txt
cd /home/$current_user/opdrachta
gzip configurations.txt
cd /

# remove logs every 5 days
sudo echo "@weekly rm -fr /opt/solr/logs" >> crontab -e