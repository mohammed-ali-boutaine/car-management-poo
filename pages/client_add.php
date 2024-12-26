<?php
// include "../config.php";
include "../db/conn.php";
include "../functions/helpers.php";

// Authentication function

requireAuth();

// db connection
if( $_SERVER["REQUEST_METHOD"] == "POST"){

    $cin = $_POST['cin'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $adress = $_POST['adress'] ?? '';
    $tel = $_POST['tel'] ?? '';

     // validation 
     if(empty($cin) || empty($nom) || empty($adress) || empty($tel)) {
        redirect("../index.php");
    }


     $cin = sanitize_input($cin);
     $nom  = sanitize_input($nom );
     $adress  =  sanitize_input($adress );
     $tel  = sanitize_input($tel );

     $stmt = $conn->prepare("INSERT INTO clients (cin, nom, adress, tel) VALUES (?, ?, ?, ?)");

     if ($stmt === false) {
          // Check for preparation errors
          die("Error preparing the statement: " . $conn->error);
          redirect("../index.php");
        }
      $stmt->bind_param("ssss", $cin, $nom, $adress, $tel);
      $stmt->execute();
      
    $stmt->close();
    redirect("/index.php");

}else{
    redirect("/index.php");
    exit;

}




