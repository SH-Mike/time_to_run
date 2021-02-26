# time-to-run
This is a technical test project, for StadLine company

Sujet
---
Créer un site permettant la gestion de sorties de courses à pied.
Une sortie de course à pied est définie comme ceci :  
  • Utilisateur  
  • Type de sortie (entraînement, course, loisirs, etc.)  
  • Date et heure de début              
  • Durée                                         
  • Distance                                 
  • Commentaire                           
  

Lors de la création ou modification d'une sortie, il faut calculer et enregistrer :  
  • la vitesse moyenne (en km/h, 11.1km/h par exemple, on pourra donc enregistrer "11.1")    
  • l'allure moyenne (en min/km, 5'24" par exemple, on pourra donc enregistrer "324")    
  • Le site doit être sécurisé. Une authentification via http basic auth sur un provider in memory est amplement suffisante.  

Une fois l'utilisateur connecté, différents écrans doivent permettre de :  
  • lister les sorties (affichage des principales informations dont la vitesse moyenne et l'allure moyenne)  
  • ajouter / modifier une sortie  
  • supprimer une sortie  
  
Une API doit être mise à disposition. Cette API ne doit pas être sécurisée. Par le biais de cette API, il doit être possible de :  
  •lister toutes les sorties  
  •lister les sorties d'un utilisateur  
  •récupérer le détail d'une sortie  

Installation du projet  
---
1. Créer la base de données "time_to_run" sur un serveur de base de données MySQL  
2. Cloner le projet "git clone https://github.com/SH-Mike/time_to_run.git"  
3. Se mettre dans le dossier "time_to_run" et exécuter la commande "composer install" pour récupérer les composants Symfony nécessaires à l’utilisation de l’application    
  3.1 NB : Des problèmes ont parfois été rencontrés lors de l’installation du projet sur d’autres machines, à cause du package fzaninotto/faker. Pour pallier toute éventualité, exécuter également la commande "composer require fzaninotto/faker"  
4. Exécuter la commande "php bin/console doctrine:migrations:migrate" pour générer la structure des tables telles que décrites dans les différentes migrations Doctrine  
5. Exécuter la commande "php bin/console doctrine:fixtures:load" pour intégrer les données de test, contenues dans la fixture développée, dans la base de données   
6. Pour lancer un serveur web PHP et commencer à utiliser l’application, exécuter la commande "php -S localhost:8000 -t public/", se connecter ensuite à l’url http://localhost:8000/ pour naviguer sur la partie site web de l’application.  

Description des étapes réalisées
---
1/ Création de l'entité "User" et de son repository  
Commande utilisée "php bin/console make:entity User".  
Migration créée pour modification de la base de données via Doctrine.  

2/ Création d'une fixture prenant en charge les "User"  
Installation du bundle DoctrineFixturesBundle via la commande "composer require --dev orm-fixtures" (ceci permet de charger rapidement de petits volumes de données de test).  
Modification de la fixture pour prise en charge de la création de données dans la table "user" via la commande "php bin/console doctrine:fixtures:load".  

3/ Création de l'entité "OutingType" et de son repository  
Commande utilisée "php bin/console make:entity OutingType".  
Migration créée pour modification de la base de données via Doctrine.  

4/ Modification de la fixture pour prise en charge des "OutingType"  
Modification de la fixture pour prise en charge de la création de données dans la table "outing_type" via la commande "php bin/console doctrine:fixtures:load".  

5/ Création de l'entité "Outing" et de son repository  
Commande utilisée "php bin/console make:entity OutingType".  
Liaison avec l'entité "User" et l'entité "OutingType".  
Migration créée pour modification de la base de données via Doctrine.  

6/ Modification de la fixture pour prise en charge des "Outing"  
Modification de la fixture pour prise en charge de la création de données dans la table "outing" via la commande "php bin/console doctrine:fixtures:load".  

7/ Ajout d'un mot de passe sur l'entité "User"  
Pour permettre la connexion à son compte utilisateur, on utilise un mot de passe stocké en base de données.  

8/ Création d'un contrôleur d'accueil (HomeController)  
Affichage d'une page d'accueil qui recevra l'utilisateur lors de son arrivée sur le site.  
Création via la commande "php bin/console make:controller HomeController".  

9/ Création d'un premier jet de design pour rendre le projet plus attractif et fonctionnel  
Création d'un header et d'un footer, mise à jour du fichier template base.html.twig pour inclusion de Bootstrap et du fichier app.css qui contient le css propre au projet.  
Ajout de la bibliothèque fontawesome pour afficher de petites icônes rendant le site plus attractif.  

10/ Gestion de la connexion / déconnexion via un provider in database et d'un formulaire de connexion
Modification des paramètres du fichier security.yaml pour ajout du provider in database qui pointe sur l'entité User (table user) avec comme username l'adresse mail.  
Ajout d'un formulaire de login, d'une route de login et de logout dans le UserController.  

11/ Calcul de la durée, de la vitesse moyenne et de l'allure moyenne  
Ajout du calcul de la durée, de la vitesse et de l'allure moyenne dans l'entité User pour accès direct via un "getXXX()" comme pour n'importe quelle donnée stockée.  
Note: Bien qu'il soit demandé dans le sujet de stocker ces 3 données, puisque ce sont des données calculées, j'ai pris la décision de ne pas les stocker. Elles sont accessibles à tout moment depuis un objet "Outing" via leurs "accesseurs".  

12/ Création des fonctions d'ajout, de modification et de suppression de sorties (Outing)  
Un bouton en haut de page permet l'ajout d'une sortie via un formulaire.  
Deux boutons (jaune pour modification, rouge pour suppression) sur chaque ligne d'un tableau de sorties permettent de rediriger respectivement vers les pages de modification (où un formulaire reprenant les informations de la sortie est affiché) et de suppression (qui redirige systématiquement en affichant un message à l'utilisateur selon la bonne suppression ou non des données).  

13/ Création de l'API  
Création d'un contrôleur contenant les 3 fonctions API (liste des sorties, liste des sorties d'un utilisateur, détail d'une sortie).  

14/ Revue de code et tests  
Correction des bugs (format de données).  
Améliorations du code.  

15/ Partie bonus - Création d'une page de gestion des types de sorties  
Création d'un contrôleur OutingTypeController faisant la gestion des différentes fonction CRUD (Create, Read, Update, Delete).  
Création d'un formulaire OutingTypeType permettant l'ajout et la modification des types de sorties.  
Création des templates pour affichage des écrans.  

16/ Revue de code finale  
Dernières retouches sur le code.  

J'estime avoir passé un peu plus de 6h sur le développement de ce projet (hors documentation)