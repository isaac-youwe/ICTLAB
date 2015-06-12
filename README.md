This is the repository for the modulecode ICTLAB
==============

University of applied science Rotterdam
--------------
This README.md is a guide on how to install the project.

## Install the composer
```curl -sS https://getcomposer.org/installer | php```

## Install the project & dependecies 
```php composer.phar install```

## Add Vendor/Zend symlink to app/library
```ln -s path/to/vendor/zend .```

## Download and install Solr
```wget http://mirrors.supportex.net/apache/lucene/solr/5.1.0/solr-5.1.0.zip```

```unzip -q solr-5.1.0.zip```

## Install SolrClient
```sudo apt-get install libcurl4-gnutls-dev libxml2 libxml2-dev```

```sudo apt-get install libpcre3-dev```

```sudo pecl install -n solr```

```sudo echo "extension=solr.so" >> /etc/php5/apache2/php.ini```

```sudo echo "extension=solr.so" > /etc/php5/apache2/conf.d/solr.ini```

```sudo service apache2 restart```

## Create collection in Solr and index files
```bin/solr start```

```bin/solr create -c shfiles```

copy the shapefiles into the ICTLAB map

```bin/post -c shfiles ICTLAB/shapefiles/```

To delete a collection use:

```bin/solr delete -c shfiles```

## Download credentials.json from Google Drive and paste it in ICTLAB

=======
**Not for sale purposes**

The Collaborators of this project are:
- Isaac de Cuba
- Karam Jezrawi
- Adriel Walter
- Serhildan Akdeniz

=======
# ICTLAB
## Workflow
### Master branch -> Develop branch -> working branches
### Every developer should push to the working branch and create PR to the develop branch
## Step 1:
```git checkout develop```
## Step 2:
```git pull upstream develop```
## Step 3:
```git checkout -b [branchnaam]```
## Step 4:
CODEREN
## Step 5:
```git pull upstream develop```
## Step 6:
```git status```
## Step 7:
```git add [gewijzigde files/folders]```
## Step 8:
```git commit -m '[message met wat je hebt gedaan]'```
## Step 9:
```git push origin [branchnaam]```
## Step 10:
Ga naar ```https://github.com/isaac-youwe/ICTLAB```
## Step 11:
Klik op PULL REQUEST
## Step 12:
Klik op NEW PULL REQUEST
## Step 13:
Verander compare:[branchnaam]
## Step 14:
Klik op CREATE PULL REQUEST

# Convert KML to JSON script
### Navigate first into the ICTLAB/scripts folder
### And run this command
```php convert-kml-to-json-script.php```

# Calculate the intersection script
### Navigate first into the ICTLAB/scripts folder
### And run this command
```php intersection-script.php```

# PHPStorm changes
## To prevent PHPStorm of running slow (exclude index for the shapefiles and data folders)
### Right click on data and shapefiles folder in ICTLAB folder and Mark Directory As Excluded
