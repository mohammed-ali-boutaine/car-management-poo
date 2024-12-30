<?php


require_once "./classes/Database.php";
require_once "./classes/Auth.php";


$conn = new Database();
$auth = new UserAuth($conn);


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

        $message = $auth->login($name,$email,$password);


}
}else{
     redirect("/dashboard.php");
}