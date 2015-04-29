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
## Step 1: ```git checkout develop```
## Step 2: ```git pull upstream develop```
## Step 3: ```git checkout -b [branchnaam]```
## Step 4: CODEREN
## Step 5: ```git pull upstream develop```
## Step 6: ```git status```
## Step 7: ```git add [gewijzigde files/folders]```
## Step 8: ```git commit -m '[message met je hebt gedaan]'```
## Step 9: ```git push origin [branchnaam]```
## Step 10: Ga naar ```https://github.com/isaac-youwe/ICTLAB```
## Step 11: Klik op PULL REQUEST
## Step 12: Klik op NEW PULL REQUEST
## Step 13: Verander compare:[branchnaam]
## Step 14: Klik op CREATE PULL REQUEST

# Windows users
## Do this only once 
```git remote add upstream https://github.com/isaac-youwe/ICTLAB.git```