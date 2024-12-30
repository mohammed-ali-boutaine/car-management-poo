<?php
// include "../config.php";
include "../db/conn.php";
include "../functions/helpers.php";

requireAuth();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin = $_POST['date_fin'] ?? '';
    $dure = $_POST['dure'] ?? '';
    $cin_client = $_POST['cin_client'] ?? '';
    $id_matric = $_POST['id_matric'] ?? '';

    // Validation
    if (empty($date_debut) || empty($date_fin) || empty($dure) || empty($cin_client) || empty($id_matric)) {
     redirect("../index.php");

    }


    $date_debut = sanitize_input($date_debut);
    $date_fin = sanitize_input($date_fin);
    $dure = sanitize_input($dure);
    $cin_client = sanitize_input($cin_client);
    $id_matric = sanitize_input($id_matric);

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO contrats (date_debut, date_fin, dure, cin_client, id_matric) VALUES (?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ssiss", $date_debut, $date_fin, $dure, $cin_client, $id_matric);

    $stmt->execute();
      
    $stmt->close();
    redirect("/index.php");
} else {
    redirect("../index.php");
    exit;
}
?>
