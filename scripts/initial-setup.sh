#!/usr/bin/env bash

echo "Please insert new administrator username"
read user

server_ip_address=`wget http://ipecho.net/plain -O - -q ; echo`

# Create new user
adduser $user

echo

# Add user to sudo group
gpasswd -a $user sudo

echo

# Generate a Key Pair
echo -e "Please run the following commands on your local machine\n"
echo "local$ ssh-keygen"
echo "Press enter"
echo "local$ ssh-copy-id $user@$server_ip_address"

# Configure SSH Daemon
# Disable Root Login
sed 's/PermitRootLogin yes/PermitRootLogin no/' /etc/ssh/sshd_config > temp.txt
mv -f temp.txt /etc/ssh/sshd_config

# Reload SSH
service ssh restart

# Check SSH login for user
echo -e "Run the following commands on your local machine\n"
echo "local$ ssh $user@$server_ip_address"
echo "If it was possible to login using SSH execute:"
echo "local$ exit"