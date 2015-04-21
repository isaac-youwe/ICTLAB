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

# Windows users
## Do this only once 
```git remote add upstream https://github.com/isaac-youwe/ICTLAB.git```

## To update local machine
### Reseve all new commits into local machine
```git checkout develop``` 
```git fetch upstream```
```git pull```
