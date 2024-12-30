<?php
// include "../config.php";
include "../db/conn.php";
include "../functions/helpers.php";

requireAuth();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricule = $_POST['matricule'] ?? '';
    $marque = $_POST['marque'] ?? '';
    $modele = $_POST['modele'] ?? '';
    $annee = $_POST['annee'] ?? '';

    // Validation
    if (empty($matricule) || empty($marque) || empty($modele) || empty($annee) || $annee <= 0) {
     redirect("../index.php");
    }

    $matricule = sanitize_input($matricule);
    $marque = sanitize_input($marque);
    $modele = sanitize_input($modele);
    $annee = sanitize_input($annee);

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO voitures (matricule, marque, modele, annee) VALUES (?, ?, ?, ?)");

    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
        redirect("../index.php");

    }

    // Bind parameters
    $stmt->bind_param("ssss", $matricule, $marque, $modele, $annee);

    $stmt->execute();
      
    $stmt->close();
    redirect("/index.php");
} else {
    redirect("../index.php");
    exit;
}
?>
