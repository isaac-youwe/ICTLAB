#!/usr/bin/env bash

# Color for bash
Green='\e[0;32m';
Red='\e[0;31m';
Yellow='\e[0;33m';
White='\e[0;37m';

server_ip_address=`wget http://ipecho.net/plain -O - -q ; echo`

echo -e "${Green}Please insert the amount of new administrators that needs to be created [ENTER]\n"
read amount_of_admins

for i in `seq 1 $amount_of_admins`;
do
    echo -e "${Green}Please insert new administrator username and press [ENTER]\n"
    read user

    # Create new user
    echo -e "Creating user.....\n"
    adduser $user

    echo

    # Add user to sudo group
    echo -e "Adding user to sudo group.....\n"
    gpasswd -a $user sudo

    echo

    # Generate a Key Pair
    echo -e "${Green}Please run the following commands on your ${White}local ${Green}machine\n"
    echo -e "local$ ${Yellow}ssh-keygen"
    echo -e "${Green}Press enter"
    echo -e "${Green}local$ ssh-copy-id $user@$server_ip_address"

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

echo

# Configure SSH Daemon
# Disable Root Login
echo -e "Disabling root user...\n"
sed 's/PermitRootLogin yes/PermitRootLogin no/' /etc/ssh/sshd_config > temp.txt
mv -f temp.txt /etc/ssh/sshd_config

echo

echo "Configuring firewall...."
sudo ufw allow ssh
sudo ufw allow 80/tcp

echo

# Show exceptions
sudo ufw show added

echo

# Enable firewall
echo "Enabling firewall...."
sudo ufw enable

# Configure Timezone and Network Time Protocol Synchronization
sudo dpkg-reconfigure tzdata

# Configure NTP Synchronization
sudo apt-get -q -y update
sudo apt-get -q -y install ntp

# Instal VIM editor
sudo apt-get -q -y install vim

# Create Snapshot / Backup
echo "Creating Snapshot...."
sudo lvcreate --size 1G -s -n snapshot_lv /dev/vgroup/original_lv