#!/usr/bin/env bash

# Récupérer le code source
git pull origin master

# Récupérer les librairies
composer install --no-dev

# Vider le cache
drush cr

# Metre à jour la base drupal
drush updb -y

# Exporter les config de prod
drush csex prod -y

# Importer les configs
drush cim -y

# Vider le cache
drush cr
