[![Software License](https://img.shields.io/aur/license/yaourt.svg?style=round-square)](LICENSE.md)
[![Build Status](https://travis-ci.com/conceptsandtraining/ilias-tool-ilse.svg?token=S5A6thmo2LVbsWtZHFUA&branch=master)](https://travis-ci.com/conceptsandtraining/ilias-tool-ilse)

# **ilse** automatically builds ILIAS installations
**Proof of Concept for a Command Line Installation Script for [ILIAS](https://github.com/ILIAS-eLearning/ILIAS)**

*Please make sure you understand that this is not yet ready for production. Use this at your own risk.*

**Contact:** [Daniel Weise](https://github.com/daniwe4), [Richard Klees](https://github.com/klees)

## Prerequisits

### Software requirements
* PHP 5.6 or higher (PHP 7 works since Release 5.2)
* git 2.1.4 or higher
* [ILIAS requirements](https://github.com/ILIAS-eLearning/ILIAS/blob/trunk/docs/configuration/install.md)

### Installation
Download the PHAR for the latest release [here](https://github.com/conceptsandtraining/ilias-tool-ilse/releases).
These instructions assume that you make ilse executable as `ilse`.

## Usage

### Configuration
In order to let **ilse** install ILIAS for you, you need to create a configuration file
containing the required information for your installation.

```
ilse example-config
```

will give you a configuration that you need to adjust to your requirements.

### Installation
After you created your configuration file make **ilse** build an installation
from it:

```
ilse install $PATH_TO_CONFIG
```

You may also supply **ilse** with multiple config files. **ilse** will then prefer
entries from the latter over the former. This allows you to create basic config
file and overwrite only some config entries with more specific config files.

### Delete installation
If you got tired of your ILIAS installation, **ilse** will be happy to remove
it for you:

```
ilse delete $PATH_TO_CONFIG
```

## Outlook
**ilse** is in internal use at CaT for about a year now. It already contains
facilities to do some more stuff automatically:

* make some configurations (e.g. LDAP, SOAP, password requirements, ...)
* install plugins
* update the installation
* import content and org-structures
* create roles and users

At the moment we do not consider these to be stable enough to show them
to the public, thus only basic functionality is available for the cli interface
at the moment.

We also have the vision that someday some ILIAS configurations are provided
in a repository like [this one](github.com/conceptsandtraining/ilias-configs-public)
so that people who want to try ILIAS only need to configure some locations
on their system. This requirement will vanish as well, once [doil](github.com/conceptsandtraining/ilias-tool-doil)
or a similar tool works reliably. This project could also pen a huge space
for testing, manual as well as automatic.

We hope that this or a similar tool will be the or at least one official
way to install ILIAS in the future and will be happy to contribute our
code and knowledge to the community.

## Contributions
We are not ready to take contributions to **ilse** in an organized way at the moment.
Please contact us per e-mail if want to contribute code to the project, preferably
before you start your work.

We started cleanup and also perform major internal changes to the code base at the
moment. Once they are completed we reconsider opening this project to outside 
contributions in a more structured way.
