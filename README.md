[![Software License](https://img.shields.io/aur/license/yaourt.svg?style=round-square)](LICENSE.md)

# ilse
**A Command Line Installation Script for [ILIAS](https://github.com/ILIAS-eLearning/ILIAS)**

## Usage
### Software requirements
```
* PHP 5.4 or higher (PHP 7 only works with trunk)
* MySQL 5.0.x or higher
* Zip and Unzip
* ImageMagick
* Composer
* git 2.1.4 or higher
```
### Installation
```
$ cd DESTINATION_FOLDER
$ git clone https://github.com/conceptsandtraining/ilias-tool-ilse.git ilse
$ cd ilse
$ composer install
```

### Configuration
Ilias-installer get the config file from the github repo `https://github.com/conceptsandtraining/ilias-configs.git`  
The name of the config file is always `ilse_config.yaml`.  
Each config file is inside a directory that represents the customomer name.  

* Edit the file src/default.yaml
* Save the file as ilse_config.yaml
* Push the file into the destination folder of the repo named above.


### Required configuration entries
For new installation of ILIAS you need these configuration entries.
```
* client
* database
* language
* server
* setup
* tools
* log
* git_branch
```

### Re- / Installation of ILIAS
With ilse it´s possible to install a new ILIAS or drop your old an install in one step.
For both it is possible to run the installation in a non interactiv mode.
If you would use this, just add the second optional parameter. Value of the parameter is "non_interactiv".

Before you install or reinstall, please switch to your www user, e.g _www or www-data.
##### Installation
```
$ php src/bin/install.php src/config.yaml [non_interactiv]
```

##### Reinstallation
```
$ ./src/bin/reinstall_ilias.sh src/config.yaml [non_interactiv]
```
