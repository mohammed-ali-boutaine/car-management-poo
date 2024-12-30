<?php

require_once "./classes/Auth.php";
$conn = new Database();
$auth = new UserAuth($conn);

$auth->logout();

header("Location: /index.php");
exit();