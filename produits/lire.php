<?php
// Donner accès aux utilisateurs de l'API à la liste de tous les produits contenus dans notre bdd via la méthode lire() du fichier Produits.php = http://apirest.test/produits/lire.php

// Headers requis (= définir les entêtes HTTP nécessaires au bon fonctionnement de l'API, pour effectuer des contrôles, faire des autorisations)

// pour autoriser ou interdire l'accès à l'API en fonction de l'origine de l'utilisateur. Par exemple, pour n'autoriser l'accès à l'API que par le site nouvelle-techno.fr, on remplace l'étoile par l'url du site en question. L'API ne répondra que si la requête vient de ce site. Avec *, accès depuis n'importe quel site ou appareil donc API publique
header("Access-Control-Allow-Origin: *"); 

// pour définir le contenu de la réponse, format des données envoyées. Ici on envoie une réponse en JSON. Parce que la norme REST implique de pouvoir répondre à n'importe quel type d'appareil respectant le protocole HTTP, on utilise du JSON car c'est un format de données très inter-opérable + charset utf-8 contient tous les caractères accentués, spéciaux et autres, donc permet d'assurer que tous les appareils afficheront les données
header("Content-Type: application/json; charset=UTF-8"); 

// méthode autorisée pour la requête en question. Ici, la requête fait de la lecture des données. Dans la norme REST, pour lire on est obligé d'utiliser la méthode GET
header("Access-Control-Allow-Methods: GET");

// durée de vie de la requête
header("Access-Control-Max-Age: 3600");


// headers/entêtes qu'on autorise, au niveau de la requête, vis-à-vis du poste client. Permet de filtrer certains types de headers et de données pris en compte
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


// On vérifie que la méthode utilisée par le poste client est correcte (pour être sûr que notre API respecte le standard REST)
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    // On inclut les fichiers de configuration et d'accès aux données
    include_once '../config/Database.php';
    include_once '../models/Produits.php';

    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnection();

    // On instancie les produits
    $produit = new Produits($db);

    // On récupère les données
    // Comme on n'a pas encore fait le fetch dans la méthode lire, au niveau du PDO on appelle ça un statement, cad qu'on récupère les infos sous la forme d'un statement. Souvent on écrit $stmt pour stocker
    $stmt = $produit->lire();

    // On vérifie si on a une réponse, cad au moins un produit
    if($stmt->rowCount() > 0){
       // Comme on doit pouvoir mettre les données en cache (norme REST), on va les envoyer en JSON. Pour simplifier les choses, en plus du tableau récupéré pour parcourir les données avec le fetch on va fabriquer un tableau en PHP, pour ensuite le transformer en JSON. 

       // On initialise un tableau associatif (on initialise un tableau vide + un sous-ensemble 'produits' vide également)
       $tableauProduits = [];
       $tableauProduits['produits'] = [];

       // On pourrait faire un fetchAll en PHP pour récupérer toutes les lignes mais avec une API REST, on doit aller vite. Or il a été démontré par une étude que de faire autant de fetch que de lignes existantes plutôt qu'un seul fetchAll permet d'aller plus vite. Donc on utilise une boucle while pour parcourir les lignes de notre tableau.

       // On parcourt les produits
       while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
           // une fois qu'on est sur notre ligne, avec la fonction extract() on récupère sous forme de variables chacune des colonnes de nos données, par exemple la colonne nom sera récupérée en $nom, conne description = $description, etc. Permet d'éviter d'avoir à parcourir chacune des colonnes individuellement.
           extract($row);

           // Ici on a un produit
           $prod = [
               "id" => $id,
               "nom" => $nom,
               "description" => $description,
               "prix" => $prix,
               "categories_id" => $categories_id,
               "categories_nom" => $categories_nom
           ];

           // On ajoute le tableau du produit dans le tableau Produits. Depuis PHP7.2, on n'a plus besoin de faire un array_push. On ajoute simplement des crochets vides après la variable du tableau pour faire un push. 
           $tableauProduits['produits'][] = $prod;

           // -> Pour chacune des lignes, le tableau $prod sera ajouté au tableauProduits
       }

       // On envoie le code réponse pour dire que la requête a fonctionné, 200 OK
       http_response_code(200);

       // On encode le tableau en json et on envoie 
       echo json_encode($tableauProduits);
    }

}else{
    // Mauvaise méthode utilisée -> On gère l'erreur

    // si la méthode utilise est incorrecte, on envoie au poste client un message d'erreur en réponse 
    http_response_code(405); // code 405 correspond à "La méthode n'est pas autorisée" pour l'en-tête (statut de la requête)
    echo json_encode(["message" => "La méthode n'est pas autorisée"]); // envoi d'un message écrit, encodé en JSON puisque c'est le format qui a été défini au niveau du header. Tableau qui indique à l'utilisateur un message (ici en français mais en anglais en général) disant que la méthode utilisée pour interroger le serveur n'est pas la bonne
}
