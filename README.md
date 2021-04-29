## api-rest
Tuto Live Coding "Créer une API Rest" NouvelleTechno, 14 septembre 2019
https://nouvelle-techno.fr/actualites/live-coding-creer-une-api-rest

(Projet en cours, commenté; une fois l'API finalisée, utilisation d'AJAX pour interroger l'API depuis JavaScript -> https://nouvelle-techno.fr/actualites/live-coding-introduction-a-ajax)


Une "API REST", c'est quoi ?

- API = Application Programming Interface -> interface de programmation d'application, cad un système qui permet à une personne d'accéder aux données ou des modules de notre application à distance, depuis un autre système/machine/Internet

- REST = REpresentative State Transfer -> transfert représentatif d'état, norme définie en 2000 par l'un des fondateurs d'Apache, Roy Fielding. Globalement, une API REST sera conforme au standard créé en 2000. Ce standard ayant créé un haut niveau de certification, il est très difficile d'être considéré comme "RESTful".

- API REST = Pour rester simple, il s'agit de créer un accès à une application et permettre de venir y consommer des données ou des fonctionnalités. Ainsi, vous pouvez, avec une autre application ou un site, venir interroger l'application et utiliser ses données.

-> API REST permet de créer des dialogues entre un système et un autre selon 5 critères :

    Sans état : le serveur ne fait aucune relation entre les différents appels d'un même client. Il ne connaît pas l'état du client entre ces transactions. Le client fait des requêtes sans jamais avoir à répondre de quoi que ce soit au niveau du serveur

    Cacheable : le client doit être capable de garder nos données en cache pour optimiser les transactions. Afin d'éviter que le système doive interroger tout le temps le serveur, les données envoyées par le serveur vers le client sont mises en cache pour être réutilisées

    Orienté client-serveur : Il nous faut une architecture client-serveur. Poste client consomme l'API, serveur fournit l'API 

    Avec une interface uniforme : ceci permet à tout composant/appareil qui comprend le protocole HTTP (protocole applicatif utilisé pour l'échange de données du World Wide Web. En d'autres termes, c'est le protocole qui permet la diffusion de pages WEB et le fonctionnement des sites internet) de communiquer avec votre API. L'API est uniforme si elle est compatible avec n'importe quel appareil qui respecte le protocole HTTP. Quel que soit l'appareil, l'API répond de la même façon

    Avec un système de couches : c'est-à-dire avec des serveurs intermédiaires. Le client final ne doit pas savoir si il est connecté au serveur principal ou à un serveur intermédiaire. Le serveur est neutre, invisible; on se connecte à un seul chemin d'accès, c'est ensuite côté serveur qu'on gère le système de couches. Système utile principalement pour les grosses API parce qu'utilisées par beaucoup d'utilisateurs. (On ne l'implémentera pas avec notre petite API)


Nous allons traiter ici les bases de la création d'une API REST, en utilisant l'exemple de l'accès à une base de données de produits.


# Méthodes HTTP utilisées :

Lors de la création des fichiers de notre API, nous allons devoir prendre en compte une contrainte REST concernant la méthode utilisée pour effectuer nos différentes requêtes HTTP.
En effet, le standard est très strict. Il indique le rôle exact de chaque méthode HTTP dans notre API. Ainsi :

- GET pour lire des données
- POST pour ajouter des données
- PUT pour modifier des données
- DELETE pour supprimer des données

Nous devrons donc toujours indiquer quelle méthode sera utilisée en entête et vérifier que celle-ci est effectivement utilisée.

# Utilisation du logiciel Postman (Postman également dispo en ligne)
- simuler des requêtes, entrer une URL et choisir les paramètres que l'on souhaite envoyer, voir le retour effectué par l'API sans faire de l'AJAX ou du JavaScript
 
 (+ https://www.sqltutorial.org/seeit/  pour tester des requêtes SQL sur une bdd exemple)

# Organisation du projet

Projet réalisé principalement en PHP POO/MVC

1) Mettre en place la connexion à la base de données = config > Database.php

2) Ecrire les méthodes CRUD permettant d'accéder aux données, d'effectuer des actions sur la bdd; modèles de données contenus dans un fichier correspondant à une table SQL existante = models > Categories.php et Produits.php

3) Créer les fichiers qui seront utilisés par les utilisateurs de l'API depuis leur client = produits > par exemple lire.php permettra d'aller chercher tous les produits

