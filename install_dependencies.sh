#!/bin/bash

#Shell script will ensure that all the correct dependencies are installed.
# This includes the following packages
# - php
# - libapache2-mod-php
#
sudo apt-get update
sudo apt-get install php libapache2-mod-php php-xml
