<!DOCTYPE html>
<html lang="en"  data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../public/style.css">
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
            <button class=" btn mr-4 toggle-btn btn-outline-primary" id="client"><a href="../dashboard.php"> Clients</a></button>
          </li>
          <li class="nav-item  ms-4">
            <button class=" btn btn-outline-primary mr-4  toggle-btn"  id="voiture"><a href="../dashboard.php"> Voitures</a></button>
          </li>
          <li class="nav-item  ms-4 me-8">
            <button class=" btn btn-outline-primary  mr-4  toggle-btn"  id="contrat"><a href="../dashboard.php"> Contrats</a> </button>
          </li>
          <li class="nav-item  ms-4">
        <button class="btn btn-secondary " id="create-account-toggle"><a href="../dashboard.php">Create User</a> </button>
          </li>
          <li class="nav-item  ms-4">
            <a class="nav-link text-danger  mr-4" href="../pages/logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- <div class="container d-flex justify-content-end">
    <button class="btn btn-primary my-4" id="create-account-toggle"> Create User</button>

  </div> -->

<div class="container min-vh-100">

