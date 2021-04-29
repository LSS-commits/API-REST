<?php
// Headers requis (les mêmes que pour lire.php sauf que pour créer un enregistrement en bdd, on doit utiiser la méthode POST)
header("Access-Control-Allow-Origin: *"); 
header("Content-Type: application/json; charset=UTF-8"); 
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


// On vérifie la méthode
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // On inclut les fichiers de configuration et d'accès aux données
    include_once '../config/Database.php';
    include_once '../models/Produits.php';

    // On instancie la bdd
    $database = new Database();
    $db = $database->getConnection();

    // On instancie les produits
    $produit = new Produits($db);

    // Dans le cadre de la norme REST, on n'utilise pas la superglobale $_POST pour récupérer les infos envoyées par l'utilisateur mais le format JSON pour envoyer les infos car il rend les données interopérables entre tous les systèmes
    // Avec le format JSON, pour créer un produit, on va créer un ensemble de paires clé-valeur. Par exemple en testant une requête POST sur Postman, pour envoyer les infos, on écrirait les données dans Body en raw de la manière suivante (*les autres champs de la table produits sont en automatique donc pas inclus ici) et on enverrait
    // {
    //     "nom": "Produit1",
    //     "description": "Description du produit1",
    //     "prix": "89.99",
    //     "categories_id": "5"
    // }

    // On récupère les informations envoyées
    // php://input est un fichier virtuel qui est l'entrée php
    $donnees = json_decode(file_get_contents("php://input"));
    // var_dump($donnees); pour vérifier le retour des infos sur Postman après avoir cliqué sur envoyer

    // $donnees contient les différentes infos de notre produit à créer. On vérifie que tout est rempli
    if(!empty($donnees->nom) && !empty($donnees->description) && !empty($donnees->prix) && !empty($donnees->categories_id)){
        // Ici on a reçu les données
        // On hydrate notre objet. On n'a pas besoin de mettre en place une protection ici parce qu'on l'a déjà fait dans le modèle (méthode creer())
        $produit->nom = $donnees->nom;
        $produit->description = $donnees->description;
        $produit->prix = $donnees->prix;
        $produit->categories_id = $donnees->categories_id;

        // On va utiliser la méthode creer(); on n'a rien à passer dans la méthode puisqu'on a déjà hydraté au-dessus
        if($produit->creer()){
            // Ici la création a fonctionné
            // On envoie un code réponse 201 (le code de statut HTTP 201 Created indique que la requête a réussi et qu'une ressource a été créée en conséquence)
            http_response_code(201);
            echo json_encode(["message" => "L'ajout a été effectué"]);
        }else{
             // Ici la création n'a pas fonctionné
            // On envoie un code réponse 503 (Erreur serveur, service temporairement indisponible ou en maintenance)
            http_response_code(503);
            echo json_encode(["message" => "L'ajout n'a pas été effectué"]);
        }
    }

}else{
    // On gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}