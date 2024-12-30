
<?php
// include "../config.php";
include "../db/conn.php";
include "../functions/helpers.php";


// Authentication function
requireAuth();


if (empty($_GET["matricule"])){
     redirect("/index.php");
}

$matricule = sanitize_input($_GET["matricule"]);


// Optionally, Step 3: Delete from `clients` table if you want to delete the client as well
$stmt = $conn->prepare("DELETE FROM voitures WHERE matricule = ?");
if ($stmt) {
    $stmt->bind_param("s", $matricule);
    $stmt->execute();
    $stmt->close();
}

redirect("/index.php");
