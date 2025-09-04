<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bts_bank";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nom = $conn->real_escape_string($_POST['nom']);
    $prenom = $conn->real_escape_string($_POST['prenom']);
    $cin = $conn->real_escape_string($_POST['cin']);
    $montant = floatval($_POST['montant']);
    $taux = floatval($_POST['taux']);
    $duree = intval($_POST['duree']);
    $mensualite = floatval($_POST['mensualite']);

    $stmt = $conn->prepare("UPDATE archives SET nom=?, prenom=?, cin=?, montant=?, taux=?, duree=?, mensualite=? WHERE id=?");
    $stmt->bind_param("sssddidi", $nom, $prenom, $cin, $montant, $taux, $duree, $mensualite, $id);

    if ($stmt->execute()) {
        header("Location: archives.php?message=" . urlencode("Enregistrement modifié avec succès!"));
    } else {
        header("Location: archives.php?message=" . urlencode("Erreur lors de la modification: " . $conn->error));
    }
    
    $stmt->close();
    $conn->close();
    exit();
}
?>