<?php
class UserAuth {
    private $pdo;

    public function __construct($host, $dbName, $username, $password) {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
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
            session_start();
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
        session_unset();
        session_destroy();
        return "Logged out successfully.";
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
