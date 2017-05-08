#!/bin/bash

#Update du schema de la base
php bin/console d:s:u --force

#Gestion des utilisateurs
php bin/console doctrine:fixtures:load --fixtures=src/VisiteurBundle/DataFixtures/ORM/Fake_Composante.php
php bin/console doctrine:fixtures:load --fixtures=src/UserBundle/DataFixtures/ORM/Roles.php --append
php bin/console doctrine:fixtures:load --fixtures=src/UserBundle/DataFixtures/ORM/Fake_Utilisateur.php --append

#Etat de l'année
php bin/console doctrine:fixtures:load --fixtures=src/VisiteurBundle/DataFixtures/ORM/Fake_AnneeEtat.php --append

#Liste des enseignements
php bin/console doctrine:fixtures:load --fixtures=src/VisiteurBundle/DataFixtures/ORM/Fake_Etape.php --append
php bin/console doctrine:fixtures:load --fixtures=src/VisiteurBundle/DataFixtures/ORM/Fake_Ue.php --append
php bin/console doctrine:fixtures:load --fixtures=src/VisiteurBundle/DataFixtures/ORM/Fake_Cours.php --append
php bin/console doctrine:fixtures:load --fixtures=src/VisiteurBundle/DataFixtures/ORM/Fake_Notifications.php --append
