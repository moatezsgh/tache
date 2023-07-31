<?php
// Connectez-vous à la base de données MySQL
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'sct';

$conn = new mysqli($host, $user, $password, $database);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué: " . $conn->connect_error);
}

// API d'inscription
if (isset($_POST['inscription']) && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $CIN = $_POST['CIN'];
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $adresse = $_POST['adresse'];
    $id_departement = $_POST['id_departement'];
    $poste = $_POST['poste'];
    $mdp = $_POST['mdp'];

    // Vérifiez si l'employé existe déjà dans la base de données
    $sql = "SELECT * FROM employer WHERE CIN = '$CIN'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo json_encode(array('status' => 'error', 'message' => 'L\'employé existe déjà'));
        exit;
    }

    // Insérez l'employé dans la table 'employees'
    $sql = "INSERT INTO employer (cin,prenom,nom,adresse,id_departement,poste) VALUES ($cin,$prenom,$nom,$adresse,$id_departement,$poste)";
    $sql = "INSERT INTO connexion (id,mdp) VALUES ($cin,$mdp)";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(array('status' => 'success', 'message' => 'Inscription réussie'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Erreur lors de l\'inscription'));
    }
}

// API de login
if (isset($_POST['login']) && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST')  {
    $id = $_POST['id'];
    $mdp = $_POST['mdp'];
   

    // Vérifiez si les informations de connexion sont valides
    $sql = "SELECT * FROM connexion WHERE id = '$id' AND mdp = '$mdp'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $p = "SELECT poste FROM employer WHERE cin = '$id'";
        echo json_encode(array('status' => 'success', 'message' => 'Connexion réussie', 'poste' => $p));
        
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Identifiants invalides'));
    }
}

$conn->close();
?>