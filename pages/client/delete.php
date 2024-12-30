<?php
include "../../functions/helpers.php";
require_once '../../classes/Database.php';
require_once "../../classes/Client.php";

// Authentication function
requireAuth();

if (empty($_GET["cin"])) {
    redirect("/index.php");
}

$cin = sanitize_input($_GET["cin"]);

$conn = new Database();
$client = new Client($conn);

$client->delete($cin);



redirect("/index.php");
