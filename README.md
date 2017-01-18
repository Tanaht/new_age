new_age
=======

A Symfony project created on January 12, 2017, 5:25 pm.

Requirements:

- la commande "composer": https://getcomposer.org/
- Un environnement pour développer en localhost tel que: XAMP/MAMP/LAMP.
- Une version de PHP supérieur à 5.5.9.
- la commande git.

Installation:

- cloner le repository new_age dans le répertoire "www" de votre environnement de développement. (sur xampp le repertoire se nomme "htdocs")
- exécuter la commande "php bin/symfony_requirements" pour vérifier l'intégrité de symfony. (le script indiquera les modules php nécéssaires à activer).
- utiliser la commande "composer install" à la racine du repertoire new_age. (la commande va installer les dépendances nécéssaires à Symfony)

Durant l'installation des dépendances, le script privilégie l'utilisation des commandes "zip"et "unzip" si elles ne sont pas installer, il va simplement cloné les repositories (ce qui prend plus de temps). (mais vous verrez un warning).
L'installation se termine là, après, il s'agit surtour de configuration mais en allant à l'adresse "localhost/new_age/web/" vous devriez voir la page d'accueil de symfony.


Documentations:

- Astuces sur Symfony: http://www.symfony2cheatsheet.com/
- PHP:https://secure.php.net/
- Symfony: http://symfony.com/doc/3.1/setup.html
- Doctrine: http://docs.doctrine-project.org/en/latest/
- Twig: http://twig.sensiolabs.org/doc/2.x/
