#!/bin/bash

php bin/console doctrine:fixtures:load --fixtures=src/VisiteurBundle/DataFixtures/ORM/Fake_Composante.php
php bin/console doctrine:fixtures:load --fixtures=src/UserBundle/DataFixtures/ORM/Fake_Utilisateur.php --append
