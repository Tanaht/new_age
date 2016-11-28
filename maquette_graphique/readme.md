# Interface graphique de newAGE

*Il s'agit des pages prototypes de la nouvelle interface du projet newAGE.*

### Liste des pages prototypées par rôle:

#### Ingénieur système : 

 - **[ingé_sys]gestion_utilisateurs.html :**
Permet à l'ingénieur système d'effectuer les opérations de CRUD sur les utilisateurs.
 - **[ingé_sys]backup.html :**
Permet à l'ingénieur système d'effectuer des backup de la base de donnée directement via l'interfacee graphique.
Il peut aussi ajuster la fréquence de création de backup de la BDD via cette page.


#### Responsable comptables :
 - **[RC]balance.html : **
 Permet d'afficher la situation entre deux composantes de l'université. La validation de la balance peut donc se faire par les deux composantes par leur bouton "Validé". 
 - **[RC]gestion_annee.html : **
Permet d'afficher l'état de l'année, de créer une nouvelle année universitaire et passer à la période suivante de l'année.
 - **[RC]recherche_utilisateur.html:**
 Recherche utilisateur enrichie avec les actions de *modification de service* et d'*affichage de la fiche de service*.

#### Responsable des services :
 - **[RS]gerer_ue.html :** 
 Edition d'une UE par le RS. Le RS a accès à l'intégralité des UE de sa composante (d'où la présence d'une recherche par étape.
 - **[RS]gestion_mission.html : ** 
 Gestion des opérations de CRUD et affection à un enseignant d'une mission par le RS
 - **[RS]gestion_services.html : **
Page gérant la mise en service des UE. La recherche par étape permet de faire un filtre des UE à mettre en service.
Le bouton présélectionner les UE valides va précocher toutes les UE ayant le bon nombre d'heures de voeux.

#### Responsable d'étape :

 - **[RE]recherche_utilisateur.html : **
Recherche d'utilisateurs enrichie avec la possibilité de nommer un utilisateur responsable d'UE pour les étapes que gère le responsable d'UE.

#### Responsable d'UE :

 - **[RUE]gerer_ue.html :**
Permet de gérer les UE que gère le RUE.
Diviser un enseignement permet de créer un CMBDD et CMBMO par exemple (dans la pratique on s'assurera à respecter le volume horaire total lors de la division).
Le RUE a la possibilité de créer des groupes pour les UE ou au contraire d'en supprimer.
 - **[RUE]voeux.html :**
 Si l'enseignant est RUE il a la possibilité de faire ses voeux même si est connecté en tant que RUE

#### Enseignant :

 - **[enseignant]bilan_voeux.html : **
Permet d'afficher le bilan des vœux par enseignant pour sa composante.
 - **[enseignant]fiche_de_service.html : **
Permet à l'enseignant d'afficher sa fiche de service et de l'exporter au format PDF.
 - **[enseignant]notifications.html : **
Permet à l'enseignant de consulter ses alertes de façon plus détaillée que dans la barre de navigation.
 - **[enseignant]voeux.html :**
Permet à l'enseignant de faire ses vœux.
*Remarque: La possiblité de faire les mêmes vœux que l'année passée n'a pas encore été prototypée*

#### Visiteur :

 - **[visiteur]connexion.html :**
Page de connexion utilisateur
 - **[visiteur]enseignements.html : **
Recherche des enseignements avec une pré-recherche par étape.
 - **[visiteur]etat_annee:**
 Visualisation des périodes de l'année universitaire en cours
 - **[visiteur]mon_profil.html :**
Edition du profil de l'utilisateur
 - **[visiteur]recherche_utilisateur.html : **
Recherche des utilisateurs de newAGE par nom
 
