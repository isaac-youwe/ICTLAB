#!/usr/bin/env bash

echo "Insert username:"
read username

# Checks if username already exists, otherwise a new user will be created
if id -u "$username" >/dev/null 2>&1; then
    echo "User $username already exists"
else
    echo "Creating user $username"

    # Create user:
    sudo adduser $username
    echo "User $username created!"
fi

echo

echo "Insert group:"
read group

# Checks if group already exists, otherwise a new group will be created
if egrep -i -q "^$group" /etc/group; then
   echo "Group $group exists"
else
   echo "Creating group $group"

   # Create group:
   sudo groupadd $group
   echo "Group $group created!\n"
fi

echo

# Checks if user is already a member of group, otherwise username will be assign to group
if groups $username | grep &>/dev/null "\b$group\b"; then
    echo "User $username is already member of the $group group"
else
    echo "Adding $username to $group"

    # Add user to (as the primary) group:
#    sudo chown -R username:group directory
    sudo usermod -a -G $group $username
fi

#Create a user and give him the ownership of the folder created.
sudo useradd -d /home/$userName $userName -p Test
sudo adduser $userName
sudo chown -R $userName:sudo $filePath/$folderName

echo

echo "Insert web directory:"
read web_directory

# Checks if there is already a web directory, otherwise one will be created
if [ -d "$web_directory" ]; then
    echo "Web directory already exists"
else
    echo "Creating web directory..."

    # Create web directory:
    sudo mkdir $web_directory
fi

echo

echo "Changing/ adding owner permission on $web_directory"

# Change to owner/group:
sudo chown -R $username:$group $web_directory

# Give group members proper permissions:
sudo chmod -R g+w $web_directory

# Change user:
su - $username








#echo "deleting $username"
#userdel -r $username

# Create user:
#sudo adduser solr-manager

# Change user
#su - solr-manager

# Install Solr
