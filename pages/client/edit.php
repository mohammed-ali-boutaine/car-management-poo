<?php
include "../../functions/helpers.php";
require_once "../../classes/Database.php";
require_once "../../classes/Client.php";

// Authentication function
requireAuth();

// Initialize database and client class
$conn = new Database();
$client = new Client($conn);

$clientData = [];
$cin_from_url = sanitize_input($_GET['cin'] ?? '');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle form submission
    $cin = sanitize_input($_POST['cin'] ?? '');
    $nom = sanitize_input($_POST['nom'] ?? '');
    $adress = sanitize_input($_POST['adress'] ?? '');
    $tel = sanitize_input($_POST['tel'] ?? '');

    // Validation
    if (empty($cin) || empty($nom) || empty($adress) || empty($tel) || empty($cin_from_url)) {
        redirect("../index.php");
        exit;
    }

    // Update client in the database
    $client->update($cin_from_url, $cin, $nom, $adress, $tel);

    redirect("/index.php");
} elseif (!empty($cin_from_url)) {
    // Fetch client data to populate the form
    $clientData = $client->getByCIN($cin_from_url);
}
?>

<?php include "../../inc/nav.php"; ?>
<?php if (!empty($clientData)) : ?>
<!-- Display the form -->
<form method="POST" action="edit.php?cin=<?php echo urlencode($cin_from_url); ?>" class="p-4 border rounded">
    <div class="mb-3">
        <label for="cin" class="form-label">CIN:</label>
        <input type="text" id="cin" name="cin" class="form-control" value="<?php echo htmlspecialchars($clientData['cin']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="nom" class="form-label">Name:</label>
        <input type="text" id="nom" name="nom" class="form-control" value="<?php echo htmlspecialchars($clientData['nom']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="adress" class="form-label">Address:</label>
        <input type="text" id="adress" name="adress" class="form-control" value="<?php echo htmlspecialchars($clientData['adress']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="tel" class="form-label">Phone:</label>
        <input type="text" id="tel" name="tel" class="form-control" value="<?php echo htmlspecialchars($clientData['tel']); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
<?php else : ?>
    <p>Client not found.</p>
<?php endif; ?>

<?php include "../../inc/footer.php"; ?>
