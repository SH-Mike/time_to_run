# time-to-run
This is a technical test project, for StadLine company

Sujet :
-------
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
