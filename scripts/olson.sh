#!/usr/bin/env bash

#Author Olson Yarzagaray 0873764 INF2C 2014-2015

#Script version 1

#This script is made for an assignment for the course Development06B

#When running this script the first argument should be the path for the newly created folder

#the second argument is the name of the folder and the third argument is the name of the user.

#for the user the default supplied password is Test.

#the if-statement checks if there are 3 arguments supplied with this script.

if [ $# -eq 3 ]; then
    #the arguments are being link to the script variable

    folderPath=$1

    folderName=$2

    userName=$3

    #Create a demo folder with the name specified.

    echo "Creating folders with demo file"

    mkdir $folderPath/$folderName

    touch /$folderPath/$folderName/demo.txt

    #Create a user and give him the ownership of the folder created.

    sudo useradd -d /home/$userName $userName -p Test

    sudo adduser $userName sudo

    sudo chown -R $userName:sudo $filePath/$folderName

    #Install vim and filezilla via apt-get

    sudo apt-get install vim

    sudo apt-get install filezilla

    #archive folders that were made

    tar -czpf $folderPath/$folderName.tar.gz $folderPath

    #write a list of all folder in /etc/ to a file named ListFolderETC.txt

    ls /etc/ &gt; /home/$userName/Documents/ListFolderETC.txt
else
    echo "No arguments supplied... Please Enter a path you wish to create the folder in and the name of the two folders you wish to create."
fi