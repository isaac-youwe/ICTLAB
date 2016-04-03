#!/usr/bin/env bash

echo -e "Application user & directory\n"
echo "Insert username:"
read username

# Checks if username already exists, otherwise a new user will be created
if id -u "$username" >/dev/null 2>&1; then
    echo "User $username already exists"
else
    echo "Creating user $username"

    # Create user:
    sudo adduser $username
    sudo usermod -a -G sudo $username

    echo "User $username created!"
fi

echo

echo "Insert web directory:"
read directory

echo

user_directory=/home/$username
web_directory=$user_directory/$directory

# Checks if there is already a web directory, otherwise one will be created
if [ -d "$web_directory" ]; then
    echo "Web directory already exists"
else
    echo "Creating web directory..."

    # Create web directory:
    sudo mkdir $web_directory
fi


if [ -n "$(find $web_directory -user "$username" -print -prune -o -prune)" ]; then
  echo "$web_directory is owned by $username."
else
  sudo chown $userName:sudo $web_directory
fi

echo

# Update Ubuntu OS packages
#sudo apt-get update

# Create Solr Agent with username: solr-agent and password: 1234567
sudo adduser solr-agent
sudo usermod -a -G sudo solr-agent

# Add solr-agent to the sudo list
sudo echo 'solr-agent ALL=(ALL:ALL) ALL' >> /etc/sudoers

# Install Solr
su -c ./install-solr.sh solr-agent

# @TODO APT-GET SILENT

# Vim as a command line text editor
#sudo apt-get install vim

#echo "deleting $username"
#userdel -r $username

