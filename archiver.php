<?php
$servername = "localhost";
$username = "root";   // ton utilisateur MySQL
$password = "";       // ton mot de passe MySQL
$dbname = "bts_bank";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Sécuriser les entrées
$nom = isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '';
$prenom = isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : '';
$cin = isset($_POST['cin']) ? htmlspecialchars($_POST['cin']) : '';
$montant = isset($_POST['montant']) ? floatval($_POST['montant']) : 0;
$taux = isset($_POST['taux']) ? floatval($_POST['taux']) : 0;
$duree = isset($_POST['duree']) ? intval($_POST['duree']) : 0;
$mensualite = isset($_POST['mensualite']) ? floatval($_POST['mensualite']) : 0;

$stmt = $conn->prepare("INSERT INTO archives (nom, prenom, cin, montant, taux, duree, mensualite) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssdidi", $nom, $prenom, $cin, $montant, $taux, $duree, $mensualite);

if ($stmt->execute()) {
    echo " Simulation archivée avec succès ! <a href='archives.php'>Voir les archives</a>";
} else {
    echo " Erreur : " . $conn->error;
}

$stmt->close();
$conn->close();
?>