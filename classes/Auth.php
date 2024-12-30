<?php

require_once 'Database.php';

class UserAuth {
    private $pdo;

    public function __construct(Database $database)
    {
         $this->pdo = $database->getConnection();
    }

    // Method to register a new user
    public function register($username, $email, $password) {
        // Check if user already exists
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
        $stmt->execute(['email' => $email, 'username' => $username]);

        if ($stmt->rowCount() > 0) {
            return "User already exists.";
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $success = $stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashedPassword]);

        return $success ? "User registered successfully." : "Registration failed.";
    }

    // Method to log in a user
    public function login($email, $password) {
        // Fetch the user by email
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() === 0) {
            return "User not found.";
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Start a session for the user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            return "Login successful.";
        } else {
            return "Invalid credentials.";
        }
    }

    // Method to log out a user
    public function logout() {
        session_start();
        session_destroy();
        return 1;
    }
}

// Usage example
/*
$auth = new UserAuth('localhost', 'test_db', 'root', '');
echo $auth->register('JohnDoe', 'johndoe@example.com', 'securepassword');
echo $auth->login('johndoe@example.com', 'securepassword');
echo $auth->logout();
*/
?>
