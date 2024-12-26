<?php
// include "../config.php";
include "../db/conn.php";
include "../functions/helpers.php";

// Authentication function
requireAuth();

$client = []; 
$cin_from_url = $_GET['cin'] ?? ''; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    $cin = $_POST['cin'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $adress = $_POST['adress'] ?? '';
    $tel = $_POST['tel'] ?? '';

    // Validation
    if (empty($cin) || empty($nom) || empty($adress) || empty($tel) || empty($cin_from_url)) {
        redirect("../index.php");
        exit;
    }

    // Sanitize inputs
    $cin = sanitize_input($cin);
    $nom = sanitize_input($nom);
    $adress = sanitize_input($adress);
    $tel = sanitize_input($tel);
    $cin_from_url = sanitize_input($cin_from_url);

    // Update client in the database
    $stmt = $conn->prepare("UPDATE clients SET cin = ?, nom = ?, adress = ?, tel = ? WHERE cin = ?");
    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("sssss", $cin, $nom, $adress, $tel, $cin_from_url);
    if ($stmt->execute()) {
        $stmt->close();
        redirect("../index.php");
        exit;
    } else {
        die("Error executing the statement: " . $stmt->error);
    }
} elseif (!empty($cin_from_url)) {
    // Fetch client data to populate the form
    $stmt = $conn->prepare("SELECT cin, nom, adress, tel FROM clients WHERE cin = ?");
    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("s", $cin_from_url);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $client = $result->fetch_assoc();
        $stmt->close();
    } else {
        die("Error fetching client data: " . $stmt->error);
    }
}

?>

<?php 
include "../inc/nav.php";
?>
<?php if (!empty($client)) : ?>
<!-- Display the form -->
<form method="POST" action="client_edit.php?cin=<?php echo urlencode($cin_from_url); ?>" class="p-4 border rounded">
    <div class="mb-3">
        <label for="cin" class="form-label">CIN:</label>
        <input type="text" id="cin" name="cin" class="form-control" value="<?php echo htmlspecialchars($client['cin']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="nom" class="form-label">Name:</label>
        <input type="text" id="nom" name="nom" class="form-control" value="<?php echo htmlspecialchars($client['nom']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="adress" class="form-label">Address:</label>
        <input type="text" id="adress" name="adress" class="form-control" value="<?php echo htmlspecialchars($client['adress']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="tel" class="form-label">Phone:</label>
        <input type="text" id="tel" name="tel" class="form-control" value="<?php echo htmlspecialchars($client['tel']); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
<?php else : ?>
    <p>Client not found.</p>
<?php endif; ?>

<?php 

include "../inc/footer.php";

?>
