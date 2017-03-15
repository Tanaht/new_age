new_age
=======

A Symfony project created on January 12, 2017, 5:25 pm.

### Requirements:

- la commande "composer": https://getcomposer.org/
- Un environnement pour développer en localhost tel que: XAMP/MAMP/LAMP.
- Une version de PHP supérieur à 5.5.9.
- la commande git.

### Installation:

- cloner le repository new_age dans le répertoire "www" de votre environnement de développement. (sur xampp le repertoire se nomme "htdocs")
- exécuter la commande "php bin/symfony_requirements" pour vérifier l'intégrité de symfony. (le script indiquera les modules php nécéssaires à activer).
- utiliser la commande "composer install" à la racine du repertoire new_age. (la commande va installer les dépendances nécéssaires à Symfony)

Durant l'installation des dépendances, le script privilégie l'utilisation des commandes "zip"et "unzip" si elles ne sont pas installer, il va simplement cloné les repositories (ce qui prend plus de temps). (mais vous verrez un warning).
L'installation se termine là, après, il s'agit surtour de configuration mais en allant à l'adresse "localhost/new_age/web/" vous devriez voir la page d'accueil de symfony.


### Quelques commandes utiles :
- Un problème de cache ? pour vider le cache (utile lorsqu'on récupère des modifs) : 
php bin/console cache:clear
chmod -R 777 var/cache/

-Créer la base de données 
php bin/console doctrine:database:create

-Mettre a jour le schema de la base de données
php bin/console doctrine:schema:update --force

- Pour générer les Fake datas : 
php bin/console doctrine:fixtures:load --fixtures=src/VisiteurBundle/DataFixtures/ORM/Fake_Composante.php
php bin/console doctrine:fixtures:load --fixtures=src/UserBundle/DataFixtures/ORM/Fake_Utilisateur.php --append
    php bin/console doctrine:fixtures:load --fixtures=src/VisiteurBundle/DataFixtures/ORM/Fake_Ue.php --append
php bin/console doctrine:fixtures:load --fixtures=src/VisiteurBundle/DataFixtures/ORM/Fake_Etape.php --append
php bin/console doctrine:fixtures:load --fixtures=src/VisiteurBundle/DataFixtures/ORM/Fake_AnneeEtat.php --append

# Pour la génération du dossier d'upload :
mkdir web/uploads/images/
chmod 777 web/uploads/images/

*cf :  http://symfony.com/doc/current/doctrine.html*


### Documentations:

- Astuces sur Symfony : http://www.symfony2cheatsheet.com/
- PHP : https://secure.php.net/
- Symfony : http://symfony.com/doc/3.1/setup.html
- Doctrine : http://docs.doctrine-project.org/en/latest/
- Twig : http://twig.sensiolabs.org/doc/2.x/


