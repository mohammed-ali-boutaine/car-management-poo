<?php
// include "../config.php";
include "../db/conn.php";
include "../functions/helpers.php";

// Authentication function
requireAuth();

$client = []; 
$id_from_url = (int) $_GET['id'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    // $id = (int) $_POST['id'];
    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin = $_POST['date_fin'] ?? '';
    $dure = (int) $_POST['dure'];
    $cin_client = $_POST['cin_client'] ?? '';
    $id_matric = $_POST['id_matric'] ?? '';

    // Validation
    if (empty($date_debut) || empty($date_fin) || empty($dure) || empty($cin_client) || empty($id_matric)) {
        redirect("../index.php");
        exit;
    }

    // Sanitize inputs
    // Sanitize inputs
    $id = (int) sanitize_input($id);
    $date_debut = sanitize_input($date_debut);
    $date_fin = sanitize_input($date_fin);
    $dure = (int) sanitize_input($dure);
    $cin_client = sanitize_input($cin_client);
    $id_matric = sanitize_input($id_matric);
    $id_from_url = (int) sanitize_input($id_from_url);

    // Update client in the database
    $stmt = $conn->prepare("UPDATE contrats SET date_debut = ?, date_fin = ?, dure = ?, cin_client = ?, id_matric = ? WHERE id = ?");
    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("ssisii", $date_debut, $date_fin, $dure, $cin_client, $id_matric, $id_from_url);
    if ($stmt->execute()) {
        $stmt->close();
        redirect("../index.php");
        exit;
    } else {
        die("Error executing the statement: " . $stmt->error);
    }
} elseif (!empty($id_from_url)) {
    // Fetch client data to populate the form
    $stmt = $conn->prepare("SELECT id, date_debut, date_fin, dure, cin_client, id_matric FROM contrats WHERE id = ?");
    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("i", $id_from_url);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $contrat = $result->fetch_assoc();
        $stmt->close();
    } else {
        die("Error fetching client data: " . $stmt->error);
    }
}

?>

<?php 
include "../inc/nav.php";
?>
<?php if (!empty($contrat)) : ?>
    <!-- Display the form -->
    <form method="POST" action="contrat_edit.php?id=<?php echo urlencode($id_from_url); ?>" class="p-4 border rounded">
    <div class="mb-3">
        <label for="date_debut" class="form-label">Date Début:</label>
        <input type="date" id="date_debut" name="date_debut" class="form-control" value="<?php echo htmlspecialchars($contrat['date_debut']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="date_fin" class="form-label">Date Fin:</label>
        <input type="date" id="date_fin" name="date_fin" class="form-control" value="<?php echo htmlspecialchars($contrat['date_fin']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="dure" class="form-label">Durée:</label>
        <input type="number" id="dure" name="dure" class="form-control" value="<?php echo htmlspecialchars($contrat['dure']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="cin_client" class="form-label">CIN Client:</label>
        <input type="text" id="cin_client" name="cin_client" class="form-control" value="<?php echo htmlspecialchars($contrat['cin_client']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="id_matric" class="form-label">Matricule:</label>
        <input type="text" id="id_matric" name="id_matric" class="form-control" value="<?php echo htmlspecialchars($contrat['id_matric']); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
<?php else : ?>
    <p>Contrat not found.</p>
<?php endif; ?>

<?php include "../inc/footer.php"; ?>
