<?php
// include "./config.php";
include "./db/conn.php";
include "./functions/helpers.php";


// if session exists redirect to dashbord.php
session_start();
if (isset($_SESSION['user_id'])) {
     redirect("dashboard.php");
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // get data from form
    $email =sanitize_input($_POST["email"]);
    $password =sanitize_input($_POST["password"]);

    // form validation
    if( empty($email) || empty($password)) {
        $error = "All fields are required.";

    // email validation
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";

    // login stuff
    }else{


        $checkStmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {

            $checkStmt->bind_result($id, $name, $hashed_password);
            $checkStmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // handle login
                $_SESSION["user_id"] = $id;
                $_SESSION["user_name"] = $name;
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "invalide password.";
            }
        }else{
            $error = "Email not exists.";
        }
        $checkStmt->close();

    }
}

?>


<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #343a40;
            color: #fff;
        }
        form {
            max-width: 500px;
            background-color: #171a1c;
            width: 90%;
            margin: 50px auto;
            border: 1px solid gray;
            padding: 50px 20px;
            border-radius: 12px;
        }
    </style>
</head>
<body class="dark">
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Login</h2>

        <!-- Show error message -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Login form -->
        <form method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                <div class="invalid-feedback">
                    Please enter a valid email.
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <div class="invalid-feedback">
                    Please enter your password.
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
        <!-- Bootstrap Bundle JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
</body>
</html>