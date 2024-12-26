
<?php
// include "../config.php";
include "../db/conn.php";
include "../functions/helpers.php";

// Authentication function
requireAuth();

if (empty($_GET["cin"])){
     redirect("/index.php");
}

$cin = sanitize_input($_GET["cin"]);

//  Delete from `contrats` where `cin_client` matches the `cin`
$stmt = $conn->prepare("DELETE FROM contrats WHERE cin_client = ?");
if ($stmt) {
    $stmt->bind_param("s", $cin);
    $stmt->execute();
    $stmt->close();
}

//  Delete from `clients` table if you want to delete the client as well
$stmt = $conn->prepare("DELETE FROM clients WHERE cin = ?");
if ($stmt) {
    $stmt->bind_param("s", $cin);
    $stmt->execute();
    $stmt->close();
}

redirect("/index.php");