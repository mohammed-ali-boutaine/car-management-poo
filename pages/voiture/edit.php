
<?php
// include "../config.php";
include "../db/conn.php";
include "../functions/helpers.php";

// Authentication function
requireAuth();

$voiture = [];
$matricule_from_url = $_GET['matricule'] ?? '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Handle form submission
   $matricule = $_POST['matricule'] ?? '';
   $marque = $_POST['marque'] ?? '';
   $modele = $_POST['modele'] ?? '';
   $annee = $_POST['annee'] ?? '';

    // Validation
    if (empty($matricule) || empty($marque) || empty($modele) || empty($annee) || empty($matricule_from_url)) {
        redirect("../index.php");
        exit;
    }

    // Sanitize inputs
    $matricule = sanitize_input($matricule);
    $marque = sanitize_input($marque);
    $modele = sanitize_input($modele);
    $annee = (int) sanitize_input($annee);
    $matricule_from_url = sanitize_input($matricule_from_url);

    // Update client in the database
    $stmt = $conn->prepare("UPDATE voitures SET matricule = ?, marque = ?, modele = ?, annee = ? WHERE matricule = ?");
    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("sssis", $matricule, $marque, $modele, $annee, $matricule_from_url);
    if ($stmt->execute()) {
        $stmt->close();
        redirect("../index.php");
        exit;
    } else {
        die("Error executing the statement: " . $stmt->error);
    }
} elseif (!empty($matricule_from_url)) {
    // Fetch client data to populate the form
    $stmt = $conn->prepare("SELECT matricule, marque, modele, annee FROM voitures WHERE matricule = ?");
    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("s", $matricule_from_url);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $voiture = $result->fetch_assoc();
        $stmt->close();
    } else {
        die("Error fetching client data: " . $stmt->error);
    }
}

?>

<?php 
include "../inc/nav.php";
?>
<?php if (!empty($voiture)) : ?>
    <!-- Display the form -->
    <form method="POST" action="voiture_edit.php?matricule=<?php echo urlencode($matricule_from_url); ?>" class="p-4 border rounded">
    <div class="mb-3">
        <label for="matricule" class="form-label">Matricule:</label>
        <input type="text" id="matricule" name="matricule" class="form-control" value="<?php echo htmlspecialchars($voiture['matricule']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="marque" class="form-label">Marque:</label>
        <input type="text" id="marque" name="marque" class="form-control" value="<?php echo htmlspecialchars($voiture['marque']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="modele" class="form-label">Modele:</label>
        <input type="text" id="modele" name="modele" class="form-control" value="<?php echo htmlspecialchars($voiture['modele']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="annee" class="form-label">Année:</label>
        <input type="number" id="annee" name="annee" class="form-control" value="<?php echo htmlspecialchars($voiture['annee']); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
<?php else : ?>
    <p>Voiture not found.</p>
<?php endif; ?>

<?php include "../inc/footer.php"; ?>
