<?php
// include "../config.php";
// include "../db/conn.php";
include "../../functions/helpers.php";
require_once '../../classes/Database.php';
require_once  "../../classes/Client.php";

requireAuth();


// Authentication function
$conn = new Database();
$client = new Client($conn);


// db connection
if( $_SERVER["REQUEST_METHOD"] == "POST"){

  $cin = sanitize_input($_POST['cin'] ?? '');
  $nom = sanitize_input($_POST['nom'] ?? '');
  $adress = sanitize_input($_POST['adress'] ?? '');
  $tel = sanitize_input($_POST['tel'] ?? '');

    // Validation
    if (empty($cin) || empty($nom) || empty($adress) || empty($tel)) {
      redirect("../index.php");
      exit;
  }



  $client->create($cin, $nom, $adress, $tel);


    redirect("/index.php");

}else{
    redirect("/index.php");
    exit;

}




