Todo list
==========


Petit topo :
-
Ce site web est un site permettant de créer des todolist.

Chaque todo lists peut contenir des taches. Les taches peuvent être associées à des catégories.

Une partie admin est disponible, elle permet de supprimer un utilisateur.


Installation du projet :
-

Pour installer le projet :

Clonez le repository grâce a cette commande :

````
https://github.com/RomainLENTZ/todolist.git
````

Par la suite, vous pouvez exécuter la commande :

````
composer install
````

Vous devez aussi créer une base de données et effectuer les migrations. Pour ce faire :
````
php bin/console doctrine:database:create
````

Puis :

````
php bin/console doctrine:migration:migrate
````

Afin d'alimenter la base, vous devez l'alimenter grâce a fixtures en utilisant la <br>commande :
````
php bin/console doctrine:fixtures:load 
````

La base de données est maintenant alimentée. Vous n'avez plus qu'a lancer le serveur en utilisant la commande :  
````
 symfony server:start
 ````

Créer des user en utilisant notre commande :
-

