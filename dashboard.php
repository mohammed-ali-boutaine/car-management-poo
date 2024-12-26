<?php
// include "./config.php";
include "./db/conn.php";
include "./functions/helpers.php";

// Authentication function
requireAuth();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];


    // validation 
    if(empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }else{
        $name = htmlspecialchars($name);
        $email = htmlspecialchars($email);
        $password = password_hash(htmlspecialchars($password), PASSWORD_BCRYPT); // Hash password

        // Check for email 
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $error = "Email is already registered.";
        } else {

  // Insert into database
  $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $name, $email, $password);

  if ($stmt->execute()) {
      $_SESSION["user_id"] = $stmt->insert_id; // Use last insert ID for the session
      $_SESSION["user_name"] = $name;
      header("Location: login.php");
      exit();
  } else {
      $error = "Error: " . $stmt->error;
  }

  $stmt->close();
}
$checkStmt->close();
}
$conn->close();


}
?>
<!DOCTYPE html>
<html lang="en"  data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="public/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
      <!-- Logo/Name -->
      <a class="navbar-brand" href="/dashboard.php">Dashboard - <span class="h6 text-primary"><u> <?php echo htmlspecialchars($_SESSION["user_name"]); ?></u></span> </a>
      
      <!-- Toggle button for mobile -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navigation links -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
       

          <li class="nav-item  ms-4">
            <button class=" btn mr-4 toggle-btn btn-outline-primary" id="client"> Clients</button>
          </li>
          <li class="nav-item  ms-4">
            <button class=" btn btn-outline-primary mr-4  toggle-btn"  id="voiture"> Voitures</button>
          </li>
          <li class="nav-item  ms-4 me-8">
            <button class=" btn btn-outline-primary  mr-4  toggle-btn"  id="contrat"> Contrats</button>
          </li>
          <li class="nav-item  ms-4">
        <button class="btn btn-secondary " id="create-account-toggle"> Create User</button>
          </li>
          <li class="nav-item  ms-4">
            <a class="nav-link text-danger  mr-4" href="pages/logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- <div class="container d-flex justify-content-end">
    <button class="btn btn-primary my-4" id="create-account-toggle"> Create User</button>

  </div> -->

<div class="container">








<!-- ---------------------------------  -->
<!-- ---------------------------------  -->
<!-- --------       Clients   --------  -->

    <div class="client data-holder  d-block">
        <div class="container d-flex justify-content-between align-items-center">
                <h2>Clients</h2>
                <button class="btn btn-primary my-4" id="create-client-toggle"> Ajouter Client</button>
        </div>

        <?php  

             $result = $conn->query("SELECT * FROM clients");
if ($result->num_rows > 0) {
    echo "<table class='table table-dark  table-striped table-hover'>
    <thead>
      <tr>
        <th scope='col'>Cin</th>
        <th scope='col'>Nom</th>
        <th scope='col'>Adress</th>
        <th scope='col'>Tel</th>
        <th scope='col'>Actions</th>
      </tr>
    </thead>
    <tbody>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["cin"] . "</td>
                <td>" . $row["nom"] . "</td>
                <td>" . $row["adress"] . "</td>
                <td>" . $row["tel"] . "</td>
                <td><a href='pages/client_edit.php?cin=" . urlencode($row["cin"]) . "'>Edit</a> | <a href='pages/client_delete.php?cin=" . urlencode($row["cin"]) . "'>Delete</a></td>
              </tr>";
    }
    echo "</table>";

} else {
    echo "No clients found.";
}

        
        ?>










<!-- ---------------------------------  -->
<!-- ---------------------------------  -->
<!-- --------       Voitures   --------  -->



    </div>
    <div class="voiture  data-holder">

    <div class="container d-flex justify-content-between align-items-center">
                <h2>Voitures</h2>
                <button class="btn btn-primary my-4" id="create-voiture-toggle"> Ajouter Voiture</button>
        </div>
    

        <?php  

             $result = $conn->query("SELECT * FROM voitures");
if ($result->num_rows > 0) {
    echo "<table class='table table-dark  table-striped table-hover'>
    <thead>
      <tr>
        <th scope='col'>Matricule</th>
        <th scope='col'>Marque</th>
        <th scope='col'>Modele</th>
        <th scope='col'>annnee</th>
        <th scope='col'>Actions</th>
      </tr>
    </thead>
    <tbody>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["matricule"] . "</td>
                <td>" . $row["marque"] . "</td>
                <td>" . $row["modele"] . "</td>
                <td>" . $row["annee"] . "</td>
                <td><a href='pages/voiture_edit.php?matricule=" . $row["matricule"] . "'>Edit</a> | <a href='pages/voiture_delete.php?matricule=" . $row["matricule"] . "'>Delete</a></td>
              </tr>";
    }
    echo "</table>";

} else {
    echo "No Voitures found.";
}

        
        ?>










<!-- ---------------------------------  -->
<!-- ---------------------------------  -->
<!-- --------       Contrat   --------  -->


    </div>
    <div class="contrat data-holder">

        <div class="container d-flex justify-content-between align-items-center">
                <h2>Contrat</h2>
                <button class="btn btn-primary my-4" id="create-contrat-toggle"> Ajouter Contrat</button>
        </div>

    <?php  

$result = $conn->query("SELECT 
                            contrats.id,
                            contrats.date_debut,
                            contrats.date_fin,
                            contrats.dure,
                            contrats.cin_client AS cin,
                            clients.nom,
                            clients.adress,
                            voitures.matricule,
                            voitures.marque,
                            voitures.modele,
                            voitures.annee
                        FROM contrats
                        JOIN clients ON clients.cin = contrats.cin_client
                        JOIN voitures ON voitures.matricule = contrats.id_matric");

if ($result->num_rows > 0) {
  echo "<table class='table table-dark table-striped table-hover'>
          <thead>
              <tr>
                  <th scope='col'>ID</th>
                  <th scope='col'>Date Debut</th>
                  <th scope='col'>Date Fin</th>
                  <th scope='col'>Dure</th>
                  <th scope='col'>Client CIN</th>
                  <th scope='col'>Nom</th>
                  <th scope='col'>Adress</th>
                  <th scope='col'>Matricule</th>
                  <th scope='col'>Marque</th>
                  <th scope='col'>Modele</th>
                  <th scope='col'>Annee</th>
                  <th scope='col'>Actions</th>
              </tr>
          </thead>
          <tbody>";
          while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . $row["date_debut"] . "</td>
                    <td>" . $row["date_fin"] . "</td>
                    <td>" . $row["dure"] . "</td>
                    <td>" . $row["cin"] . "</td>
                    <td>" . $row["nom"] . "</td>
                    <td>" . $row["adress"] . "</td>
                    <td>" . $row["matricule"] . "</td>
                    <td>" . $row["marque"] . "</td>
                    <td>" . $row["modele"] . "</td>
                    <td>" . $row["annee"] . "</td>
                    <td>
                        <a href='pages/contrat_edit.php?id=" . $row["id"] . "'>Edit</a> | 
                        <a href='pages/contrat_delete.php?id=" . $row["id"] . "'>Delete</a>
                    </td>
                  </tr>";
        }
        echo "</tbody></table>";

} else {
echo "No contrats found.";
}

?>









    
    </div>


















</div>













<!-- add client form  -->

<div class="container mt-5 create-client-form d-none">
    <form action="./pages/client_add.php" method="POST">
        <h2 class="mb-4">Client Information Form</h2>

        <!-- CIN -->
        <div class="mb-3">
            <label for="cin" class="form-label">CIN</label>
            <input name="cin" type="text" class="form-control" id="cin" placeholder="Enter CIN" required>
        </div>

        <!-- Name -->
        <div class="mb-3">
            <label for="nom" class="form-label">Name</label>
            <input name="nom" type="text" class="form-control" id="nom" placeholder="Enter name" required>
        </div>

        <!-- Address -->
        <div class="mb-3">
            <label for="adress" class="form-label">Address</label>
            <input name="adress" type="text" class="form-control" id="adress" placeholder="Enter address" required>
        </div>

        <!-- Telephone -->
        <div class="mb-3">
            <label for="tel" class="form-label">Telephone</label>
            <input name="tel" type="tel" class="form-control" id="tel" placeholder="Enter phone number" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>






<!-- add voiture form  -->

<div class="container mt-5 create-voiture-form d-none" >
        <form action="./pages/voiture_add.php" method="POST">
        <h2 class="mb-4">Car Information Form</h2>

            <div class="mb-3">
                <label for="matricule" class="form-label">Matricule</label>
                <input name="matricule" type="text" class="form-control" id="matricule" placeholder="Enter matricule">
            </div>
            <div class="mb-3">
                <label for="marque" class="form-label">Marque</label>
                <input name="marque"  type="text" class="form-control" id="marque" placeholder="Enter marque">
            </div>
            <div class="mb-3">
                <label for="modele" class="form-label">Modèle</label>
                <input name="modele" type="text" class="form-control" id="modele" placeholder="Enter modèle">
            </div>
            <div class="mb-3">
                <label for="annee" class="form-label">Année</label>
                <input name="annee" type="number" class="form-control" id="annee" placeholder="Enter année">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>





<!-- add contrat form  -->

<div class="container mt-5 create-contrat-form d-none">
    <form action="./pages/contrat_add.php" method="POST">
        <h2 class="mb-4">Contract Information Form</h2>

        <!-- Start Date -->
        <div class="mb-3">
            <label for="date_debut" class="form-label">Start Date</label>
            <input name="date_debut" type="date" class="form-control" id="date_debut" required>
        </div>

        <!-- End Date -->
        <div class="mb-3">
            <label for="date_fin" class="form-label">End Date</label>
            <input name="date_fin" type="date" class="form-control" id="date_fin" required>
        </div>

        <!-- Duration -->
        <div class="mb-3">
            <label for="dure" class="form-label">Duration (days)</label>
            <input name="dure" type="number" class="form-control" id="dure" placeholder="Enter duration in days" required>
        </div>

        <!-- Client CIN -->
        <div class="mb-3">
            <label for="cin_client" class="form-label">Client CIN</label>
            <input name="cin_client" type="text" class="form-control" id="cin_client" placeholder="Enter client CIN" required>
        </div>

        <!-- Vehicle Matricule -->
        <div class="mb-3">
            <label for="id_matric" class="form-label">Vehicle Matricule</label>
            <input name="id_matric" type="text" class="form-control" id="id_matric" placeholder="Enter vehicle matricule" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>



<!-------------------------- forms  -->


<form class="create-account-form d-none" method="POST" action="" class="needs-validation mx-auto " novalidate>
<div class="container mt-5">
        <h2 class="mb-4">Create Account</h2>

         <!-- show errors -->
        <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="user..." required>
         <div class="invalid-feedback">
                    Please enter your full name.
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="example@gmail.com" required>
                <div class="invalid-feedback">
                    Please enter a valid email address.
                </div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="strong password..." required>
                <div class="invalid-feedback">
                    Please enter a password.
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>













<script src="public/app.js"></script>

<script>
        // Bootstrap form validation
        (function () {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>